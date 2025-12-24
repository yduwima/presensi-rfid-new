<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kelas extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        if ($this->session->userdata('role') != 'admin') {
            show_error('Akses ditolak', 403, 'Forbidden');
        }
        
        $this->load->model('Kelas_model');
        $this->load->model('Tahun_ajaran_model');
        $this->load->model('Guru_model');
    }

    public function index() {
        $data['title'] = 'Kelas';
        $data['active_menu'] = 'kelas';
        $data['kelas'] = $this->Kelas_model->get_all();
        $data['tahun_ajaran'] = $this->Tahun_ajaran_model->get_all();
        $data['guru'] = $this->Guru_model->get_all();
        
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/kelas/index', $data);
        $this->load->view('templates/admin_footer');
    }

    public function create() {
        if ($this->input->post()) {
            $data = array(
                'nama_kelas' => $this->input->post('nama_kelas'),
                'tingkat' => $this->input->post('tingkat'),
                'tahun_ajaran_id' => $this->input->post('tahun_ajaran_id'),
                'wali_kelas_id' => $this->input->post('wali_kelas_id') ? $this->input->post('wali_kelas_id') : null
            );
            
            if ($this->Kelas_model->create($data)) {
                $this->session->set_flashdata('success', 'Kelas berhasil ditambahkan');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan kelas');
            }
        }
        
        redirect('admin/kelas');
    }

    public function update($id) {
        if ($this->input->post()) {
            $data = array(
                'nama_kelas' => $this->input->post('nama_kelas'),
                'tingkat' => $this->input->post('tingkat'),
                'tahun_ajaran_id' => $this->input->post('tahun_ajaran_id'),
                'wali_kelas_id' => $this->input->post('wali_kelas_id') ? $this->input->post('wali_kelas_id') : null
            );
            
            if ($this->Kelas_model->update($id, $data)) {
                $this->session->set_flashdata('success', 'Kelas berhasil diupdate');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengupdate kelas');
            }
        }
        
        redirect('admin/kelas');
    }

    public function delete($id) {
        if ($this->Kelas_model->delete($id)) {
            $this->session->set_flashdata('success', 'Kelas berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus kelas');
        }
        
        redirect('admin/kelas');
    }
}
