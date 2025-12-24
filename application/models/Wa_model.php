<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wa_model extends CI_Model {

    protected $table_settings = 'wa_settings';
    protected $table_templates = 'wa_templates';
    protected $table_queue = 'wa_queue';

    public function get_settings() {
        return $this->db->get_where($this->table_settings, array('is_active' => 1))->row();
    }

    public function update_settings($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table_settings, $data);
    }

    public function get_template($type) {
        return $this->db->get_where($this->table_templates, array(
            'type' => $type,
            'is_active' => 1
        ))->row();
    }

    public function get_all_templates() {
        return $this->db->get($this->table_templates)->result();
    }

    public function update_template($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table_templates, $data);
    }

    public function add_to_queue($phone, $message) {
        $data = array(
            'phone' => $phone,
            'message' => $message,
            'status' => 'pending'
        );
        return $this->db->insert($this->table_queue, $data);
    }

    public function get_pending_queue($limit = 10) {
        $this->db->where('status', 'pending');
        $this->db->limit($limit);
        return $this->db->get($this->table_queue)->result();
    }

    public function update_queue_status($id, $status, $sent_at = null) {
        $data = array('status' => $status);
        if ($sent_at) {
            $data['sent_at'] = $sent_at;
        }
        $this->db->where('id', $id);
        return $this->db->update($this->table_queue, $data);
    }

    public function increment_retry($id) {
        $this->db->where('id', $id);
        $this->db->set('retry_count', 'retry_count + 1', FALSE);
        return $this->db->update($this->table_queue);
    }
}
