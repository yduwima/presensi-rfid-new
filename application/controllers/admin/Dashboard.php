<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Check if user is logged in and has admin role
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        if ($this->session->userdata('role') != 'admin') {
            show_error('Akses ditolak. Anda tidak memiliki hak akses.', 403, 'Forbidden');
        }
        
        $this->load->model('Siswa_model');
        $this->load->model('Guru_model');
        $this->load->model('Absensi_model');
    }

    public function index() {
        $data['title'] = 'Dashboard';
        $data['active_menu'] = 'dashboard';
        
        // Get statistics
        $data['total_siswa'] = $this->Siswa_model->count_all();
        $data['total_guru'] = $this->Guru_model->count_all();
        $data['absen_siswa_today'] = $this->Absensi_model->count_today('siswa');
        $data['absen_guru_today'] = $this->Absensi_model->count_today('guru');
        
        // Get recent absences
        $data['recent_absensi_siswa'] = $this->Absensi_model->get_recent('siswa', 10);
        $data['recent_absensi_guru'] = $this->Absensi_model->get_recent('guru', 10);
        
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/dashboard', $data);
        $this->load->view('templates/admin_footer');
    }
}
