<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jadwal_pelajaran_model extends CI_Model {

    protected $table = 'jadwal_pelajaran';

    public function get_all($kelas_id = null) {
        $this->db->select('jadwal_pelajaran.*, mata_pelajaran.nama as nama_mapel, kelas.nama_kelas, guru.nama as nama_guru');
        $this->db->join('mata_pelajaran', 'mata_pelajaran.id = jadwal_pelajaran.mata_pelajaran_id', 'left');
        $this->db->join('kelas', 'kelas.id = jadwal_pelajaran.kelas_id', 'left');
        $this->db->join('guru', 'guru.id = jadwal_pelajaran.guru_id', 'left');
        
        if ($kelas_id) {
            $this->db->where('jadwal_pelajaran.kelas_id', $kelas_id);
        }
        
        $this->db->order_by('jadwal_pelajaran.hari', 'ASC');
        $this->db->order_by('jadwal_pelajaran.jam_mulai', 'ASC');
        
        return $this->db->get($this->table)->result();
    }

    public function get_by_id($id) {
        return $this->db->get_where($this->table, array('id' => $id))->row();
    }

    public function get_by_guru($guru_id) {
        $this->db->select('jadwal_pelajaran.*, mata_pelajaran.nama as nama_mapel, kelas.nama_kelas');
        $this->db->join('mata_pelajaran', 'mata_pelajaran.id = jadwal_pelajaran.mata_pelajaran_id', 'left');
        $this->db->join('kelas', 'kelas.id = jadwal_pelajaran.kelas_id', 'left');
        $this->db->where('jadwal_pelajaran.guru_id', $guru_id);
        $this->db->order_by('jadwal_pelajaran.hari', 'ASC');
        $this->db->order_by('jadwal_pelajaran.jam_mulai', 'ASC');
        
        return $this->db->get($this->table)->result();
    }

    public function get_by_day($hari, $guru_id = null) {
        $this->db->select('jadwal_pelajaran.*, mata_pelajaran.nama as nama_mapel, kelas.nama_kelas');
        $this->db->join('mata_pelajaran', 'mata_pelajaran.id = jadwal_pelajaran.mata_pelajaran_id', 'left');
        $this->db->join('kelas', 'kelas.id = jadwal_pelajaran.kelas_id', 'left');
        $this->db->where('jadwal_pelajaran.hari', $hari);
        
        if ($guru_id) {
            $this->db->where('jadwal_pelajaran.guru_id', $guru_id);
        }
        
        $this->db->order_by('jadwal_pelajaran.jam_mulai', 'ASC');
        
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

    public function check_conflict($kelas_id, $hari, $jam_mulai, $jam_selesai, $exclude_id = null) {
        $this->db->where('kelas_id', $kelas_id);
        $this->db->where('hari', $hari);
        
        $this->db->group_start();
        $this->db->where('jam_mulai <', $jam_selesai);
        $this->db->where('jam_selesai >', $jam_mulai);
        $this->db->group_end();
        
        if ($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        
        return $this->db->get($this->table)->num_rows() > 0;
    }
    
    public function count_by_guru($guru_id) {
        $this->db->where('guru_id', $guru_id);
        return $this->db->count_all_results($this->table);
    }
}
