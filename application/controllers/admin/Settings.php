<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        if ($this->session->userdata('role') != 'admin') {
            show_error('Akses ditolak', 403, 'Forbidden');
        }
        
        $this->load->model('Settings_model');
    }

    public function index() {
        $data['title'] = 'Pengaturan Sekolah';
        $data['active_menu'] = 'settings';
        $data['settings'] = $this->Settings_model->get();
        
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/settings/index', $data);
        $this->load->view('templates/admin_footer');
    }

    public function update() {
        if ($this->input->post()) {
            $settings = $this->Settings_model->get();
            
            $data = array(
                'nama_sekolah' => $this->input->post('nama_sekolah'),
                'alamat_sekolah' => $this->input->post('alamat_sekolah'),
                'nama_kepala_sekolah' => $this->input->post('nama_kepala_sekolah'),
                'website' => $this->input->post('website'),
                'no_telephone' => $this->input->post('no_telephone'),
                'email' => $this->input->post('email')
            );
            
            // Handle logo upload
            if (!empty($_FILES['logo']['name'])) {
                $config['upload_path'] = './assets/uploads/';
                $config['allowed_types'] = 'jpg|jpeg|png';
                $config['max_size'] = 2048;
                $config['file_name'] = 'logo_' . time();
                
                $this->load->library('upload', $config);
                
                if ($this->upload->do_upload('logo')) {
                    $upload_data = $this->upload->data();
                    $data['logo'] = $upload_data['file_name'];
                    
                    // Delete old logo
                    if ($settings && $settings->logo && file_exists('./assets/uploads/' . $settings->logo)) {
                        unlink('./assets/uploads/' . $settings->logo);
                    }
                }
            }
            
            if ($settings) {
                // Update existing settings
                if ($this->Settings_model->update($settings->id, $data)) {
                    $this->session->set_flashdata('success', 'Pengaturan berhasil diupdate');
                } else {
                    $this->session->set_flashdata('error', 'Gagal mengupdate pengaturan');
                }
            } else {
                // Insert new settings
                if ($this->Settings_model->create($data)) {
                    $this->session->set_flashdata('success', 'Pengaturan berhasil disimpan');
                } else {
                    $this->session->set_flashdata('error', 'Gagal menyimpan pengaturan');
                }
            }
        }
        
        redirect('admin/settings');
    }
}
