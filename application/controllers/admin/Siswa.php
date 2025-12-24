<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Siswa extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        if ($this->session->userdata('role') != 'admin') {
            show_error('Akses ditolak', 403, 'Forbidden');
        }
        
        $this->load->model('Siswa_model');
        $this->load->model('Kelas_model');
        $this->load->library('pagination');
    }

    public function index() {
        $data['title'] = 'Data Siswa';
        $data['active_menu'] = 'siswa';
        
        // Pagination configuration
        $config['base_url'] = base_url('admin/siswa/index');
        $config['total_rows'] = $this->Siswa_model->count_all();
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
            $data['siswa'] = $this->Siswa_model->search($search, $config['per_page'], $offset);
        } else {
            $data['siswa'] = $this->Siswa_model->get_all($config['per_page'], $offset);
        }
        
        $data['kelas'] = $this->Kelas_model->get_all();
        $data['pagination'] = $this->pagination->create_links();
        $data['per_page'] = $config['per_page'];
        $data['search'] = $search;
        
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/siswa/index', $data);
        $this->load->view('templates/admin_footer');
    }

    public function create() {
        if ($this->input->post()) {
            $data = array(
                'nis' => $this->input->post('nis'),
                'nisn' => $this->input->post('nisn'),
                'nama' => $this->input->post('nama'),
                'kelas_id' => $this->input->post('kelas_id'),
                'jenis_kelamin' => $this->input->post('jenis_kelamin'),
                'tempat_lahir' => $this->input->post('tempat_lahir'),
                'tanggal_lahir' => $this->input->post('tanggal_lahir'),
                'alamat' => $this->input->post('alamat'),
                'nama_ortu' => $this->input->post('nama_ortu'),
                'telp_ortu' => $this->input->post('telp_ortu'),
                'rfid_uid' => $this->input->post('rfid_uid'),
                'is_active' => 1
            );
            
            if ($this->Siswa_model->create($data)) {
                $this->session->set_flashdata('success', 'Data siswa berhasil ditambahkan');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan data siswa');
            }
        }
        
        redirect('admin/siswa');
    }

    public function update($id) {
        if ($this->input->post()) {
            $data = array(
                'nis' => $this->input->post('nis'),
                'nisn' => $this->input->post('nisn'),
                'nama' => $this->input->post('nama'),
                'kelas_id' => $this->input->post('kelas_id'),
                'jenis_kelamin' => $this->input->post('jenis_kelamin'),
                'tempat_lahir' => $this->input->post('tempat_lahir'),
                'tanggal_lahir' => $this->input->post('tanggal_lahir'),
                'alamat' => $this->input->post('alamat'),
                'nama_ortu' => $this->input->post('nama_ortu'),
                'telp_ortu' => $this->input->post('telp_ortu'),
                'rfid_uid' => $this->input->post('rfid_uid')
            );
            
            if ($this->Siswa_model->update($id, $data)) {
                $this->session->set_flashdata('success', 'Data siswa berhasil diupdate');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengupdate data siswa');
            }
        }
        
        redirect('admin/siswa');
    }

    public function delete($id) {
        if ($this->Siswa_model->delete($id)) {
            $this->session->set_flashdata('success', 'Data siswa berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data siswa');
        }
        
        redirect('admin/siswa');
    }
    
    public function import() {
        $data['title'] = 'Import Data Siswa';
        $data['active_menu'] = 'siswa';
        
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/siswa/import', $data);
        $this->load->view('templates/admin_footer');
    }
    
    public function download_template() {
        $this->load->library('Excel_import');
        $this->excel_import->generate_siswa_template();
    }
    
    public function process_import() {
        if (!$this->input->post()) {
            redirect('admin/siswa/import');
        }
        
        // Handle file upload
        $config['upload_path'] = './assets/uploads/temp/';
        $config['allowed_types'] = 'xlsx|xls|csv';
        $config['max_size'] = 5120; // 5MB
        $config['file_name'] = 'import_siswa_' . time();
        
        // Create temp directory if not exists
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, true);
        }
        
        $this->load->library('upload', $config);
        
        if (!$this->upload->do_upload('file')) {
            $this->session->set_flashdata('error', $this->upload->display_errors());
            redirect('admin/siswa/import');
        }
        
        $file_data = $this->upload->data();
        $file_path = $file_data['full_path'];
        
        // Read and validate file
        $this->load->library('Excel_import');
        $read_result = $this->excel_import->read_file($file_path);
        
        if (!$read_result['success']) {
            unlink($file_path);
            $this->session->set_flashdata('error', $read_result['message']);
            redirect('admin/siswa/import');
        }
        
        $validation = $this->excel_import->validate_siswa($read_result['data']);
        
        // Import valid rows
        $success_count = 0;
        $error_count = 0;
        
        foreach ($validation['valid'] as $row) {
            $data = array(
                'nis' => $row['NIS'],
                'nisn' => isset($row['NISN']) ? $row['NISN'] : null,
                'nama' => $row['Nama'],
                'kelas_id' => isset($row['Kelas ID']) ? $row['Kelas ID'] : null,
                'jenis_kelamin' => isset($row['Jenis Kelamin']) ? $row['Jenis Kelamin'] : 'L',
                'tempat_lahir' => isset($row['Tempat Lahir']) ? $row['Tempat Lahir'] : null,
                'tanggal_lahir' => isset($row['Tanggal Lahir']) ? $row['Tanggal Lahir'] : null,
                'alamat' => isset($row['Alamat']) ? $row['Alamat'] : null,
                'telp_siswa' => isset($row['No HP Siswa']) ? $row['No HP Siswa'] : null,
                'nama_ortu' => isset($row['Nama Orang Tua']) ? $row['Nama Orang Tua'] : null,
                'telp_ortu' => isset($row['No HP Orang Tua']) ? $row['No HP Orang Tua'] : null,
                'rfid_uid' => isset($row['RFID UID']) ? $row['RFID UID'] : null,
                'is_active' => 1
            );
            
            if ($this->Siswa_model->create($data)) {
                $success_count++;
            } else {
                $error_count++;
            }
        }
        
        // Delete uploaded file
        unlink($file_path);
        
        // Set flash message
        $message = "Import selesai. Berhasil: {$success_count}, Gagal: {$error_count}";
        if ($validation['error_count'] > 0) {
            $message .= ", Error validasi: {$validation['error_count']}";
        }
        
        $this->session->set_flashdata('success', $message);
        redirect('admin/siswa');
    }
}
