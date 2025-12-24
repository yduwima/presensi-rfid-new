<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitoring extends CI_Controller {

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
        $this->load->model('Siswa_model');
        $this->load->model('Absensi_model');
    }

    public function index() {
        $data['title'] = 'Monitoring Siswa Bermasalah';
        
        $current_month = date('n');
        $current_year = date('Y');
        
        // Get filter
        $filter = $this->input->get('filter') ? $this->input->get('filter') : 'all';
        
        if ($filter == 'alpha') {
            $data['students'] = $this->Monitoring_bk_model->get_siswa_alpha($current_month, $current_year);
        } else if ($filter == 'terlambat') {
            $data['students'] = $this->Monitoring_bk_model->get_siswa_terlambat($current_month, $current_year);
        } else {
            // Get both
            $alpha_students = $this->Monitoring_bk_model->get_siswa_alpha($current_month, $current_year);
            $late_students = $this->Monitoring_bk_model->get_siswa_terlambat($current_month, $current_year);
            
            // Merge and mark problem types
            foreach ($alpha_students as &$student) {
                $student->problem_type = 'alpha';
                $student->problem_count = $student->jumlah_alpha;
            }
            foreach ($late_students as &$student) {
                $student->problem_type = 'terlambat';
                $student->problem_count = $student->jumlah_terlambat;
            }
            
            $data['students'] = array_merge($alpha_students, $late_students);
        }
        
        $data['filter'] = $filter;
        $data['current_month'] = $current_month;
        $data['current_year'] = $current_year;
        
        $this->load->view('templates/bk_header', $data);
        $this->load->view('bk/monitoring/index', $data);
        $this->load->view('templates/bk_footer');
    }

    public function detail($siswa_id) {
        $data['title'] = 'Detail Monitoring Siswa';
        
        // Get student data
        $data['siswa'] = $this->Siswa_model->get_by_id($siswa_id);
        
        if (!$data['siswa']) {
            $this->session->set_flashdata('error', 'Siswa tidak ditemukan');
            redirect('bk/monitoring');
        }
        
        $current_month = date('n');
        $current_year = date('Y');
        
        // Get attendance details
        $data['alpha_records'] = $this->Monitoring_bk_model->get_detail_alpha($siswa_id, $current_month, $current_year);
        $data['late_records'] = $this->Monitoring_bk_model->get_detail_terlambat($siswa_id, $current_month, $current_year);
        
        $data['total_alpha'] = count($data['alpha_records']);
        $data['total_late'] = count($data['late_records']);
        
        $this->load->view('templates/bk_header', $data);
        $this->load->view('bk/monitoring/detail', $data);
        $this->load->view('templates/bk_footer');
    }
}
