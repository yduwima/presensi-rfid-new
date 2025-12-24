<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Cron Controller
 * For scheduled tasks via cron jobs
 * 
 * Setup cron jobs:
 * 1. Process WA Queue: every 5 minutes - curl http://localhost/presensi-rfid-new/cron/process_wa_queue
 * 2. Send Reminder: daily at 9 AM - curl http://localhost/presensi-rfid-new/cron/send_reminder
 */
class Cron extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('Whatsapp');
        $this->load->model('Wa_model');
        $this->load->model('Siswa_model');
        $this->load->model('Absensi_model');
    }

    /**
     * Process WhatsApp Queue
     * Process pending WA messages in queue
     * Run every 5 minutes
     */
    public function process_wa_queue() {
        // Security: Only allow from localhost or CLI
        if (!$this->_is_cli() && !$this->_is_localhost()) {
            show_error('Access denied', 403, 'Forbidden');
            return;
        }

        $result = $this->whatsapp->process_queue(10);
        
        $response = array(
            'success' => true,
            'message' => 'Queue processed',
            'data' => $result
        );
        
        echo json_encode($response);
    }

    /**
     * Send Reminder
     * Send reminder to students who haven't checked in by 9:00 AM
     * Run daily at 9:00 AM
     */
    public function send_reminder() {
        // Security: Only allow from localhost or CLI
        if (!$this->_is_cli() && !$this->_is_localhost()) {
            show_error('Access denied', 403, 'Forbidden');
            return;
        }

        $today = date('Y-m-d');
        $sent_count = 0;
        
        // Get all active students
        $this->db->where('is_active', 1);
        $siswa_list = $this->db->get('siswa')->result();
        
        foreach ($siswa_list as $siswa) {
            // Check if student has checked in today
            $absen = $this->Absensi_model->get_today('siswa', $siswa->id);
            
            // If no attendance record, send reminder
            if (!$absen && !empty($siswa->telp_ortu)) {
                // Get reminder template
                $template = $this->Wa_model->get_template('reminder');
                
                if ($template) {
                    $message = $template->template;
                    $message = str_replace('{nama_siswa}', $siswa->nama, $message);
                    $message = str_replace('{tanggal}', date('d-m-Y'), $message);
                    $message = str_replace('{waktu}', date('H:i'), $message);
                    
                    // Add to queue
                    $this->Wa_model->add_to_queue($siswa->telp_ortu, $message);
                    $sent_count++;
                }
            }
        }
        
        $response = array(
            'success' => true,
            'message' => 'Reminder sent',
            'data' => array(
                'total_students' => count($siswa_list),
                'reminders_sent' => $sent_count
            )
        );
        
        echo json_encode($response);
    }

    /**
     * Check if request is from CLI
     */
    private function _is_cli() {
        return (php_sapi_name() === 'cli');
    }

    /**
     * Check if request is from localhost
     */
    private function _is_localhost() {
        $allowed_ips = array('127.0.0.1', '::1', 'localhost');
        return in_array($_SERVER['REMOTE_ADDR'], $allowed_ips);
    }
}
