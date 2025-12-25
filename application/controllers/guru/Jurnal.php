<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jurnal extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        if ($this->session->userdata('role') != 'guru') {
            redirect('admin/dashboard');
        }
        
        $this->load->model('Jurnal_model');
        $this->load->model('Jadwal_pelajaran_model');
        $this->load->model('Guru_model');
        $this->load->model('Siswa_model');
        $this->load->model('Absensi_mapel_model');
    }

    public function index() {
        $data['title'] = 'Isi Jurnal';
        $data['active_menu'] = 'jurnal';
        
        $guru = $this->Guru_model->get_by_user_id($this->session->userdata('user_id'));
        $data['guru'] = $guru;
        
        if ($guru) {
            // Get today's schedule that hasn't been filled
            $hari = date('l');
            $hari_indonesia = array(
                'Monday' => 'Senin',
                'Tuesday' => 'Selasa',
                'Wednesday' => 'Rabu',
                'Thursday' => 'Kamis',
                'Friday' => 'Jumat',
                'Saturday' => 'Sabtu',
                'Sunday' => 'Minggu'
            );
            $hari_ini = $hari_indonesia[$hari];
            $tanggal_hari_ini = date('Y-m-d');
            
            $data['jadwal_tersedia'] = $this->Jadwal_pelajaran_model->get_by_day($hari_ini, $guru->id);
            
            // Filter out schedules that already have jurnal for today
            $jadwal_filtered = array();
            foreach ($data['jadwal_tersedia'] as $jadwal) {
                if (!$this->Jurnal_model->check_exists($jadwal->id, $tanggal_hari_ini)) {
                    $jadwal_filtered[] = $jadwal;
                }
            }
            $data['jadwal_tersedia'] = $jadwal_filtered;
        } else {
            $data['jadwal_tersedia'] = array();
        }
        
        $this->load->view('templates/guru_header', $data);
        $this->load->view('guru/jurnal/index', $data);
        $this->load->view('templates/guru_footer');
    }

    public function create($jadwal_id = null) {
        $guru = $this->Guru_model->get_by_user_id($this->session->userdata('user_id'));
        
        if (!$guru) {
            $this->session->set_flashdata('error', 'Data guru tidak ditemukan');
            redirect('guru/jurnal');
        }
        
        if ($this->input->post()) {
            $jadwal_id = $this->input->post('jadwal_id');
            $tanggal = $this->input->post('tanggal');
            
            // Check if jurnal already exists
            if ($this->Jurnal_model->check_exists($jadwal_id, $tanggal)) {
                $this->session->set_flashdata('error', 'Jurnal untuk jadwal dan tanggal ini sudah pernah diisi');
                redirect('guru/jurnal');
                return;
            }
            
            // Create jurnal
            $jurnal_data = array(
                'jadwal_id' => $jadwal_id,
                'guru_id' => $guru->id,
                'tanggal' => $tanggal,
                'materi' => $this->input->post('materi'),
                'catatan' => $this->input->post('keterangan')
            );
            
            $this->db->trans_start();
            
            if ($this->Jurnal_model->create($jurnal_data)) {
                $jurnal_id = $this->db->insert_id();
                
                // Save attendance per student
                $siswa_ids = $this->input->post('siswa_id');
                $statuses = $this->input->post('status');
                
                if ($siswa_ids && $statuses) {
                    $absensi_batch = array();
                    foreach ($siswa_ids as $siswa_id) {
                        // Check if status exists for this student
                        if (isset($statuses[$siswa_id])) {
                            $absensi_batch[] = array(
                                'jurnal_id' => $jurnal_id,
                                'siswa_id' => $siswa_id,
                                'status' => $statuses[$siswa_id]
                            );
                        }
                    }
                    
                    if (!empty($absensi_batch)) {
                        $this->Absensi_mapel_model->create_batch($absensi_batch);
                    }
                }
            }
            
            $this->db->trans_complete();
            
            if ($this->db->trans_status() === FALSE) {
                $this->session->set_flashdata('error', 'Gagal menyimpan jurnal');
            } else {
                $this->session->set_flashdata('success', 'Jurnal berhasil disimpan');
            }
            
            redirect('guru/jurnal');
        }
        
        // Show form
        if (!$jadwal_id) {
            $this->session->set_flashdata('error', 'Jadwal tidak dipilih');
            redirect('guru/jurnal');
        }
        
        $data['title'] = 'Isi Jurnal';
        $data['active_menu'] = 'jurnal';
        $data['guru'] = $guru;
        $data['jadwal'] = $this->Jadwal_pelajaran_model->get_by_id($jadwal_id);
        
        if (!$data['jadwal']) {
            $this->session->set_flashdata('error', 'Jadwal tidak ditemukan');
            redirect('guru/jurnal');
        }
        
        // Get students in this class
        $data['siswa'] = $this->Siswa_model->get_by_kelas($data['jadwal']->kelas_id);
        
        $this->load->view('templates/guru_header', $data);
        $this->load->view('guru/jurnal/create', $data);
        $this->load->view('templates/guru_footer');
    }
}
