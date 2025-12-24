<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jadwal_pelajaran extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        if ($this->session->userdata('role') != 'admin') {
            show_error('Akses ditolak', 403, 'Forbidden');
        }
        
        $this->load->model('Jadwal_pelajaran_model');
        $this->load->model('Kelas_model');
        $this->load->model('Mata_pelajaran_model');
        $this->load->model('Guru_model');
    }

    public function index() {
        $data['title'] = 'Jadwal Pelajaran';
        $data['active_menu'] = 'jadwal';
        
        $kelas_filter = $this->input->get('kelas_id');
        $data['jadwal'] = $this->Jadwal_pelajaran_model->get_all($kelas_filter);
        $data['kelas'] = $this->Kelas_model->get_all();
        $data['mata_pelajaran'] = $this->Mata_pelajaran_model->get_all();
        $data['guru'] = $this->Guru_model->get_all();
        $data['kelas_filter'] = $kelas_filter;
        
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/jadwal_pelajaran/index', $data);
        $this->load->view('templates/admin_footer');
    }

    public function create() {
        if ($this->input->post()) {
            $data = array(
                'kelas_id' => $this->input->post('kelas_id'),
                'mata_pelajaran_id' => $this->input->post('mata_pelajaran_id'),
                'guru_id' => $this->input->post('guru_id'),
                'hari' => $this->input->post('hari'),
                'jam_mulai' => $this->input->post('jam_mulai'),
                'jam_selesai' => $this->input->post('jam_selesai')
            );
            
            // Check for scheduling conflicts
            if ($this->Jadwal_pelajaran_model->check_conflict(
                $data['kelas_id'], 
                $data['hari'], 
                $data['jam_mulai'], 
                $data['jam_selesai']
            )) {
                $this->session->set_flashdata('error', 'Jadwal bentrok dengan jadwal lain di kelas yang sama');
                redirect('admin/jadwal_pelajaran');
                return;
            }
            
            if ($this->Jadwal_pelajaran_model->create($data)) {
                $this->session->set_flashdata('success', 'Jadwal pelajaran berhasil ditambahkan');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan jadwal pelajaran');
            }
        }
        
        redirect('admin/jadwal_pelajaran');
    }

    public function update($id) {
        if ($this->input->post()) {
            $data = array(
                'kelas_id' => $this->input->post('kelas_id'),
                'mata_pelajaran_id' => $this->input->post('mata_pelajaran_id'),
                'guru_id' => $this->input->post('guru_id'),
                'hari' => $this->input->post('hari'),
                'jam_mulai' => $this->input->post('jam_mulai'),
                'jam_selesai' => $this->input->post('jam_selesai')
            );
            
            // Check for scheduling conflicts (excluding current schedule)
            if ($this->Jadwal_pelajaran_model->check_conflict(
                $data['kelas_id'], 
                $data['hari'], 
                $data['jam_mulai'], 
                $data['jam_selesai'],
                $id
            )) {
                $this->session->set_flashdata('error', 'Jadwal bentrok dengan jadwal lain di kelas yang sama');
                redirect('admin/jadwal_pelajaran');
                return;
            }
            
            if ($this->Jadwal_pelajaran_model->update($id, $data)) {
                $this->session->set_flashdata('success', 'Jadwal pelajaran berhasil diupdate');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengupdate jadwal pelajaran');
            }
        }
        
        redirect('admin/jadwal_pelajaran');
    }

    public function delete($id) {
        if ($this->Jadwal_pelajaran_model->delete($id)) {
            $this->session->set_flashdata('success', 'Jadwal pelajaran berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus jadwal pelajaran');
        }
        
        redirect('admin/jadwal_pelajaran');
    }
}
