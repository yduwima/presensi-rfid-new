<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Surat_bk_model extends CI_Model {

    public function get_all() {
        $this->db->select('surat_bk.*, siswa.nama as nama_siswa, siswa.nis, kelas.nama_kelas');
        $this->db->from('surat_bk');
        $this->db->join('siswa', 'siswa.id = surat_bk.siswa_id', 'left');
        $this->db->join('kelas', 'kelas.id = siswa.kelas_id', 'left');
        $this->db->order_by('surat_bk.created_at', 'DESC');
        
        $query = $this->db->get();
        return $query->result();
    }

    public function get_by_id($id) {
        $this->db->select('surat_bk.*, siswa.nama as nama_siswa, siswa.nis, siswa.nama_ortu, kelas.nama_kelas');
        $this->db->from('surat_bk');
        $this->db->join('siswa', 'siswa.id = surat_bk.siswa_id', 'left');
        $this->db->join('kelas', 'kelas.id = siswa.kelas_id', 'left');
        $this->db->where('surat_bk.id', $id);
        
        $query = $this->db->get();
        return $query->row();
    }

    public function get_by_period($bulan, $tahun) {
        $this->db->select('surat_bk.*, siswa.nama as nama_siswa, siswa.nis, kelas.nama_kelas');
        $this->db->from('surat_bk');
        $this->db->join('siswa', 'siswa.id = surat_bk.siswa_id', 'left');
        $this->db->join('kelas', 'kelas.id = siswa.kelas_id', 'left');
        $this->db->where('MONTH(surat_bk.tanggal_surat)', $bulan);
        $this->db->where('YEAR(surat_bk.tanggal_surat)', $tahun);
        $this->db->order_by('surat_bk.created_at', 'DESC');
        
        $query = $this->db->get();
        return $query->result();
    }

    public function count_by_month($bulan, $tahun) {
        $this->db->where('MONTH(surat_bk.tanggal_surat)', $bulan);
        $this->db->where('YEAR(surat_bk.tanggal_surat)', $tahun);
        return $this->db->count_all_results('surat_bk');
    }

    public function get_recent($limit = 5) {
        $this->db->select('surat_bk.*, siswa.nama as nama_siswa, siswa.nis');
        $this->db->from('surat_bk');
        $this->db->join('siswa', 'siswa.id = surat_bk.siswa_id', 'left');
        $this->db->order_by('surat_bk.created_at', 'DESC');
        $this->db->limit($limit);
        
        $query = $this->db->get();
        return $query->result();
    }

    public function create($data) {
        return $this->db->insert('surat_bk', $data);
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('surat_bk', $data);
    }

    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('surat_bk');
    }
}
