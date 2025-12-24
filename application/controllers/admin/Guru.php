<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Guru extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        if ($this->session->userdata('role') != 'admin') {
            show_error('Akses ditolak', 403, 'Forbidden');
        }
        
        $this->load->model('Guru_model');
        $this->load->library('pagination');
    }

    public function index() {
        $data['title'] = 'Data Guru';
        $data['active_menu'] = 'guru';
        
        // Pagination configuration
        $config['base_url'] = base_url('admin/guru/index');
        $config['total_rows'] = $this->Guru_model->count_all();
        $config['per_page'] = $this->input->get('per_page') ? $this->input->get('per_page') : 10;
        $config['uri_segment'] = 3;
        $config['use_page_numbers'] = TRUE;
        $config['reuse_query_string'] = TRUE;
        
        // Bootstrap pagination styling
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
        $search = $this->input->get('search');
        if ($search) {
            $data['guru'] = $this->Guru_model->search($search, $config['per_page'], $offset);
        } else {
            $data['guru'] = $this->Guru_model->get_all($config['per_page'], $offset);
        }
        
        $data['pagination'] = $this->pagination->create_links();
        $data['per_page'] = $config['per_page'];
        $data['search'] = $search;
        
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/guru/index', $data);
        $this->load->view('templates/admin_footer');
    }

    public function create() {
        if ($this->input->post()) {
            $data = array(
                'nip' => $this->input->post('nip'),
                'nama' => $this->input->post('nama'),
                'email' => $this->input->post('email'),
                'telp' => $this->input->post('telp'),
                'alamat' => $this->input->post('alamat'),
                'rfid_uid' => $this->input->post('rfid_uid'),
                'is_wali_kelas' => $this->input->post('is_wali_kelas') ? 1 : 0,
                'is_piket' => $this->input->post('is_piket') ? 1 : 0,
                'is_bk' => $this->input->post('is_bk') ? 1 : 0
            );
            
            if ($this->Guru_model->create($data)) {
                $this->session->set_flashdata('success', 'Data guru berhasil ditambahkan');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan data guru');
            }
        }
        
        redirect('admin/guru');
    }

    public function update($id) {
        if ($this->input->post()) {
            $data = array(
                'nip' => $this->input->post('nip'),
                'nama' => $this->input->post('nama'),
                'email' => $this->input->post('email'),
                'telp' => $this->input->post('telp'),
                'alamat' => $this->input->post('alamat'),
                'rfid_uid' => $this->input->post('rfid_uid'),
                'is_wali_kelas' => $this->input->post('is_wali_kelas') ? 1 : 0,
                'is_piket' => $this->input->post('is_piket') ? 1 : 0,
                'is_bk' => $this->input->post('is_bk') ? 1 : 0
            );
            
            if ($this->Guru_model->update($id, $data)) {
                $this->session->set_flashdata('success', 'Data guru berhasil diupdate');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengupdate data guru');
            }
        }
        
        redirect('admin/guru');
    }

    public function delete($id) {
        if ($this->Guru_model->delete($id)) {
            $this->session->set_flashdata('success', 'Data guru berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data guru');
        }
        
        redirect('admin/guru');
    }
}
