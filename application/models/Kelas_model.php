<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kelas_model extends CI_Model {

    protected $table = 'kelas';

    public function get_all() {
        $this->db->select('kelas.*, tahun_ajaran.tahun, guru.nama as wali_kelas_nama');
        $this->db->join('tahun_ajaran', 'tahun_ajaran.id = kelas.tahun_ajaran_id', 'left');
        $this->db->join('guru', 'guru.id = kelas.wali_kelas_id', 'left');
        $this->db->order_by('kelas.tingkat', 'ASC');
        $this->db->order_by('kelas.nama_kelas', 'ASC');
        return $this->db->get($this->table)->result();
    }

    public function get_by_id($id) {
        return $this->db->get_where($this->table, array('id' => $id))->row();
    }

    public function get_by_tahun_ajaran($tahun_ajaran_id) {
        $this->db->where('tahun_ajaran_id', $tahun_ajaran_id);
        $this->db->order_by('tingkat', 'ASC');
        $this->db->order_by('nama_kelas', 'ASC');
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

    public function count_siswa($kelas_id) {
        $this->db->where('kelas_id', $kelas_id);
        $this->db->where('is_active', 1);
        return $this->db->count_all_results('siswa');
    }
}
