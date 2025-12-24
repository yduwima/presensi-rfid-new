<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mata_pelajaran_model extends CI_Model {

    protected $table = 'mata_pelajaran';

    public function get_all($limit = null, $offset = null) {
        $this->db->order_by('nama', 'ASC');
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get($this->table)->result();
    }

    public function get_by_id($id) {
        return $this->db->get_where($this->table, array('id' => $id))->row();
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

    public function count_all() {
        return $this->db->count_all($this->table);
    }

    public function search($keyword, $limit = null, $offset = null) {
        $this->db->like('nama', $keyword);
        $this->db->or_like('kode', $keyword);
        $this->db->order_by('nama', 'ASC');
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get($this->table)->result();
    }
    
    public function count_search($keyword) {
        $this->db->like('nama', $keyword);
        $this->db->or_like('kode', $keyword);
        return $this->db->count_all_results($this->table);
    }
}
