<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Siswa_model extends CI_Model {

    protected $table = 'siswa';

    public function get_all($limit = null, $offset = null) {
        $this->db->select('siswa.*, kelas.nama_kelas');
        $this->db->join('kelas', 'kelas.id = siswa.kelas_id', 'left');
        $this->db->where('siswa.is_active', 1);
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get($this->table)->result();
    }

    public function get_by_id($id) {
        $this->db->select('siswa.*, kelas.nama_kelas');
        $this->db->join('kelas', 'kelas.id = siswa.kelas_id', 'left');
        $this->db->where('siswa.id', $id);
        return $this->db->get($this->table)->row();
    }

    public function get_by_kelas($kelas_id) {
        $this->db->where('kelas_id', $kelas_id);
        $this->db->where('is_active', 1);
        return $this->db->get($this->table)->result();
    }

    public function get_by_rfid($rfid_uid) {
        return $this->db->get_where($this->table, array('rfid_uid' => $rfid_uid))->row();
    }

    public function create($data) {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function delete($id) {
        // Soft delete
        return $this->update($id, array('is_active' => 0));
    }

    public function count_all() {
        $this->db->where('is_active', 1);
        return $this->db->count_all_results($this->table);
    }

    public function search($keyword, $limit = null, $offset = null) {
        $this->db->select('siswa.*, kelas.nama_kelas');
        $this->db->join('kelas', 'kelas.id = siswa.kelas_id', 'left');
        $this->db->group_start();
        $this->db->like('siswa.nama', $keyword);
        $this->db->or_like('siswa.nis', $keyword);
        $this->db->or_like('siswa.nisn', $keyword);
        $this->db->group_end();
        $this->db->where('siswa.is_active', 1);
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get($this->table)->result();
    }

    public function count_search($keyword) {
        $this->db->group_start();
        $this->db->like('nama', $keyword);
        $this->db->or_like('nis', $keyword);
        $this->db->or_like('nisn', $keyword);
        $this->db->group_end();
        $this->db->where('is_active', 1);
        return $this->db->count_all_results($this->table);
    }
}
