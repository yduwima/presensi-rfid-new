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
        $this->load->library('pagination');
    }

    public function index() {
        $data['title'] = 'Jadwal Pelajaran';
        $data['active_menu'] = 'jadwal';
        
        // Get search and pagination parameters
        $search = $this->input->get('search');
        $per_page = $this->input->get('per_page') ? $this->input->get('per_page') : 10;
        $page = $this->input->get('page') ? $this->input->get('page') : 1;
        $offset = ($page - 1) * $per_page;
        
        // Get data with search and pagination
        if ($search) {
            $data['jadwal'] = $this->Jadwal_pelajaran_model->search($search, $per_page, $offset);
            $total_rows = $this->Jadwal_pelajaran_model->count_search($search);
        } else {
            $data['jadwal'] = $this->Jadwal_pelajaran_model->get_all_paginated($per_page, $offset);
            $total_rows = $this->Jadwal_pelajaran_model->count_all();
        }
        
        // Pagination config
        $config['base_url'] = base_url('admin/jadwal_pelajaran');
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $per_page;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['reuse_query_string'] = TRUE;
        $config['num_links'] = 3;
        
        // Tailwind styling
        $config['full_tag_open'] = '<div class="flex justify-center mt-6"><ul class="flex space-x-2">';
        $config['full_tag_close'] = '</ul></div>';
        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = 'Next &raquo;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo; Previous';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li><span class="px-3 py-2 bg-blue-600 text-white rounded">';
        $config['cur_tag_close'] = '</span></li>';
        $config['num_tag_open'] = '<li><a href="#" class="px-3 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300"';
        $config['num_tag_close'] = '</a></li>';
        $config['attributes'] = array('class' => 'px-3 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300');
        
        $this->pagination->initialize($config);
        
        $data['pagination'] = $this->pagination->create_links();
        $data['total_rows'] = $total_rows;
        $data['per_page'] = $per_page;
        $data['page'] = $page;
        $data['offset'] = $offset;
        $data['search'] = $search;
        
        $data['kelas'] = $this->Kelas_model->get_all();
        $data['mata_pelajaran'] = $this->Mata_pelajaran_model->get_all();
        $data['guru'] = $this->Guru_model->get_all();
        
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
