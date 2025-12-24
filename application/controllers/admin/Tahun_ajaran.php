<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tahun_ajaran extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        if ($this->session->userdata('role') != 'admin') {
            show_error('Akses ditolak', 403, 'Forbidden');
        }
        
        $this->load->model('Tahun_ajaran_model');
    }

    public function index() {
        $data['title'] = 'Tahun Ajaran';
        $data['active_menu'] = 'tahun_ajaran';
        $data['tahun_ajaran'] = $this->Tahun_ajaran_model->get_all();
        
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/tahun_ajaran/index', $data);
        $this->load->view('templates/admin_footer');
    }

    public function create() {
        if ($this->input->post()) {
            $data = array(
                'tahun' => $this->input->post('tahun'),
                'is_active' => $this->input->post('is_active') ? 1 : 0
            );
            
            if ($this->Tahun_ajaran_model->create($data)) {
                $this->session->set_flashdata('success', 'Tahun ajaran berhasil ditambahkan');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan tahun ajaran');
            }
        }
        
        redirect('admin/tahun_ajaran');
    }

    public function update($id) {
        if ($this->input->post()) {
            $data = array(
                'tahun' => $this->input->post('tahun'),
                'is_active' => $this->input->post('is_active') ? 1 : 0
            );
            
            if ($this->Tahun_ajaran_model->update($id, $data)) {
                $this->session->set_flashdata('success', 'Tahun ajaran berhasil diupdate');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengupdate tahun ajaran');
            }
        }
        
        redirect('admin/tahun_ajaran');
    }

    public function delete($id) {
        if ($this->Tahun_ajaran_model->delete($id)) {
            $this->session->set_flashdata('success', 'Tahun ajaran berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus tahun ajaran');
        }
        
        redirect('admin/tahun_ajaran');
    }

    public function set_active($id) {
        // Deactivate all
        $this->Tahun_ajaran_model->deactivate_all();
        
        // Activate selected
        if ($this->Tahun_ajaran_model->update($id, array('is_active' => 1))) {
            $this->session->set_flashdata('success', 'Tahun ajaran aktif berhasil diubah');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengubah tahun ajaran aktif');
        }
        
        redirect('admin/tahun_ajaran');
    }
}
