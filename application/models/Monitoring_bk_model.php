<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitoring_bk_model extends CI_Model {

    public function get_siswa_alpha($bulan, $tahun) {
        $this->db->select('siswa.*, kelas.nama_kelas, COUNT(absensi_harian.id) as jumlah_alpha');
        $this->db->from('absensi_harian');
        $this->db->join('siswa', 'siswa.id = absensi_harian.user_id', 'left');
        $this->db->join('kelas', 'kelas.id = siswa.kelas_id', 'left');
        $this->db->where('absensi_harian.user_type', 'siswa');
        $this->db->where('absensi_harian.jam_pulang IS NULL', null, false);
        $this->db->where('MONTH(absensi_harian.tanggal)', $bulan);
        $this->db->where('YEAR(absensi_harian.tanggal)', $tahun);
        $this->db->group_by('siswa.id');
        $this->db->having('jumlah_alpha >=', 3);
        $this->db->order_by('jumlah_alpha', 'DESC');
        
        $query = $this->db->get();
        return $query->result();
    }

    public function get_siswa_terlambat($bulan, $tahun) {
        $this->db->select('siswa.*, kelas.nama_kelas, COUNT(absensi_harian.id) as jumlah_terlambat');
        $this->db->from('absensi_harian');
        $this->db->join('siswa', 'siswa.id = absensi_harian.user_id', 'left');
        $this->db->join('kelas', 'kelas.id = siswa.kelas_id', 'left');
        $this->db->where('absensi_harian.user_type', 'siswa');
        $this->db->where('absensi_harian.terlambat >', 0);
        $this->db->where('MONTH(absensi_harian.tanggal)', $bulan);
        $this->db->where('YEAR(absensi_harian.tanggal)', $tahun);
        $this->db->group_by('siswa.id');
        $this->db->having('jumlah_terlambat >=', 5);
        $this->db->order_by('jumlah_terlambat', 'DESC');
        
        $query = $this->db->get();
        return $query->result();
    }

    public function get_detail_alpha($siswa_id, $bulan, $tahun) {
        $this->db->select('*');
        $this->db->from('absensi_harian');
        $this->db->where('user_id', $siswa_id);
        $this->db->where('user_type', 'siswa');
        $this->db->where('jam_pulang IS NULL', null, false);
        $this->db->where('MONTH(tanggal)', $bulan);
        $this->db->where('YEAR(tanggal)', $tahun);
        $this->db->order_by('tanggal', 'DESC');
        
        $query = $this->db->get();
        return $query->result();
    }

    public function get_detail_terlambat($siswa_id, $bulan, $tahun) {
        $this->db->select('*');
        $this->db->from('absensi_harian');
        $this->db->where('user_id', $siswa_id);
        $this->db->where('user_type', 'siswa');
        $this->db->where('terlambat >', 0);
        $this->db->where('MONTH(tanggal)', $bulan);
        $this->db->where('YEAR(tanggal)', $tahun);
        $this->db->order_by('tanggal', 'DESC');
        
        $query = $this->db->get();
        return $query->result();
    }
}
