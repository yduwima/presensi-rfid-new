<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Semester extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        if ($this->session->userdata('role') != 'admin') {
            show_error('Akses ditolak', 403, 'Forbidden');
        }
        
        $this->load->model('Semester_model');
        $this->load->model('Tahun_ajaran_model');
    }

    public function index() {
        $data['title'] = 'Semester';
        $data['active_menu'] = 'semester';
        $data['semester'] = $this->Semester_model->get_all();
        $data['tahun_ajaran'] = $this->Tahun_ajaran_model->get_all();
        
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/semester/index', $data);
        $this->load->view('templates/admin_footer');
    }

    public function create() {
        if ($this->input->post()) {
            $data = array(
                'nama' => $this->input->post('nama'),
                'tahun_ajaran_id' => $this->input->post('tahun_ajaran_id'),
                'is_active' => $this->input->post('is_active') ? 1 : 0
            );
            
            if ($this->Semester_model->create($data)) {
                $this->session->set_flashdata('success', 'Semester berhasil ditambahkan');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan semester');
            }
        }
        
        redirect('admin/semester');
    }

    public function update($id) {
        if ($this->input->post()) {
            $data = array(
                'nama' => $this->input->post('nama'),
                'tahun_ajaran_id' => $this->input->post('tahun_ajaran_id'),
                'is_active' => $this->input->post('is_active') ? 1 : 0
            );
            
            if ($this->Semester_model->update($id, $data)) {
                $this->session->set_flashdata('success', 'Semester berhasil diupdate');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengupdate semester');
            }
        }
        
        redirect('admin/semester');
    }

    public function delete($id) {
        if ($this->Semester_model->delete($id)) {
            $this->session->set_flashdata('success', 'Semester berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus semester');
        }
        
        redirect('admin/semester');
    }

    public function set_active($id) {
        // Deactivate all
        $this->Semester_model->deactivate_all();
        
        // Activate selected
        if ($this->Semester_model->update($id, array('is_active' => 1))) {
            $this->session->set_flashdata('success', 'Semester aktif berhasil diubah');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengubah semester aktif');
        }
        
        redirect('admin/semester');
    }
}
