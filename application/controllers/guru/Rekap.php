<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekap extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        
        // Check if user has guru role
        if ($this->session->userdata('role') !== 'guru') {
            redirect('auth/login');
        }
        
        $this->load->model('Jurnal_model');
        $this->load->model('Guru_model');
        $this->load->model('Jadwal_pelajaran_model');
        $this->load->model('Absensi_mapel_model');
    }

    public function index()
    {
        // Get guru data by user_id
        $user_id = $this->session->userdata('user_id');
        $guru = $this->Guru_model->get_by_user_id($user_id);
        
        if (!$guru) {
            $this->session->set_flashdata('error', 'Data guru tidak ditemukan');
            redirect('guru/dashboard');
        }
        
        // Get filter parameters
        $bulan = $this->input->get('bulan') ?: date('m');
        $tahun = $this->input->get('tahun') ?: date('Y');
        
        // Get all journals for this teacher
        $data['jurnal'] = $this->Jurnal_model->get_by_guru_periode($guru->id, $bulan, $tahun);
        
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;
        $data['guru'] = $guru;
        
        // Calculate stats
        $data['total_jurnal'] = count($data['jurnal']);
        $data['total_pertemuan'] = $this->Jadwal_pelajaran_model->count_by_guru($guru->id) * 4; // Assume 4 weeks per month
        
        $data['title'] = 'Rekap Jurnal Mengajar';
        $this->load->view('templates/guru_header', $data);
        $this->load->view('guru/rekap/index', $data);
        $this->load->view('templates/guru_footer');
    }
    
    public function detail($jurnal_id)
    {
        // Get guru data by user_id
        $user_id = $this->session->userdata('user_id');
        $guru = $this->Guru_model->get_by_user_id($user_id);
        
        if (!$guru) {
            $this->session->set_flashdata('error', 'Data guru tidak ditemukan');
            redirect('guru/dashboard');
        }
        
        // Get journal detail
        $data['jurnal'] = $this->Jurnal_model->get_by_id($jurnal_id);
        
        if (!$data['jurnal'] || $data['jurnal']->guru_id != $guru->id) {
            $this->session->set_flashdata('error', 'Jurnal tidak ditemukan');
            redirect('guru/rekap');
        }
        
        // Get attendance records for this journal
        $data['absensi'] = $this->Absensi_mapel_model->get_by_jurnal($jurnal_id);
        
        // Calculate attendance stats
        $stats = ['H' => 0, 'S' => 0, 'I' => 0, 'A' => 0];
        foreach ($data['absensi'] as $abs) {
            if (isset($stats[$abs->status])) {
                $stats[$abs->status]++;
            }
        }
        $data['stats'] = $stats;
        
        $data['title'] = 'Detail Jurnal';
        $this->load->view('templates/guru_header', $data);
        $this->load->view('guru/rekap/detail', $data);
        $this->load->view('templates/guru_footer');
    }
}
