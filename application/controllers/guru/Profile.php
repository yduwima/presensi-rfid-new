<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        
        // Check if user has guru role
        if ($this->session->userdata('role') !== 'guru') {
            redirect('auth/login');
        }
        
        $this->load->library('form_validation');
        $this->load->model('Guru_model');
        $this->load->model('User_model');
    }

    public function index()
    {
        // Get guru data by user_id
        $user_id = $this->session->userdata('user_id');
        $data['guru'] = $this->Guru_model->get_by_user_id($user_id);
        
        if (!$data['guru']) {
            $this->session->set_flashdata('error', 'Data guru tidak ditemukan');
            redirect('guru/dashboard');
        }
        
        $data['user'] = $this->User_model->get_by_id($user_id);
        
        $data['title'] = 'Profile Saya';
        $this->load->view('templates/guru_header', $data);
        $this->load->view('guru/profile/index', $data);
        $this->load->view('templates/guru_footer');
    }
    
    public function update()
    {
        $user_id = $this->session->userdata('user_id');
        $guru = $this->Guru_model->get_by_user_id($user_id);
        
        if (!$guru) {
            $this->session->set_flashdata('error', 'Data guru tidak ditemukan');
            redirect('guru/dashboard');
        }
        
        // Validation rules
        $this->form_validation->set_rules('nama', 'Nama', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('telp', 'No. Telepon', 'required');
        
        if ($this->form_validation->run() === FALSE) {
            $this->index();
        } else {
            $data_guru = array(
                'nama' => $this->input->post('nama'),
                'email' => $this->input->post('email'),
                'telp' => $this->input->post('telp'),
                'alamat' => $this->input->post('alamat')
            );
            
            if ($this->Guru_model->update($guru->id, $data_guru)) {
                $this->session->set_flashdata('success', 'Profile berhasil diperbarui');
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui profile');
            }
            
            redirect('guru/profile');
        }
    }
    
    public function change_password()
    {
        $user_id = $this->session->userdata('user_id');
        
        // Validation rules
        $this->form_validation->set_rules('password_lama', 'Password Lama', 'required');
        $this->form_validation->set_rules('password_baru', 'Password Baru', 'required|min_length[6]');
        $this->form_validation->set_rules('password_konfirmasi', 'Konfirmasi Password', 'required|matches[password_baru]');
        
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('guru/profile');
        } else {
            $user = $this->User_model->get_by_id($user_id);
            
            // Verify old password
            if (!password_verify($this->input->post('password_lama'), $user->password)) {
                $this->session->set_flashdata('error', 'Password lama tidak sesuai');
                redirect('guru/profile');
                return;
            }
            
            // Update password
            $data = array(
                'password' => password_hash($this->input->post('password_baru'), PASSWORD_BCRYPT)
            );
            
            if ($this->User_model->update($user_id, $data)) {
                $this->session->set_flashdata('success', 'Password berhasil diubah');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengubah password');
            }
            
            redirect('guru/profile');
        }
    }
}
