<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jurnal_model extends CI_Model {

    protected $table = 'jurnal';

    public function get_all($guru_id = null) {
        $this->db->select('jurnal.*, mata_pelajaran.nama as nama_mapel, kelas.nama_kelas, guru.nama as nama_guru');
        $this->db->join('jadwal_pelajaran', 'jadwal_pelajaran.id = jurnal.jadwal_id', 'left');
        $this->db->join('mata_pelajaran', 'mata_pelajaran.id = jadwal_pelajaran.mata_pelajaran_id', 'left');
        $this->db->join('kelas', 'kelas.id = jadwal_pelajaran.kelas_id', 'left');
        $this->db->join('guru', 'guru.id = jurnal.guru_id', 'left');
        
        if ($guru_id) {
            $this->db->where('jurnal.guru_id', $guru_id);
        }
        
        $this->db->order_by('jurnal.tanggal', 'DESC');
        $this->db->order_by('jurnal.created_at', 'DESC');
        
        return $this->db->get($this->table)->result();
    }

    public function get_by_id($id) {
        $this->db->select('jurnal.*, jadwal_pelajaran.*, mata_pelajaran.nama as nama_mapel, kelas.nama_kelas, kelas.id as kelas_id');
        $this->db->join('jadwal_pelajaran', 'jadwal_pelajaran.id = jurnal.jadwal_id', 'left');
        $this->db->join('mata_pelajaran', 'mata_pelajaran.id = jadwal_pelajaran.mata_pelajaran_id', 'left');
        $this->db->join('kelas', 'kelas.id = jadwal_pelajaran.kelas_id', 'left');
        $this->db->where('jurnal.id', $id);
        
        return $this->db->get($this->table)->row();
    }

    public function get_by_guru($guru_id, $limit = null, $offset = null) {
        $this->db->select('jurnal.*, mata_pelajaran.nama as nama_mapel, kelas.nama_kelas');
        $this->db->join('jadwal_pelajaran', 'jadwal_pelajaran.id = jurnal.jadwal_id', 'left');
        $this->db->join('mata_pelajaran', 'mata_pelajaran.id = jadwal_pelajaran.mata_pelajaran_id', 'left');
        $this->db->join('kelas', 'kelas.id = jadwal_pelajaran.kelas_id', 'left');
        $this->db->where('jurnal.guru_id', $guru_id);
        $this->db->order_by('jurnal.tanggal', 'DESC');
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
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

    public function count_by_guru($guru_id) {
        $this->db->where('guru_id', $guru_id);
        return $this->db->count_all_results($this->table);
    }

    public function check_exists($jadwal_id, $tanggal) {
        $this->db->where('jadwal_id', $jadwal_id);
        $this->db->where('tanggal', $tanggal);
        return $this->db->get($this->table)->num_rows() > 0;
    }
}
