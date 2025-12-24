<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wa_settings extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        
        // Check if user is logged in and has admin role
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') !== 'admin') {
            redirect('auth/login');
        }
        
        $this->load->model('Settings_model');
        $this->load->model('Kelas_model');
    }

    public function index()
    {
        // Get WA settings from database
        $this->db->where('setting_key', 'wa_url');
        $wa_url = $this->db->get('settings')->row();
        
        $this->db->where('setting_key', 'wa_api_key');
        $wa_api_key = $this->db->get('settings')->row();
        
        $this->db->where('setting_key', 'wa_sender');
        $wa_sender = $this->db->get('settings')->row();
        
        $data['wa_url'] = $wa_url ? $wa_url->setting_value : '';
        $data['wa_api_key'] = $wa_api_key ? $wa_api_key->setting_value : '';
        $data['wa_sender'] = $wa_sender ? $wa_sender->setting_value : '';
        
        // Get WA templates
        $data['templates'] = $this->db->get('wa_templates')->result();
        
        // Get classes for notification settings
        $data['kelas'] = $this->Kelas_model->get_all();
        
        // Get enabled classes for notifications
        $data['wa_notif_kelas'] = $this->db->get('wa_notif_kelas')->result();
        
        $data['title'] = 'Pengaturan WhatsApp';
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/wa_settings/index', $data);
        $this->load->view('templates/admin_footer');
    }
    
    public function save()
    {
        $wa_url = $this->input->post('wa_url');
        $wa_api_key = $this->input->post('wa_api_key');
        $wa_sender = $this->input->post('wa_sender');
        
        // Update or insert settings
        $this->update_setting('wa_url', $wa_url);
        $this->update_setting('wa_api_key', $wa_api_key);
        $this->update_setting('wa_sender', $wa_sender);
        
        $this->session->set_flashdata('success', 'Pengaturan WhatsApp berhasil disimpan');
        redirect('admin/wa_settings');
    }
    
    public function update_template()
    {
        $id = $this->input->post('id');
        $template = $this->input->post('template');
        
        $this->db->where('id', $id);
        $this->db->update('wa_templates', ['template' => $template]);
        
        $this->session->set_flashdata('success', 'Template berhasil diperbarui');
        redirect('admin/wa_settings');
    }
    
    public function toggle_kelas()
    {
        $kelas_id = $this->input->post('kelas_id');
        $enabled = $this->input->post('enabled');
        
        if ($enabled == '1') {
            // Add to enabled classes
            $this->db->insert('wa_notif_kelas', ['kelas_id' => $kelas_id]);
        } else {
            // Remove from enabled classes
            $this->db->delete('wa_notif_kelas', ['kelas_id' => $kelas_id]);
        }
        
        echo json_encode(['success' => true]);
    }
    
    private function update_setting($key, $value)
    {
        $this->db->where('setting_key', $key);
        $exists = $this->db->get('settings')->num_rows();
        
        if ($exists > 0) {
            $this->db->where('setting_key', $key);
            $this->db->update('settings', ['setting_value' => $value]);
        } else {
            $this->db->insert('settings', [
                'setting_key' => $key,
                'setting_value' => $value
            ]);
        }
    }
}
