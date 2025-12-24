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
        $this->load->model('Jurnal_model');
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
            
            // Calculate statistics
            // 1. Total schedule count for this teacher
            $data['total_jadwal'] = $this->Jadwal_pelajaran_model->count_by_guru($guru->id);
            
            // 2. Journal entries this month
            $bulan = date('m');
            $tahun = date('Y');
            $jurnal_bulan_ini = $this->Jurnal_model->get_by_guru_periode($guru->id, $bulan, $tahun);
            $data['jurnal_bulan_ini'] = count($jurnal_bulan_ini);
            
            // 3. Count unique classes taught (from jadwal)
            $all_jadwal = $this->Jadwal_pelajaran_model->get_by_guru($guru->id);
            $kelas_unique = array();
            foreach ($all_jadwal as $jadwal) {
                if (isset($jadwal->kelas_id) && !in_array($jadwal->kelas_id, $kelas_unique)) {
                    $kelas_unique[] = $jadwal->kelas_id;
                }
            }
            $data['kelas_diampu'] = count($kelas_unique);
        } else {
            $data['jadwal_hari_ini'] = array();
            $data['hari_ini'] = '';
            $data['total_jadwal'] = 0;
            $data['jurnal_bulan_ini'] = 0;
            $data['kelas_diampu'] = 0;
        }
        
        $this->load->view('templates/guru_header', $data);
        $this->load->view('guru/dashboard', $data);
        $this->load->view('templates/guru_footer');
    }
}
