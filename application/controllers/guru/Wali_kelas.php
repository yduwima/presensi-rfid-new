<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wali_kelas extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        
        // Check if user is guru
        if ($this->session->userdata('role') != 'guru') {
            redirect('auth/login');
        }
        
        $this->load->helper('text');
        $this->load->library('form_validation');
        $this->load->model('Guru_model');
        $this->load->model('Siswa_model');
        $this->load->model('Izin_siswa_model');
        $this->load->model('Kelas_model');
    }

    public function index()
    {
        // Get guru data
        $user_id = $this->session->userdata('user_id');
        $guru = $this->Guru_model->get_by_user_id($user_id);
        
        if (!$guru || !$guru->is_wali_kelas) {
            show_error('Anda tidak memiliki akses sebagai Wali Kelas', 403);
        }
        
        // Get kelas where this guru is wali kelas
        $kelas = $this->db->get_where('kelas', ['wali_kelas_id' => $guru->id])->row();
        
        if (!$kelas) {
            show_error('Kelas tidak ditemukan', 404);
        }
        
        // Get students in this class
        $data['siswa'] = $this->Siswa_model->get_by_kelas($kelas->id);
        
        // Get recent izin records for this class
        $data['izin_recent'] = $this->Izin_siswa_model->get_by_kelas($kelas->id, 30);
        
        $data['kelas'] = $kelas;
        $data['guru'] = $guru;
        $data['title'] = 'Wali Kelas - Input Izin Siswa';
        
        $this->load->view('templates/guru_header', $data);
        $this->load->view('guru/wali_kelas/index', $data);
        $this->load->view('templates/guru_footer');
    }
    
    public function create()
    {
        // Get guru data
        $user_id = $this->session->userdata('user_id');
        $guru = $this->Guru_model->get_by_user_id($user_id);
        
        if (!$guru || !$guru->is_wali_kelas) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses sebagai Wali Kelas');
            redirect('guru/dashboard');
        }
        
        $this->form_validation->set_rules('siswa_id', 'Siswa', 'required');
        $this->form_validation->set_rules('jenis', 'Jenis Izin', 'required|in_list[sakit,izin]');
        $this->form_validation->set_rules('tanggal_mulai', 'Tanggal Mulai', 'required');
        $this->form_validation->set_rules('tanggal_selesai', 'Tanggal Selesai', 'required');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'required');
        
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('guru/wali_kelas');
        }
        
        // Verify siswa is in guru's class
        $siswa = $this->Siswa_model->get_by_id($this->input->post('siswa_id'));
        $kelas = $this->db->get_where('kelas', ['wali_kelas_id' => $guru->id])->row();
        
        if (!$siswa || $siswa->kelas_id != $kelas->id) {
            $this->session->set_flashdata('error', 'Siswa tidak valid atau bukan dari kelas Anda');
            redirect('guru/wali_kelas');
        }
        
        $data = [
            'siswa_id' => $this->input->post('siswa_id'),
            'guru_id' => $guru->id,
            'jenis' => $this->input->post('jenis'),
            'tanggal_mulai' => $this->input->post('tanggal_mulai'),
            'tanggal_selesai' => $this->input->post('tanggal_selesai'),
            'keterangan' => $this->input->post('keterangan'),
            'surat' => ''
        ];
        
        // Handle file upload if any
        if (!empty($_FILES['bukti_file']['name'])) {
            $config['upload_path'] = './assets/uploads/izin/';
            $config['allowed_types'] = 'jpg|jpeg|png|pdf';
            $config['max_size'] = 2048; // 2MB
            $config['file_name'] = 'izin_' . time() . '_' . $siswa->nis;
            
            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, true);
            }
            
            $this->load->library('upload', $config);
            
            if ($this->upload->do_upload('bukti_file')) {
                $upload_data = $this->upload->data();
                $data['surat'] = $upload_data['file_name'];
            }
        }
        
        if ($this->Izin_siswa_model->create($data)) {
            $this->session->set_flashdata('success', 'Izin siswa berhasil ditambahkan');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan izin siswa');
        }
        
        redirect('guru/wali_kelas');
    }
    
    public function delete($id)
    {
        // Get guru data
        $user_id = $this->session->userdata('user_id');
        $guru = $this->Guru_model->get_by_user_id($user_id);
        
        if (!$guru || !$guru->is_wali_kelas) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses');
            redirect('guru/dashboard');
        }
        
        // Verify izin belongs to guru's students
        $izin = $this->Izin_siswa_model->get_by_id($id);
        if (!$izin || $izin->guru_id != $guru->id) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan');
            redirect('guru/wali_kelas');
        }
        
        if ($this->Izin_siswa_model->delete($id)) {
            $this->session->set_flashdata('success', 'Izin siswa berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus izin siswa');
        }
        
        redirect('guru/wali_kelas');
    }
}
