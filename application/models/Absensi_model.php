<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Absensi_model extends CI_Model {

    protected $table = 'absensi_harian';

    public function get_all($user_type = null, $limit = null, $offset = null) {
        if ($user_type) {
            $this->db->where('user_type', $user_type);
        }
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        $this->db->order_by('tanggal', 'DESC');
        $this->db->order_by('jam_masuk', 'DESC');
        return $this->db->get($this->table)->result();
    }

    public function get_by_id($id) {
        return $this->db->get_where($this->table, array('id' => $id))->row();
    }

    public function get_today($user_type, $user_id) {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_id', $user_id);
        $this->db->where('tanggal', date('Y-m-d'));
        return $this->db->get($this->table)->row();
    }

    public function count_today($user_type) {
        $this->db->where('user_type', $user_type);
        $this->db->where('tanggal', date('Y-m-d'));
        return $this->db->count_all_results($this->table);
    }

    public function get_recent($user_type, $limit = 10) {
        $this->db->where('user_type', $user_type);
        $this->db->where('tanggal', date('Y-m-d'));
        $this->db->order_by('jam_masuk', 'DESC');
        $this->db->limit($limit);
        return $this->db->get($this->table)->result();
    }

    public function get_by_date_range($user_type, $user_id, $start_date, $end_date) {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_id', $user_id);
        $this->db->where('tanggal >=', $start_date);
        $this->db->where('tanggal <=', $end_date);
        $this->db->order_by('tanggal', 'ASC');
        return $this->db->get($this->table)->result();
    }

    public function create($data) {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function tap_in($user_type, $user_id, $jam_masuk_scheduled) {
        // Check if already tapped in today
        $existing = $this->get_today($user_type, $user_id);
        
        $current_time = date('H:i:s');
        $jam_masuk = strtotime($current_time);
        $jam_scheduled = strtotime($jam_masuk_scheduled);
        
        $status_masuk = 'tepat_waktu';
        $menit_keterlambatan = 0;
        
        if ($jam_masuk > $jam_scheduled) {
            $status_masuk = 'terlambat';
            $menit_keterlambatan = round(($jam_masuk - $jam_scheduled) / 60);
        }
        
        if ($existing) {
            // Update jam masuk if not already set
            if (empty($existing->jam_masuk)) {
                $data = array(
                    'jam_masuk' => $current_time,
                    'status_masuk' => $status_masuk,
                    'menit_keterlambatan' => $menit_keterlambatan
                );
                return $this->update($existing->id, $data);
            }
            return false; // Already tapped in
        } else {
            // Create new record
            $data = array(
                'user_type' => $user_type,
                'user_id' => $user_id,
                'tanggal' => date('Y-m-d'),
                'jam_masuk' => $current_time,
                'status_masuk' => $status_masuk,
                'menit_keterlambatan' => $menit_keterlambatan
            );
            return $this->create($data);
        }
    }

    public function tap_out($user_type, $user_id) {
        $existing = $this->get_today($user_type, $user_id);
        
        if ($existing && !empty($existing->jam_masuk)) {
            // Update jam pulang
            $data = array(
                'jam_pulang' => date('H:i:s')
            );
            return $this->update($existing->id, $data);
        }
        
        return false; // Must tap in first
    }

    public function count_all() {
        return $this->db->count_all($this->table);
    }
}
