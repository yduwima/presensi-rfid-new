<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hari_kerja_model extends CI_Model {

    protected $table = 'hari_kerja';

    public function get_all() {
        return $this->db->get($this->table)->result();
    }

    public function get_by_id($id) {
        return $this->db->get_where($this->table, array('id' => $id))->row();
    }

    public function get_by_hari($hari) {
        return $this->db->get_where($this->table, array('hari' => $hari))->row();
    }

    public function get_active_days() {
        $this->db->where('is_active', 1);
        return $this->db->get($this->table)->result();
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function update_by_hari($hari, $data) {
        $this->db->where('hari', $hari);
        return $this->db->update($this->table, $data);
    }
}
