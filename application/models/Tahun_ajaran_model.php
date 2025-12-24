<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tahun_ajaran_model extends CI_Model {

    protected $table = 'tahun_ajaran';

    public function get_all() {
        $this->db->order_by('tahun', 'DESC');
        return $this->db->get($this->table)->result();
    }

    public function get_by_id($id) {
        return $this->db->get_where($this->table, array('id' => $id))->row();
    }

    public function get_active() {
        return $this->db->get_where($this->table, array('is_active' => 1))->row();
    }

    public function create($data) {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function delete($id) {
        return $this->db->delete($this->table, array('id' => $id));
    }

    public function deactivate_all() {
        return $this->db->update($this->table, array('is_active' => 0));
    }

    public function count_all() {
        return $this->db->count_all($this->table);
    }
}
