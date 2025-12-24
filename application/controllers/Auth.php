<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Settings_model');
        $this->load->library('form_validation');
    }

    public function index() {
        // Redirect to login
        $this->login();
    }

    public function login() {
        // Check if already logged in
        if ($this->session->userdata('logged_in')) {
            $role = $this->session->userdata('role');
            $this->_redirect_by_role($role);
            return;
        }

        // Handle login form submission
        if ($this->input->post()) {
            $this->form_validation->set_rules('username', 'Username', 'required|trim');
            $this->form_validation->set_rules('password', 'Password', 'required');

            if ($this->form_validation->run() == TRUE) {
                $username = $this->input->post('username');
                $password = $this->input->post('password');

                $user = $this->User_model->login($username, $password);

                if ($user) {
                    // Set session data
                    $session_data = array(
                        'user_id' => $user->id,
                        'username' => $user->username,
                        'role' => $user->role,
                        'guru_id' => $user->guru_id,
                        'logged_in' => TRUE
                    );
                    $this->session->set_userdata($session_data);

                    // Redirect based on role
                    $this->_redirect_by_role($user->role);
                } else {
                    $this->session->set_flashdata('error', 'Username atau password salah!');
                    redirect('auth/login');
                }
            }
        }

        // Load login view
        $data['settings'] = $this->Settings_model->get();
        $this->load->view('auth/login', $data);
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('auth/login');
    }

    private function _redirect_by_role($role) {
        switch ($role) {
            case 'admin':
                redirect('admin/dashboard');
                break;
            case 'guru':
                redirect('guru/dashboard');
                break;
            case 'bk':
                redirect('bk/dashboard');
                break;
            default:
                redirect('auth/login');
                break;
        }
    }

    // Check if user is logged in (for use in other controllers)
    public function check_login() {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    // Check if user has specific role
    public function check_role($allowed_roles = array()) {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }

        $user_role = $this->session->userdata('role');
        if (!in_array($user_role, $allowed_roles)) {
            show_error('Anda tidak memiliki akses ke halaman ini.', 403, 'Akses Ditolak');
        }
    }
}
