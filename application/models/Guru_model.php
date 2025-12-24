<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Guru_model extends CI_Model {

    protected $table = 'guru';

    public function get_all($limit = null, $offset = null) {
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get($this->table)->result();
    }

    public function get_by_id($id) {
        return $this->db->get_where($this->table, array('id' => $id))->row();
    }

    public function get_by_nip($nip) {
        return $this->db->get_where($this->table, array('nip' => $nip))->row();
    }

    public function get_by_rfid($rfid_uid) {
        return $this->db->get_where($this->table, array('rfid_uid' => $rfid_uid))->row();
    }

    public function get_by_user_id($user_id) {
        // Join with users table to get guru data by user_id
        $this->db->select('guru.*');
        $this->db->from('guru');
        $this->db->join('users', 'users.guru_id = guru.id');
        $this->db->where('users.id', $user_id);
        return $this->db->get()->row();
    }

    public function get_wali_kelas() {
        $this->db->where('is_wali_kelas', 1);
        return $this->db->get($this->table)->result();
    }

    public function get_piket() {
        $this->db->where('is_piket', 1);
        return $this->db->get($this->table)->result();
    }

    public function get_bk() {
        $this->db->where('is_bk', 1);
        return $this->db->get($this->table)->result();
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
        $this->db->group_start();
        $this->db->like('nama', $keyword);
        $this->db->or_like('nip', $keyword);
        $this->db->or_like('email', $keyword);
        $this->db->group_end();
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get($this->table)->result();
    }

    public function count_search($keyword) {
        $this->db->group_start();
        $this->db->like('nama', $keyword);
        $this->db->or_like('nip', $keyword);
        $this->db->or_like('email', $keyword);
        $this->db->group_end();
        return $this->db->count_all_results($this->table);
    }
}
