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
        $this->load->library('pagination');
    }

    public function index() {
        $data['title'] = 'Kelas';
        $data['active_menu'] = 'kelas';
        
        // Pagination configuration
        $config['base_url'] = base_url('admin/kelas/index');
        $search = $this->input->get('search');
        $config['total_rows'] = $search ? $this->Kelas_model->count_search($search) : $this->Kelas_model->count_all();
        $config['per_page'] = $this->input->get('per_page') ? $this->input->get('per_page') : 10;
        $config['uri_segment'] = 3;
        $config['use_page_numbers'] = TRUE;
        $config['reuse_query_string'] = TRUE;
        
        // Pagination styling
        $config['full_tag_open'] = '<nav><ul class="flex space-x-1">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = '&raquo;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo;';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="px-3 py-1 bg-blue-600 text-white rounded">';
        $config['cur_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded cursor-pointer">';
        $config['num_tag_close'] = '</li>';
        
        $this->pagination->initialize($config);
        
        $page = ($this->input->get('page')) ? $this->input->get('page') : 1;
        $offset = ($page - 1) * $config['per_page'];
        
        // Search
        if ($search) {
            $data['kelas'] = $this->Kelas_model->search($search, $config['per_page'], $offset);
        } else {
            $data['kelas'] = $this->Kelas_model->get_all($config['per_page'], $offset);
        }
        
        $data['tahun_ajaran'] = $this->Tahun_ajaran_model->get_all();
        $data['guru'] = $this->Guru_model->get_all();
        $data['pagination'] = $this->pagination->create_links();
        $data['per_page'] = $config['per_page'];
        $data['search'] = $search;
        
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
