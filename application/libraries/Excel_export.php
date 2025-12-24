<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Excel Export Library
 * Handles Excel file generation for reports
 */
class Excel_export {
    
    protected $CI;
    private $data = array();
    private $title = '';
    
    public function __construct() {
        $this->CI =& get_instance();
    }
    
    /**
     * Set report title
     */
    public function set_title($title) {
        $this->title = $title;
    }
    
    /**
     * Set data for export
     */
    public function set_data($data) {
        $this->data = $data;
    }
    
    /**
     * Generate and download Excel file for student attendance
     */
    public function export_absensi_siswa($data, $bulan, $tahun, $kelas_nama = 'Semua Kelas') {
        // Set headers for Excel download
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Laporan_Absensi_Siswa_' . $bulan . '_' . $tahun . '.xls"');
        header('Cache-Control: max-age=0');
        
        // Start output
        echo '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
        echo '<head>';
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        echo '<style>';
        echo 'table { border-collapse: collapse; width: 100%; }';
        echo 'th, td { border: 1px solid #000; padding: 8px; text-align: left; }';
        echo 'th { background-color: #4F46E5; color: white; font-weight: bold; }';
        echo '.title { font-size: 18px; font-weight: bold; text-align: center; margin-bottom: 10px; }';
        echo '.subtitle { font-size: 14px; text-align: center; margin-bottom: 20px; }';
        echo '</style>';
        echo '</head>';
        echo '<body>';
        
        // Title
        $bulan_nama = array(
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        );
        
        echo '<div class="title">LAPORAN ABSENSI SISWA</div>';
        echo '<div class="subtitle">Periode: ' . $bulan_nama[$bulan] . ' ' . $tahun . ' - Kelas: ' . $kelas_nama . '</div>';
        
        // Table
        echo '<table>';
        echo '<thead>';
        echo '<tr>';
        echo '<th>No</th>';
        echo '<th>NIS</th>';
        echo '<th>Nama Siswa</th>';
        echo '<th>Kelas</th>';
        echo '<th>Tanggal</th>';
        echo '<th>Jam Masuk</th>';
        echo '<th>Jam Pulang</th>';
        echo '<th>Keterlambatan</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        
        $no = 1;
        foreach($data as $row) {
            echo '<tr>';
            echo '<td>' . $no++ . '</td>';
            echo '<td>' . $row->nis . '</td>';
            echo '<td>' . $row->nama . '</td>';
            echo '<td>' . $row->nama_kelas . '</td>';
            echo '<td>' . date('d/m/Y', strtotime($row->tanggal)) . '</td>';
            echo '<td>' . ($row->jam_masuk ? $row->jam_masuk : '-') . '</td>';
            echo '<td>' . ($row->jam_pulang ? $row->jam_pulang : '-') . '</td>';
            $keterlambatan = isset($row->menit_keterlambatan) && $row->menit_keterlambatan > 0 ? $row->menit_keterlambatan . ' menit' : 'Tepat Waktu';
            echo '<td>' . $keterlambatan . '</td>';
            echo '</tr>';
        }
        
        echo '</tbody>';
        echo '</table>';
        echo '</body>';
        echo '</html>';
        exit;
    }
    
    /**
     * Generate and download Excel file for teacher attendance
     */
    public function export_absensi_guru($data, $bulan, $tahun) {
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Laporan_Absensi_Guru_' . $bulan . '_' . $tahun . '.xls"');
        header('Cache-Control: max-age=0');
        
        echo '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
        echo '<head>';
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        echo '<style>';
        echo 'table { border-collapse: collapse; width: 100%; }';
        echo 'th, td { border: 1px solid #000; padding: 8px; text-align: left; }';
        echo 'th { background-color: #10B981; color: white; font-weight: bold; }';
        echo '.title { font-size: 18px; font-weight: bold; text-align: center; margin-bottom: 10px; }';
        echo '.subtitle { font-size: 14px; text-align: center; margin-bottom: 20px; }';
        echo '</style>';
        echo '</head>';
        echo '<body>';
        
        $bulan_nama = array(
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        );
        
        echo '<div class="title">LAPORAN ABSENSI GURU DAN STAFF</div>';
        echo '<div class="subtitle">Periode: ' . $bulan_nama[$bulan] . ' ' . $tahun . '</div>';
        
        echo '<table>';
        echo '<thead>';
        echo '<tr>';
        echo '<th>No</th>';
        echo '<th>NIP</th>';
        echo '<th>Nama Guru</th>';
        echo '<th>Tanggal</th>';
        echo '<th>Jam Masuk</th>';
        echo '<th>Jam Pulang</th>';
        echo '<th>Keterlambatan</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        
        $no = 1;
        foreach($data as $row) {
            echo '<tr>';
            echo '<td>' . $no++ . '</td>';
            echo '<td>' . $row->nip . '</td>';
            echo '<td>' . $row->nama . '</td>';
            echo '<td>' . date('d/m/Y', strtotime($row->tanggal)) . '</td>';
            echo '<td>' . ($row->jam_masuk ? $row->jam_masuk : '-') . '</td>';
            echo '<td>' . ($row->jam_pulang ? $row->jam_pulang : '-') . '</td>';
            $keterlambatan = isset($row->menit_keterlambatan) && $row->menit_keterlambatan > 0 ? $row->menit_keterlambatan . ' menit' : 'Tepat Waktu';
            echo '<td>' . $keterlambatan . '</td>';
            echo '</tr>';
        }
        
        echo '</tbody>';
        echo '</table>';
        echo '</body>';
        echo '</html>';
        exit;
    }
    
    /**
     * Generate and download Excel file for journal recap
     */
    public function export_rekap_jurnal($data, $bulan, $tahun, $guru_nama = 'Semua Guru') {
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Rekap_Jurnal_' . $bulan . '_' . $tahun . '.xls"');
        header('Cache-Control: max-age=0');
        
        echo '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
        echo '<head>';
        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        echo '<style>';
        echo 'table { border-collapse: collapse; width: 100%; }';
        echo 'th, td { border: 1px solid #000; padding: 8px; text-align: left; }';
        echo 'th { background-color: #8B5CF6; color: white; font-weight: bold; }';
        echo '.title { font-size: 18px; font-weight: bold; text-align: center; margin-bottom: 10px; }';
        echo '.subtitle { font-size: 14px; text-align: center; margin-bottom: 20px; }';
        echo '</style>';
        echo '</head>';
        echo '<body>';
        
        $bulan_nama = array(
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        );
        
        echo '<div class="title">REKAP JURNAL MENGAJAR</div>';
        echo '<div class="subtitle">Periode: ' . $bulan_nama[$bulan] . ' ' . $tahun . ' - Guru: ' . $guru_nama . '</div>';
        
        echo '<table>';
        echo '<thead>';
        echo '<tr>';
        echo '<th>No</th>';
        echo '<th>Tanggal</th>';
        echo '<th>Guru</th>';
        echo '<th>Mata Pelajaran</th>';
        echo '<th>Kelas</th>';
        echo '<th>Materi Pembelajaran</th>';
        echo '<th>Catatan</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        
        $no = 1;
        foreach($data as $row) {
            echo '<tr>';
            echo '<td>' . $no++ . '</td>';
            echo '<td>' . date('d/m/Y', strtotime($row->tanggal)) . '</td>';
            echo '<td>' . $row->nama_guru . '</td>';
            echo '<td>' . $row->nama_mapel . '</td>';
            echo '<td>' . $row->nama_kelas . '</td>';
            echo '<td>' . $row->materi . '</td>';
            echo '<td>' . ($row->catatan ? $row->catatan : '-') . '</td>';
            echo '</tr>';
        }
        
        echo '</tbody>';
        echo '</table>';
        echo '</body>';
        echo '</html>';
        exit;
    }
}
