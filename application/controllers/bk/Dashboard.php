<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        
        // Check if user has BK role
        $this->load->model('Guru_model');
        $user_id = $this->session->userdata('user_id');
        $guru = $this->Guru_model->get_by_user_id($user_id);
        
        if (!$guru || !$guru->is_bk) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke panel BK');
            redirect('auth/login');
        }
        
        $this->load->model('Monitoring_bk_model');
        $this->load->model('Surat_bk_model');
    }

    public function index() {
        $data['title'] = 'Dashboard BK';
        
        // Get statistics
        $current_month = date('n');
        $current_year = date('Y');
        
        // Count problem students (alpha >= 3 or late >= 5)
        $alpha_students = $this->Monitoring_bk_model->get_siswa_alpha($current_month, $current_year);
        $late_students = $this->Monitoring_bk_model->get_siswa_terlambat($current_month, $current_year);
        
        // Combine and count unique students
        $problem_students = array_merge($alpha_students, $late_students);
        $unique_ids = array_unique(array_column($problem_students, 'id'));
        
        $data['total_problem_students'] = count($unique_ids);
        $data['total_alpha_students'] = count($alpha_students);
        $data['total_late_students'] = count($late_students);
        
        // Count letters this month
        $data['total_letters_month'] = $this->Surat_bk_model->count_by_month($current_month, $current_year);
        
        // Recent letters (last 5)
        $data['recent_letters'] = $this->Surat_bk_model->get_recent(5);
        
        $this->load->view('templates/bk_header', $data);
        $this->load->view('bk/dashboard', $data);
        $this->load->view('templates/bk_footer');
    }
}
