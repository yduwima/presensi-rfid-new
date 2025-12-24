<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Absensi_mapel_model extends CI_Model {

    protected $table = 'absensi_mapel';

    public function get_by_jurnal($jurnal_id) {
        $this->db->select('absensi_mapel.*, siswa.nama, siswa.nis');
        $this->db->join('siswa', 'siswa.id = absensi_mapel.siswa_id', 'left');
        $this->db->where('absensi_mapel.jurnal_id', $jurnal_id);
        $this->db->order_by('siswa.nama', 'ASC');
        
        return $this->db->get($this->table)->result();
    }

    public function create($data) {
        return $this->db->insert($this->table, $data);
    }

    public function create_batch($data) {
        return $this->db->insert_batch($this->table, $data);
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function delete_by_jurnal($jurnal_id) {
        return $this->db->delete($this->table, array('jurnal_id' => $jurnal_id));
    }

    public function get_stats_by_siswa($siswa_id, $start_date = null, $end_date = null) {
        $this->db->select('status, COUNT(*) as total');
        $this->db->where('siswa_id', $siswa_id);
        
        if ($start_date && $end_date) {
            $this->db->join('jurnal', 'jurnal.id = absensi_mapel.jurnal_id', 'left');
            $this->db->where('jurnal.tanggal >=', $start_date);
            $this->db->where('jurnal.tanggal <=', $end_date);
        }
        
        $this->db->group_by('status');
        
        $result = $this->db->get($this->table)->result();
        
        $stats = array('H' => 0, 'S' => 0, 'I' => 0, 'A' => 0);
        foreach ($result as $row) {
            $stats[$row->status] = $row->total;
        }
        
        return $stats;
    }
}
