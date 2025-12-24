<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings_model extends CI_Model {

    protected $table = 'settings';

    public function get() {
        return $this->db->get($this->table)->row();
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function create($data) {
        return $this->db->insert($this->table, $data);
    }

    public function get_by_id($id) {
        return $this->db->get_where($this->table, array('id' => $id))->row();
    }
}
