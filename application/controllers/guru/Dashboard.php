<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        if ($this->session->userdata('role') != 'guru') {
            redirect('admin/dashboard');
        }
        
        $this->load->model('Jadwal_pelajaran_model');
        $this->load->model('Guru_model');
    }

    public function index() {
        $data['title'] = 'Dashboard Guru';
        $data['active_menu'] = 'dashboard';
        
        // Get guru data
        $guru = $this->Guru_model->get_by_user_id($this->session->userdata('user_id'));
        $data['guru'] = $guru;
        
        if ($guru) {
            // Get today's schedule
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
            
            $data['jadwal_hari_ini'] = $this->Jadwal_pelajaran_model->get_by_day($hari_ini, $guru->id);
            $data['hari_ini'] = $hari_ini;
        } else {
            $data['jadwal_hari_ini'] = array();
            $data['hari_ini'] = '';
        }
        
        $this->load->view('templates/guru_header', $data);
        $this->load->view('guru/dashboard', $data);
        $this->load->view('templates/guru_footer');
    }
}
