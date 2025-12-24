<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        
        // Check if user is admin
        if ($this->session->userdata('role') != 'admin') {
            redirect('auth/login');
        }
        
        $this->load->model('Siswa_model');
        $this->load->model('Guru_model');
        $this->load->model('Absensi_model');
        $this->load->model('Settings_model');
        $this->load->library('Pdf_generator');
    }

    public function index()
    {
        $data['title'] = 'Laporan';
        
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/laporan/index', $data);
        $this->load->view('templates/admin_footer');
    }
    
    // Laporan Absensi Siswa
    public function absensi_siswa()
    {
        $bulan = $this->input->get('bulan') ?: date('m');
        $tahun = $this->input->get('tahun') ?: date('Y');
        $kelas_id = $this->input->get('kelas_id');
        
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;
        $data['kelas_id'] = $kelas_id;
        
        // Get data
        $this->db->select('absensi_harian.*, siswa.nis, siswa.nama, kelas.nama_kelas');
        $this->db->from('absensi_harian');
        $this->db->join('siswa', 'siswa.id = absensi_harian.user_id', 'left');
        $this->db->join('kelas', 'kelas.id = siswa.kelas_id', 'left');
        $this->db->where('absensi_harian.user_type', 'siswa');
        $this->db->where('MONTH(absensi_harian.tanggal)', $bulan);
        $this->db->where('YEAR(absensi_harian.tanggal)', $tahun);
        
        if ($kelas_id) {
            $this->db->where('siswa.kelas_id', $kelas_id);
        }
        
        $this->db->order_by('absensi_harian.tanggal', 'DESC');
        $this->db->order_by('siswa.nama', 'ASC');
        
        $data['absensi'] = $this->db->get()->result();
        $data['kelas_list'] = $this->db->get('kelas')->result();
        
        $data['title'] = 'Laporan Absensi Siswa';
        
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/laporan/absensi_siswa', $data);
        $this->load->view('templates/admin_footer');
    }
    
    // PDF Absensi Siswa
    public function pdf_absensi_siswa()
    {
        $bulan = $this->input->get('bulan') ?: date('m');
        $tahun = $this->input->get('tahun') ?: date('Y');
        $kelas_id = $this->input->get('kelas_id');
        
        // Get settings
        $settings = $this->Settings_model->get();
        
        // Get data
        $this->db->select('absensi_harian.*, siswa.nis, siswa.nama, kelas.nama_kelas');
        $this->db->from('absensi_harian');
        $this->db->join('siswa', 'siswa.id = absensi_harian.user_id', 'left');
        $this->db->join('kelas', 'kelas.id = siswa.kelas_id', 'left');
        $this->db->where('absensi_harian.user_type', 'siswa');
        $this->db->where('MONTH(absensi_harian.tanggal)', $bulan);
        $this->db->where('YEAR(absensi_harian.tanggal)', $tahun);
        
        if ($kelas_id) {
            $this->db->where('siswa.kelas_id', $kelas_id);
        }
        
        $this->db->order_by('absensi_harian.tanggal', 'DESC');
        $this->db->order_by('siswa.nama', 'ASC');
        
        $absensi = $this->db->get()->result();
        
        // Create PDF
        $pdf = $this->pdf_generator->create();
        $pdf->AddPage();
        
        // Add header
        $bulan_nama = date('F', mktime(0, 0, 0, $bulan, 1));
        $title = 'LAPORAN ABSENSI SISWA - ' . strtoupper($bulan_nama) . ' ' . $tahun;
        $this->pdf_generator->add_school_header($settings, $title);
        
        // Table header
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(10, 7, 'No', 1, 0, 'C');
        $pdf->Cell(30, 7, 'NIS', 1, 0, 'C');
        $pdf->Cell(50, 7, 'Nama', 1, 0, 'C');
        $pdf->Cell(40, 7, 'Kelas', 1, 0, 'C');
        $pdf->Cell(30, 7, 'Tanggal', 1, 0, 'C');
        $pdf->Cell(20, 7, 'Masuk', 1, 0, 'C');
        $pdf->Cell(20, 7, 'Pulang', 1, 1, 'C');
        
        // Table data
        $pdf->SetFont('Arial', '', 9);
        $no = 1;
        foreach ($absensi as $row) {
            $pdf->Cell(10, 6, $no++, 1, 0, 'C');
            $pdf->Cell(30, 6, $row->nis, 1, 0);
            $pdf->Cell(50, 6, substr($row->nama, 0, 30), 1, 0);
            $pdf->Cell(40, 6, $row->nama_kelas, 1, 0);
            $pdf->Cell(30, 6, date('d/m/Y', strtotime($row->tanggal)), 1, 0);
            $pdf->Cell(20, 6, $row->jam_masuk ?: '-', 1, 0, 'C');
            $pdf->Cell(20, 6, $row->jam_pulang ?: '-', 1, 1, 'C');
        }
        
        // Output
        $filename = 'Laporan_Absensi_Siswa_' . $bulan . '_' . $tahun . '.pdf';
        $this->pdf_generator->output($filename, 'I');
    }
    
    // Laporan Absensi Guru
    public function absensi_guru()
    {
        $bulan = $this->input->get('bulan') ?: date('m');
        $tahun = $this->input->get('tahun') ?: date('Y');
        
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;
        
        // Get data
        $this->db->select('absensi_harian.*, guru.nip, guru.nama');
        $this->db->from('absensi_harian');
        $this->db->join('guru', 'guru.id = absensi_harian.user_id', 'left');
        $this->db->where('absensi_harian.user_type', 'guru');
        $this->db->where('MONTH(absensi_harian.tanggal)', $bulan);
        $this->db->where('YEAR(absensi_harian.tanggal)', $tahun);
        $this->db->order_by('absensi_harian.tanggal', 'DESC');
        $this->db->order_by('guru.nama', 'ASC');
        
        $data['absensi'] = $this->db->get()->result();
        
        $data['title'] = 'Laporan Absensi Guru';
        
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/laporan/absensi_guru', $data);
        $this->load->view('templates/admin_footer');
    }
    
    // Rekap Jurnal (Admin view)
    public function rekap_jurnal()
    {
        $bulan = $this->input->get('bulan') ?: date('m');
        $tahun = $this->input->get('tahun') ?: date('Y');
        $guru_id = $this->input->get('guru_id');
        
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;
        $data['guru_id'] = $guru_id;
        
        // Get data
        $this->db->select('jurnal.*, guru.nama as guru_nama, mata_pelajaran.nama as mapel_nama, kelas.nama_kelas');
        $this->db->from('jurnal');
        $this->db->join('jadwal_pelajaran', 'jadwal_pelajaran.id = jurnal.jadwal_id', 'left');
        $this->db->join('guru', 'guru.id = jadwal_pelajaran.guru_id', 'left');
        $this->db->join('mata_pelajaran', 'mata_pelajaran.id = jadwal_pelajaran.mata_pelajaran_id', 'left');
        $this->db->join('kelas', 'kelas.id = jadwal_pelajaran.kelas_id', 'left');
        $this->db->where('MONTH(jurnal.tanggal)', $bulan);
        $this->db->where('YEAR(jurnal.tanggal)', $tahun);
        
        if ($guru_id) {
            $this->db->where('jadwal_pelajaran.guru_id', $guru_id);
        }
        
        $this->db->order_by('jurnal.tanggal', 'DESC');
        
        $data['jurnal'] = $this->db->get()->result();
        $data['guru_list'] = $this->Guru_model->get_all();
        
        $data['title'] = 'Rekap Jurnal Mengajar';
        
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/laporan/rekap_jurnal', $data);
        $this->load->view('templates/admin_footer');
    }
}
