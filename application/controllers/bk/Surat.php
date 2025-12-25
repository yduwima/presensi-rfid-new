<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Surat extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        
        // Check if user has BK role
        $this->load->model('Guru_model');
        $user_id = $this->session->userdata('user_id');
        $guru = $this->Guru_model->get_by_user_id($user_id);
        
        if (!$guru || !$guru->is_bk) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke panel BK');
            redirect('auth/login');
        }
        
        $this->load->model('Surat_bk_model');
        $this->load->model('Siswa_model');
        $this->load->model('Settings_model');
        $this->load->library('Pdf_generator');
        $this->load->library('form_validation');
    }

    public function index() {
        $data['title'] = 'Cetak Surat Panggilan';
        
        // Get filter
        $bulan = $this->input->get('bulan') ? $this->input->get('bulan') : date('n');
        $tahun = $this->input->get('tahun') ? $this->input->get('tahun') : date('Y');
        
        $data['letters'] = $this->Surat_bk_model->get_by_period($bulan, $tahun);
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;
        
        $this->load->view('templates/bk_header', $data);
        $this->load->view('bk/surat/index', $data);
        $this->load->view('templates/bk_footer');
    }

    public function create($siswa_id = null) {
        $data['title'] = 'Buat Surat Panggilan';
        
        if ($siswa_id) {
            $data['siswa'] = $this->Siswa_model->get_by_id($siswa_id);
            if (!$data['siswa']) {
                $this->session->set_flashdata('error', 'Siswa tidak ditemukan');
                redirect('bk/monitoring');
            }
        } else {
            $data['siswa'] = null;
        }
        
        // Get all students for dropdown
        $data['all_siswa'] = $this->Siswa_model->get_all();
        
        $this->load->view('templates/bk_header', $data);
        $this->load->view('bk/surat/form', $data);
        $this->load->view('templates/bk_footer');
    }

    public function store() {
        // Validation
        $this->form_validation->set_rules('siswa_id', 'Siswa', 'required');
        $this->form_validation->set_rules('nomor_surat', 'Nomor Surat', 'required');
        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
        $this->form_validation->set_rules('waktu', 'Waktu', 'required');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('bk/surat/create');
        }
        
        // Get current user's guru_id
        $user_id = $this->session->userdata('user_id');
        $guru = $this->Guru_model->get_by_user_id($user_id);
        
        if (!$guru) {
            $this->session->set_flashdata('error', 'Data guru tidak ditemukan');
            redirect('bk/surat');
            return;
        }
        
        // Combine date and time for waktu_panggilan
        $tanggal = $this->input->post('tanggal');
        $waktu = $this->input->post('waktu');
        $waktu_panggilan = $tanggal . ' ' . $waktu . ':00';
        
        $data = array(
            'siswa_id' => $this->input->post('siswa_id'),
            'nomor_surat' => $this->input->post('nomor_surat'),
            'tanggal_surat' => $tanggal,
            'waktu_panggilan' => $waktu_panggilan,
            'alasan' => $this->input->post('keterangan'),
            'guru_bk_id' => $guru->id,
            'status' => 'terkirim'
        );
        
        $result = $this->Surat_bk_model->create($data);
        
        if ($result) {
            $this->session->set_flashdata('success', 'Surat berhasil dibuat');
        } else {
            $this->session->set_flashdata('error', 'Gagal membuat surat');
        }
        
        redirect('bk/surat');
    }

    public function edit($id) {
        $data['title'] = 'Edit Surat Panggilan';
        $data['surat'] = $this->Surat_bk_model->get_by_id($id);
        
        if (!$data['surat']) {
            $this->session->set_flashdata('error', 'Surat tidak ditemukan');
            redirect('bk/surat');
        }
        
        $data['siswa'] = $this->Siswa_model->get_by_id($data['surat']->siswa_id);
        $data['all_siswa'] = $this->Siswa_model->get_all();
        
        $this->load->view('templates/bk_header', $data);
        $this->load->view('bk/surat/form', $data);
        $this->load->view('templates/bk_footer');
    }

    public function update($id) {
        // Validation
        $this->form_validation->set_rules('nomor_surat', 'Nomor Surat', 'required');
        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
        $this->form_validation->set_rules('waktu', 'Waktu', 'required');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('bk/surat/edit/' . $id);
        }
        
        // Combine date and time for waktu_panggilan
        $tanggal = $this->input->post('tanggal');
        $waktu = $this->input->post('waktu');
        $waktu_panggilan = $tanggal . ' ' . $waktu . ':00';
        
        $data = array(
            'nomor_surat' => $this->input->post('nomor_surat'),
            'tanggal_surat' => $tanggal,
            'waktu_panggilan' => $waktu_panggilan,
            'alasan' => $this->input->post('keterangan')
        );
        
        $result = $this->Surat_bk_model->update($id, $data);
        
        if ($result) {
            $this->session->set_flashdata('success', 'Surat berhasil diupdate');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengupdate surat');
        }
        
        redirect('bk/surat');
    }

    public function delete($id) {
        $result = $this->Surat_bk_model->delete($id);
        
        if ($result) {
            $this->session->set_flashdata('success', 'Surat berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus surat');
        }
        
        redirect('bk/surat');
    }

    public function pdf($id) {
        $surat = $this->Surat_bk_model->get_by_id($id);
        
        if (!$surat) {
            show_404();
        }
        
        $siswa = $this->Siswa_model->get_by_id($surat->siswa_id);
        $settings = $this->Settings_model->get();
        
        // Create PDF
        $this->pdf_generator->create_pdf();
        $pdf = $this->pdf_generator->get_pdf();
        
        // Add school header
        $this->pdf_generator->add_school_header($settings, 'SURAT PANGGILAN ORANG TUA');
        
        // Letter content
        $pdf->Ln(10);
        $pdf->SetFont('Arial', '', 11);
        
        // Letter number and date
        $pdf->Cell(0, 6, 'Nomor: ' . $surat->nomor_surat, 0, 1);
        $pdf->Cell(0, 6, 'Tanggal: ' . date('d F Y', strtotime($surat->tanggal_surat)), 0, 1);
        $pdf->Ln(5);
        
        // Greeting
        $pdf->Cell(0, 6, 'Kepada Yth.', 0, 1);
        $pdf->Cell(0, 6, 'Bapak/Ibu ' . $siswa->nama_ortu, 0, 1);
        $pdf->Cell(0, 6, 'Orang Tua/Wali dari ' . $siswa->nama, 0, 1);
        $pdf->Cell(0, 6, 'di Tempat', 0, 1);
        $pdf->Ln(5);
        
        // Opening
        $pdf->MultiCell(0, 6, 'Dengan hormat,');
        $pdf->Ln(3);
        
        // Body
        $body_text = 'Melalui surat ini kami dari pihak sekolah memberitahukan bahwa anak Bapak/Ibu:';
        $pdf->MultiCell(0, 6, $body_text);
        $pdf->Ln(2);
        
        // Student info
        $pdf->Cell(40, 6, 'Nama', 0, 0);
        $pdf->Cell(5, 6, ':', 0, 0);
        $pdf->Cell(0, 6, $siswa->nama, 0, 1);
        
        $pdf->Cell(40, 6, 'NIS', 0, 0);
        $pdf->Cell(5, 6, ':', 0, 0);
        $pdf->Cell(0, 6, $siswa->nis, 0, 1);
        
        $pdf->Cell(40, 6, 'Kelas', 0, 0);
        $pdf->Cell(5, 6, ':', 0, 0);
        $pdf->Cell(0, 6, $siswa->nama_kelas, 0, 1);
        $pdf->Ln(3);
        
        // Problem description
        $pdf->MultiCell(0, 6, 'Memiliki masalah kedisiplinan sebagai berikut:');
        $pdf->Ln(2);
        $pdf->MultiCell(0, 6, $surat->alasan);
        $pdf->Ln(3);
        
        // Meeting request
        $meeting_text = 'Untuk itu kami mengundang Bapak/Ibu untuk hadir pada:';
        $pdf->MultiCell(0, 6, $meeting_text);
        $pdf->Ln(2);
        
        // Extract day name from waktu_panggilan datetime
        $days = array('Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu');
        $day_name = $days[date('l', strtotime($surat->waktu_panggilan))];
        
        $pdf->Cell(40, 6, 'Hari/Tanggal', 0, 0);
        $pdf->Cell(5, 6, ':', 0, 0);
        $pdf->Cell(0, 6, $day_name . ', ' . date('d F Y', strtotime($surat->waktu_panggilan)), 0, 1);
        
        $pdf->Cell(40, 6, 'Waktu', 0, 0);
        $pdf->Cell(5, 6, ':', 0, 0);
        $pdf->Cell(0, 6, date('H:i', strtotime($surat->waktu_panggilan)) . ' WIB', 0, 1);
        
        $pdf->Cell(40, 6, 'Tempat', 0, 0);
        $pdf->Cell(5, 6, ':', 0, 0);
        $pdf->Cell(0, 6, 'Ruang BK ' . $settings->nama_sekolah, 0, 1);
        $pdf->Ln(5);
        
        // Closing
        $pdf->MultiCell(0, 6, 'Demikian surat ini kami sampaikan, atas perhatian dan kehadirannya kami ucapkan terima kasih.');
        $pdf->Ln(10);
        
        // Signature
        $pdf->Cell(100, 6, '', 0, 0);
        $pdf->Cell(0, 6, 'Hormat kami,', 0, 1);
        $pdf->Cell(100, 6, '', 0, 0);
        $pdf->Cell(0, 6, 'Guru BK', 0, 1);
        $pdf->Ln(20);
        $pdf->Cell(100, 6, '', 0, 0);
        $pdf->Cell(0, 6, '_____________________', 0, 1);
        
        // Output
        $filename = 'Surat_Panggilan_' . $siswa->nis . '_' . date('Ymd') . '.pdf';
        $this->pdf_generator->output($filename, 'I');
    }
}
