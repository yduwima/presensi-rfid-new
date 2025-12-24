<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Whatsapp {

    protected $CI;
    protected $api_url;
    protected $api_key;
    protected $sender;

    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->model('Wa_model');
        
        // Load settings
        $settings = $this->CI->Wa_model->get_settings();
        if ($settings) {
            $this->api_url = $settings->url;
            $this->api_key = $settings->api_key;
            $this->sender = $settings->sender;
        }
    }

    public function send($phone, $message) {
        if (empty($this->api_url) || empty($this->api_key)) {
            return array(
                'success' => false,
                'message' => 'WhatsApp API belum dikonfigurasi'
            );
        }

        // Clean phone number
        $phone = $this->_clean_phone($phone);

        // Prepare data for Fonnte API (adjust based on your WA API provider)
        $data = array(
            'target' => $phone,
            'message' => $message,
            'countryCode' => '62' // Indonesia
        );

        // Send via cURL
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Authorization: ' . $this->api_key,
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return array(
                'success' => false,
                'message' => 'cURL Error: ' . $err
            );
        }

        $result = json_decode($response);
        
        return array(
            'success' => isset($result->status) && $result->status == true,
            'message' => isset($result->message) ? $result->message : 'Unknown response',
            'response' => $result
        );
    }

    private function _clean_phone($phone) {
        // Remove any non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Remove leading zero and add country code
        if (substr($phone, 0, 1) == '0') {
            $phone = '62' . substr($phone, 1);
        }
        
        // Ensure it starts with 62
        if (substr($phone, 0, 2) != '62') {
            $phone = '62' . $phone;
        }
        
        return $phone;
    }

    public function process_queue($limit = 10) {
        $queue = $this->CI->Wa_model->get_pending_queue($limit);
        
        $processed = 0;
        $success = 0;
        $failed = 0;

        foreach ($queue as $item) {
            $result = $this->send($item->phone, $item->message);
            
            if ($result['success']) {
                $this->CI->Wa_model->update_queue_status($item->id, 'sent', date('Y-m-d H:i:s'));
                $success++;
            } else {
                // Increment retry count
                $this->CI->Wa_model->increment_retry($item->id);
                
                // Mark as failed if retry count exceeds 3
                if ($item->retry_count >= 2) {
                    $this->CI->Wa_model->update_queue_status($item->id, 'failed');
                }
                $failed++;
            }
            
            $processed++;
            
            // Add small delay between messages
            sleep(1);
        }

        return array(
            'processed' => $processed,
            'success' => $success,
            'failed' => $failed
        );
    }
}
