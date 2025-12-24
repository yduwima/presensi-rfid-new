<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Izin_siswa_model extends CI_Model {

    protected $table = 'izin_siswa';

    public function get_all()
    {
        $this->db->select('izin_siswa.*, siswa.nama as siswa_nama, siswa.nis, guru.nama as guru_nama, kelas.nama_kelas');
        $this->db->from($this->table);
        $this->db->join('siswa', 'siswa.id = izin_siswa.siswa_id', 'left');
        $this->db->join('guru', 'guru.id = izin_siswa.guru_id', 'left');
        $this->db->join('kelas', 'kelas.id = siswa.kelas_id', 'left');
        $this->db->order_by('izin_siswa.created_at', 'DESC');
        return $this->db->get()->result();
    }

    public function get_by_id($id)
    {
        $this->db->select('izin_siswa.*, siswa.nama as siswa_nama, siswa.nis, guru.nama as guru_nama, kelas.nama_kelas');
        $this->db->from($this->table);
        $this->db->join('siswa', 'siswa.id = izin_siswa.siswa_id', 'left');
        $this->db->join('guru', 'guru.id = izin_siswa.guru_id', 'left');
        $this->db->join('kelas', 'kelas.id = siswa.kelas_id', 'left');
        $this->db->where('izin_siswa.id', $id);
        return $this->db->get()->row();
    }

    public function get_by_kelas($kelas_id, $limit = null)
    {
        $this->db->select('izin_siswa.*, siswa.nama as siswa_nama, siswa.nis, guru.nama as guru_nama');
        $this->db->from($this->table);
        $this->db->join('siswa', 'siswa.id = izin_siswa.siswa_id', 'left');
        $this->db->join('guru', 'guru.id = izin_siswa.guru_id', 'left');
        $this->db->where('siswa.kelas_id', $kelas_id);
        $this->db->order_by('izin_siswa.created_at', 'DESC');
        
        if ($limit) {
            $this->db->limit($limit);
        }
        
        return $this->db->get()->result();
    }

    public function get_by_siswa($siswa_id, $limit = null)
    {
        $this->db->select('izin_siswa.*, guru.nama as guru_nama');
        $this->db->from($this->table);
        $this->db->join('guru', 'guru.id = izin_siswa.guru_id', 'left');
        $this->db->where('izin_siswa.siswa_id', $siswa_id);
        $this->db->order_by('izin_siswa.created_at', 'DESC');
        
        if ($limit) {
            $this->db->limit($limit);
        }
        
        return $this->db->get()->result();
    }

    public function get_active($siswa_id = null)
    {
        $today = date('Y-m-d');
        
        $this->db->select('izin_siswa.*, siswa.nama as siswa_nama, siswa.nis, kelas.nama_kelas');
        $this->db->from($this->table);
        $this->db->join('siswa', 'siswa.id = izin_siswa.siswa_id', 'left');
        $this->db->join('kelas', 'kelas.id = siswa.kelas_id', 'left');
        $this->db->where('izin_siswa.tanggal_mulai <=', $today);
        $this->db->where('izin_siswa.tanggal_selesai >=', $today);
        $this->db->where('izin_siswa.status', 'disetujui');
        
        if ($siswa_id) {
            $this->db->where('izin_siswa.siswa_id', $siswa_id);
        }
        
        return $this->db->get()->result();
    }

    public function create($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }

    public function count_by_periode($tanggal_mulai, $tanggal_selesai, $jenis = null)
    {
        $this->db->from($this->table);
        $this->db->where('tanggal_mulai >=', $tanggal_mulai);
        $this->db->where('tanggal_selesai <=', $tanggal_selesai);
        
        if ($jenis) {
            $this->db->where('jenis', $jenis);
        }
        
        return $this->db->count_all_results();
    }
}
