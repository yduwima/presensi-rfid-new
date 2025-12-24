<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Absensi extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Siswa_model');
        $this->load->model('Guru_model');
        $this->load->model('Absensi_model');
        $this->load->model('Hari_kerja_model');
        $this->load->model('Settings_model');
        $this->load->library('Whatsapp');
    }

    public function index() {
        // Main RFID scanning page (no login required)
        $data['title'] = 'Absensi RFID';
        $data['settings'] = $this->Settings_model->get();
        $this->load->view('absensi/scan', $data);
    }

    public function scan() {
        // Handle RFID scan via AJAX
        $rfid_uid = $this->input->post('rfid_uid');
        
        if (empty($rfid_uid)) {
            echo json_encode(array(
                'success' => false,
                'message' => 'RFID UID tidak boleh kosong'
            ));
            return;
        }

        // Check if RFID belongs to siswa or guru
        $siswa = $this->Siswa_model->get_by_rfid($rfid_uid);
        $guru = $this->Guru_model->get_by_rfid($rfid_uid);

        if (!$siswa && !$guru) {
            echo json_encode(array(
                'success' => false,
                'message' => 'Kartu RFID tidak terdaftar'
            ));
            return;
        }

        // Get scheduled work time for today
        $hari = date('l');
        $hari_indonesia = $this->_translate_day($hari);
        $hari_kerja = $this->Hari_kerja_model->get_by_hari($hari_indonesia);

        if (!$hari_kerja || !$hari_kerja->is_active) {
            echo json_encode(array(
                'success' => false,
                'message' => 'Hari ini adalah hari libur'
            ));
            return;
        }

        // Process tap in/out
        if ($siswa) {
            $result = $this->_process_tap('siswa', $siswa->id, $hari_kerja->jam_masuk, $siswa);
        } else {
            $result = $this->_process_tap('guru', $guru->id, $hari_kerja->jam_masuk, $guru);
        }

        echo json_encode($result);
    }

    private function _process_tap($user_type, $user_id, $jam_masuk_scheduled, $user_data) {
        // Check existing attendance today
        $existing = $this->Absensi_model->get_today($user_type, $user_id);

        if (!$existing || empty($existing->jam_masuk)) {
            // Tap in
            $success = $this->Absensi_model->tap_in($user_type, $user_id, $jam_masuk_scheduled);
            
            if ($success) {
                $absen = $this->Absensi_model->get_today($user_type, $user_id);
                
                // Queue WhatsApp notification
                $this->_queue_wa_notification($user_type, $user_data, 'masuk', $absen);
                
                return array(
                    'success' => true,
                    'action' => 'masuk',
                    'user_type' => $user_type,
                    'name' => $user_data->nama,
                    'time' => date('H:i:s'),
                    'status' => $absen->status_masuk,
                    'late_minutes' => $absen->menit_keterlambatan
                );
            }
        } else if (empty($existing->jam_pulang)) {
            // Tap out
            $success = $this->Absensi_model->tap_out($user_type, $user_id);
            
            if ($success) {
                $absen = $this->Absensi_model->get_today($user_type, $user_id);
                
                // Queue WhatsApp notification
                $this->_queue_wa_notification($user_type, $user_data, 'pulang', $absen);
                
                return array(
                    'success' => true,
                    'action' => 'pulang',
                    'user_type' => $user_type,
                    'name' => $user_data->nama,
                    'time' => date('H:i:s')
                );
            }
        } else {
            return array(
                'success' => false,
                'message' => 'Anda sudah melakukan absensi masuk dan pulang hari ini'
            );
        }

        return array(
            'success' => false,
            'message' => 'Gagal memproses absensi'
        );
    }

    private function _queue_wa_notification($user_type, $user_data, $type, $absen) {
        // Only send WA for siswa
        if ($user_type != 'siswa' || empty($user_data->telp_ortu)) {
            return;
        }

        // Get WA template
        $this->load->model('Wa_model');
        $template = $this->Wa_model->get_template($type);
        
        if (!$template) {
            return;
        }

        // Replace placeholders
        $message = $template->template;
        $message = str_replace('{nama_siswa}', $user_data->nama, $message);
        $message = str_replace('{tanggal}', date('d-m-Y'), $message);
        $message = str_replace('{jam}', date('H:i', strtotime($type == 'masuk' ? $absen->jam_masuk : $absen->jam_pulang)), $message);
        
        if ($type == 'masuk') {
            $status_text = $absen->status_masuk == 'tepat_waktu' ? 'Tepat Waktu' : 'Terlambat ' . $absen->menit_keterlambatan . ' menit';
            $message = str_replace('{status}', $status_text, $message);
        }

        // Add to queue
        $this->Wa_model->add_to_queue($user_data->telp_ortu, $message);
    }

    private function _translate_day($day) {
        $days = array(
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu'
        );
        
        return isset($days[$day]) ? $days[$day] : $day;
    }

    public function get_recent() {
        // Get recent attendance (for real-time display)
        $limit = $this->input->get('limit') ? $this->input->get('limit') : 20;
        
        $siswa_absen = $this->Absensi_model->get_recent('siswa', $limit);
        $guru_absen = $this->Absensi_model->get_recent('guru', $limit);
        
        // Fetch names
        foreach ($siswa_absen as $absen) {
            $siswa = $this->Siswa_model->get_by_id($absen->user_id);
            $absen->nama = $siswa ? $siswa->nama : 'Unknown';
            $absen->kelas = $siswa ? $siswa->nama_kelas : '-';
        }
        
        foreach ($guru_absen as $absen) {
            $guru = $this->Guru_model->get_by_id($absen->user_id);
            $absen->nama = $guru ? $guru->nama : 'Unknown';
        }
        
        echo json_encode(array(
            'siswa' => $siswa_absen,
            'guru' => $guru_absen
        ));
    }
}
