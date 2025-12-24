<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    protected $table = 'users';

    public function login($username, $password) {
        $this->db->where('username', $username);
        $this->db->where('is_active', 1);
        $user = $this->db->get($this->table)->row();

        if ($user && password_verify($password, $user->password)) {
            return $user;
        }

        return false;
    }

    public function get_by_id($id) {
        return $this->db->get_where($this->table, array('id' => $id))->row();
    }

    public function get_by_username($username) {
        return $this->db->get_where($this->table, array('username' => $username))->row();
    }

    public function create($data) {
        // Hash password before insert
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data) {
        // Hash password if being updated
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']);
        }

        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function delete($id) {
        return $this->db->delete($this->table, array('id' => $id));
    }

    public function get_all($limit = null, $offset = null) {
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get($this->table)->result();
    }

    public function count_all() {
        return $this->db->count_all($this->table);
    }

    public function search($keyword, $limit = null, $offset = null) {
        $this->db->like('username', $keyword);
        
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get($this->table)->result();
    }
}
