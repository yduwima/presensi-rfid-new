<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mata_pelajaran extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        if ($this->session->userdata('role') != 'admin') {
            show_error('Akses ditolak', 403, 'Forbidden');
        }
        
        $this->load->model('Mata_pelajaran_model');
    }

    public function index() {
        $data['title'] = 'Mata Pelajaran';
        $data['active_menu'] = 'mata_pelajaran';
        $data['mata_pelajaran'] = $this->Mata_pelajaran_model->get_all();
        
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/mata_pelajaran/index', $data);
        $this->load->view('templates/admin_footer');
    }

    public function create() {
        if ($this->input->post()) {
            $data = array(
                'kode' => $this->input->post('kode'),
                'nama' => $this->input->post('nama'),
                'deskripsi' => $this->input->post('deskripsi')
            );
            
            if ($this->Mata_pelajaran_model->create($data)) {
                $this->session->set_flashdata('success', 'Mata pelajaran berhasil ditambahkan');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan mata pelajaran');
            }
        }
        
        redirect('admin/mata_pelajaran');
    }

    public function update($id) {
        if ($this->input->post()) {
            $data = array(
                'kode' => $this->input->post('kode'),
                'nama' => $this->input->post('nama'),
                'deskripsi' => $this->input->post('deskripsi')
            );
            
            if ($this->Mata_pelajaran_model->update($id, $data)) {
                $this->session->set_flashdata('success', 'Mata pelajaran berhasil diupdate');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengupdate mata pelajaran');
            }
        }
        
        redirect('admin/mata_pelajaran');
    }

    public function delete($id) {
        if ($this->Mata_pelajaran_model->delete($id)) {
            $this->session->set_flashdata('success', 'Mata pelajaran berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus mata pelajaran');
        }
        
        redirect('admin/mata_pelajaran');
    }
}
