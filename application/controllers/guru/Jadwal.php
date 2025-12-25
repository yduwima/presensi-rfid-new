<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jadwal extends CI_Controller {

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
        
        $this->load->model('Jadwal_pelajaran_model');
        $this->load->model('Guru_model');
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
        
        // Get all schedules for this teacher
        $data['jadwal'] = $this->Jadwal_pelajaran_model->get_by_guru($guru->id);
        
        // Group schedules by day
        $data['jadwal_per_hari'] = $this->group_by_day($data['jadwal']);
        
        // Days of week in Indonesian
        $data['hari'] = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        
        $data['title'] = 'Jadwal Mengajar Saya';
        $this->load->view('templates/guru_header', $data);
        $this->load->view('guru/jadwal/index', $data);
        $this->load->view('templates/guru_footer');
    }
    
    private function group_by_day($jadwal)
    {
        $grouped = [
            'Senin' => [],
            'Selasa' => [],
            'Rabu' => [],
            'Kamis' => [],
            'Jumat' => [],
            'Sabtu' => [],
            'Minggu' => []
        ];
        
        foreach ($jadwal as $j) {
            if (isset($grouped[$j->hari])) {
                $grouped[$j->hari][] = $j;
            }
        }
        
        // Sort each day's schedules by time
        foreach ($grouped as $day => &$schedules) {
            usort($schedules, function($a, $b) {
                return strcmp($a->jam_mulai, $b->jam_mulai);
            });
        }
        
        return $grouped;
    }
}
