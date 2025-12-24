<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hari_kerja extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        if ($this->session->userdata('role') != 'admin') {
            show_error('Akses ditolak', 403, 'Forbidden');
        }
        
        $this->load->model('Hari_kerja_model');
    }

    public function index() {
        $data['title'] = 'Pengaturan Hari Kerja';
        $data['active_menu'] = 'hari_kerja';
        $data['hari_kerja'] = $this->Hari_kerja_model->get_all();
        
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/hari_kerja/index', $data);
        $this->load->view('templates/admin_footer');
    }

    public function update_all() {
        if ($this->input->post()) {
            $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
            
            foreach ($days as $day) {
                $data = array(
                    'jam_masuk' => $this->input->post('jam_masuk_' . $day),
                    'jam_pulang' => $this->input->post('jam_pulang_' . $day),
                    'is_active' => $this->input->post('is_active_' . $day) ? 1 : 0
                );
                
                $this->Hari_kerja_model->update_by_hari($day, $data);
            }
            
            $this->session->set_flashdata('success', 'Pengaturan hari kerja berhasil diupdate');
        }
        
        redirect('admin/hari_kerja');
    }
}
