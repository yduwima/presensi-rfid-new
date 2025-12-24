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
        
        $this->load->model('Wa_settings_model');
        $this->load->model('Kelas_model');
    }

    public function index()
    {
        // Get WA settings from wa_settings table
        $wa_settings = $this->db->get('wa_settings')->row();
        
        $data['wa_url'] = $wa_settings ? $wa_settings->url : '';
        $data['wa_api_key'] = $wa_settings ? $wa_settings->api_key : '';
        $data['wa_sender'] = $wa_settings ? $wa_settings->sender : '';
        
        // Get WA templates
        $data['templates'] = $this->db->get('wa_templates')->result();
        
        // Get classes for notification settings
        $data['kelas'] = $this->Kelas_model->get_all();
        
        // Get enabled classes for notifications
        $data['wa_notif_kelas'] = $this->db->get('wa_notif_kelas')->result();
        
        $data['title'] = 'Pengaturan WhatsApp';
        $data['active_menu'] = 'wa_settings';
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/wa_settings/index', $data);
        $this->load->view('templates/admin_footer');
    }
    
    public function save()
    {
        $wa_url = $this->input->post('wa_url');
        $wa_api_key = $this->input->post('wa_api_key');
        $wa_sender = $this->input->post('wa_sender');
        
        // Check if settings exist
        $existing = $this->db->get('wa_settings')->row();
        
        $data = array(
            'url' => $wa_url,
            'api_key' => $wa_api_key,
            'sender' => $wa_sender
        );
        
        if ($existing) {
            // Update existing settings
            $this->db->where('id', $existing->id);
            $this->db->update('wa_settings', $data);
        } else {
            // Insert new settings
            $this->db->insert('wa_settings', $data);
        }
        
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
}
