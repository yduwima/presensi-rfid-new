<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Naik_kelas extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        
        // Check if user is admin
        if ($this->session->userdata('role') != 'admin') {
            redirect('auth/login');
        }
        
        $this->load->model('Siswa_model');
        $this->load->model('Kelas_model');
        $this->load->model('Tahun_ajaran_model');
    }

    public function index() {
        $data['title'] = 'Naik Kelas';
        $data['active_menu'] = 'naik_kelas';
        
        // Get all tahun ajaran
        $data['tahun_ajaran_list'] = $this->Tahun_ajaran_model->get_all();
        
        // Get active tahun ajaran
        $active_ta = $this->Tahun_ajaran_model->get_active();
        $data['active_tahun_ajaran'] = $active_ta;
        
        // Get all kelas
        $data['kelas_list'] = $this->Kelas_model->get_all();
        
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/naik_kelas/index', $data);
        $this->load->view('templates/admin_footer');
    }

    public function get_siswa_by_kelas() {
        $kelas_id = $this->input->post('kelas_id');
        
        if (!$kelas_id) {
            echo json_encode(['error' => 'Kelas ID tidak valid']);
            return;
        }
        
        $siswa = $this->Siswa_model->get_by_kelas($kelas_id);
        $kelas_info = $this->Kelas_model->get_by_id($kelas_id);
        
        $result = [
            'siswa' => $siswa,
            'kelas_info' => $kelas_info,
            'total' => count($siswa)
        ];
        
        echo json_encode($result);
    }

    public function get_kelas_tujuan() {
        $tingkat_asal = $this->input->post('tingkat_asal');
        $tahun_ajaran_tujuan = $this->input->post('tahun_ajaran_tujuan');
        
        if (!$tingkat_asal || !$tahun_ajaran_tujuan) {
            echo json_encode(['error' => 'Parameter tidak lengkap']);
            return;
        }
        
        // Determine target tingkat
        $tingkat_tujuan = $tingkat_asal + 1;
        
        if ($tingkat_tujuan > 12) {
            // Students graduate
            echo json_encode([
                'graduate' => true,
                'message' => 'Siswa kelas XII akan lulus'
            ]);
            return;
        }
        
        // Get kelas for target tingkat and tahun ajaran
        $this->db->where('tingkat', $tingkat_tujuan);
        $this->db->where('tahun_ajaran_id', $tahun_ajaran_tujuan);
        $this->db->order_by('nama_kelas', 'ASC');
        $kelas_tujuan = $this->db->get('kelas')->result();
        
        echo json_encode([
            'graduate' => false,
            'kelas' => $kelas_tujuan,
            'tingkat_tujuan' => $tingkat_tujuan
        ]);
    }

    public function process_naik_kelas() {
        $kelas_asal_id = $this->input->post('kelas_asal_id');
        $kelas_tujuan_id = $this->input->post('kelas_tujuan_id');
        $tahun_ajaran_tujuan = $this->input->post('tahun_ajaran_tujuan');
        $action = $this->input->post('action'); // 'promote' or 'graduate'
        
        if (!$kelas_asal_id || !$tahun_ajaran_tujuan) {
            $this->session->set_flashdata('error', 'Data tidak lengkap');
            redirect('admin/naik_kelas');
            return;
        }
        
        // Get students from source class
        $siswa_list = $this->Siswa_model->get_by_kelas($kelas_asal_id);
        
        if (empty($siswa_list)) {
            $this->session->set_flashdata('error', 'Tidak ada siswa di kelas yang dipilih');
            redirect('admin/naik_kelas');
            return;
        }
        
        $this->db->trans_start();
        
        $success_count = 0;
        $kelas_info = $this->Kelas_model->get_by_id($kelas_asal_id);
        
        if ($action == 'graduate') {
            // Mark students as graduated (inactive)
            foreach ($siswa_list as $siswa) {
                $update_data = [
                    'is_active' => 0,
                    'keterangan' => 'Lulus Tahun Ajaran ' . date('Y')
                ];
                
                if ($this->Siswa_model->update($siswa->id, $update_data)) {
                    $success_count++;
                }
            }
            
            $message = "Berhasil meluluskan {$success_count} siswa dari kelas {$kelas_info->nama_kelas}";
            
        } else {
            // Promote students to next class
            if (!$kelas_tujuan_id) {
                $this->session->set_flashdata('error', 'Kelas tujuan harus dipilih');
                redirect('admin/naik_kelas');
                return;
            }
            
            $kelas_tujuan_info = $this->Kelas_model->get_by_id($kelas_tujuan_id);
            
            foreach ($siswa_list as $siswa) {
                $update_data = [
                    'kelas_id' => $kelas_tujuan_id
                ];
                
                if ($this->Siswa_model->update($siswa->id, $update_data)) {
                    $success_count++;
                }
            }
            
            $message = "Berhasil menaikkan {$success_count} siswa dari kelas {$kelas_info->nama_kelas} ke {$kelas_tujuan_info->nama_kelas}";
        }
        
        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Terjadi kesalahan saat memproses kenaikan kelas');
        } else {
            $this->session->set_flashdata('success', $message);
        }
        
        redirect('admin/naik_kelas');
    }
}
