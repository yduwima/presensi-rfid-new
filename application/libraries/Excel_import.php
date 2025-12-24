<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Excel Import Library
 * Handles Excel file reading and validation for bulk import
 */
class Excel_import {
    
    protected $CI;
    protected $errors = [];
    
    public function __construct() {
        $this->CI =& get_instance();
    }
    
    /**
     * Read Excel file and return array of data
     */
    public function read_file($file_path) {
        if (!file_exists($file_path)) {
            return ['success' => false, 'message' => 'File tidak ditemukan'];
        }
        
        // Load PHPExcel or use simple XLSX reader
        // For now, using simpler approach with CSV conversion
        $extension = pathinfo($file_path, PATHINFO_EXTENSION);
        
        if ($extension == 'xlsx' || $extension == 'xls') {
            return $this->read_excel_file($file_path);
        } else if ($extension == 'csv') {
            return $this->read_csv_file($file_path);
        }
        
        return ['success' => false, 'message' => 'Format file tidak didukung. Gunakan XLSX, XLS, atau CSV'];
    }
    
    /**
     * Read CSV file
     */
    private function read_csv_file($file_path) {
        $data = [];
        $handle = fopen($file_path, 'r');
        
        if ($handle) {
            $headers = fgetcsv($handle); // First row as headers
            
            while (($row = fgetcsv($handle)) !== FALSE) {
                if (count($row) == count($headers)) {
                    $data[] = array_combine($headers, $row);
                }
            }
            fclose($handle);
        }
        
        return ['success' => true, 'data' => $data];
    }
    
    /**
     * Read Excel file (XLSX/XLS)
     * Uses simple XML parsing for XLSX
     */
    private function read_excel_file($file_path) {
        // Simple XLSX reader using ZIP and XML
        $data = [];
        
        try {
            $zip = new ZipArchive;
            if ($zip->open($file_path) === TRUE) {
                $sheet = $zip->getFromName('xl/worksheets/sheet1.xml');
                $strings = $zip->getFromName('xl/sharedStrings.xml');
                $zip->close();
                
                if ($sheet && $strings) {
                    $data = $this->parse_xlsx_sheet($sheet, $strings);
                }
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error membaca file Excel: ' . $e->getMessage()];
        }
        
        return ['success' => true, 'data' => $data];
    }
    
    /**
     * Parse XLSX sheet data
     */
    private function parse_xlsx_sheet($sheet_xml, $strings_xml) {
        // Parse shared strings
        $strings_dom = simplexml_load_string($strings_xml);
        $shared_strings = [];
        foreach ($strings_dom->si as $si) {
            $shared_strings[] = (string)$si->t;
        }
        
        // Parse sheet data
        $sheet_dom = simplexml_load_string($sheet_xml);
        $rows = [];
        $headers = [];
        
        $row_index = 0;
        foreach ($sheet_dom->sheetData->row as $row) {
            $col_data = [];
            
            foreach ($row->c as $cell) {
                $value = '';
                $cell_type = (string)$cell['t'];
                
                if ($cell_type == 's') {
                    // Shared string reference
                    $index = (int)$cell->v;
                    $value = $shared_strings[$index] ?? '';
                } else {
                    // Direct value
                    $value = (string)$cell->v;
                }
                
                $col_data[] = $value;
            }
            
            if ($row_index == 0) {
                $headers = $col_data;
            } else {
                if (count($col_data) == count($headers)) {
                    $rows[] = array_combine($headers, $col_data);
                }
            }
            
            $row_index++;
        }
        
        return $rows;
    }
    
    /**
     * Validate siswa (student) data
     */
    public function validate_siswa($data) {
        $this->CI->load->model('Siswa_model');
        $this->CI->load->model('Kelas_model');
        
        $valid_rows = [];
        $errors = [];
        
        foreach ($data as $index => $row) {
            $row_errors = [];
            $row_number = $index + 2; // +2 because row 1 is header, array starts at 0
            
            // Required fields
            if (empty($row['NIS'])) {
                $row_errors[] = 'NIS wajib diisi';
            } else {
                // Check duplicate NIS
                $existing = $this->CI->Siswa_model->get_by_nis($row['NIS']);
                if ($existing) {
                    $row_errors[] = 'NIS sudah terdaftar';
                }
            }
            
            if (empty($row['Nama'])) {
                $row_errors[] = 'Nama wajib diisi';
            }
            
            if (empty($row['Jenis Kelamin']) || !in_array($row['Jenis Kelamin'], ['L', 'P'])) {
                $row_errors[] = 'Jenis Kelamin harus L atau P';
            }
            
            // Validate Kelas ID
            if (!empty($row['Kelas ID'])) {
                $kelas = $this->CI->Kelas_model->get_by_id($row['Kelas ID']);
                if (!$kelas) {
                    $row_errors[] = 'Kelas ID tidak valid';
                }
            }
            
            // Validate date format
            if (!empty($row['Tanggal Lahir'])) {
                $date = DateTime::createFromFormat('Y-m-d', $row['Tanggal Lahir']);
                if (!$date || $date->format('Y-m-d') != $row['Tanggal Lahir']) {
                    $row_errors[] = 'Format tanggal lahir harus YYYY-MM-DD';
                }
            }
            
            if (empty($row_errors)) {
                $valid_rows[] = $row;
            } else {
                $errors[] = [
                    'row' => $row_number,
                    'data' => $row,
                    'errors' => $row_errors
                ];
            }
        }
        
        return [
            'valid' => $valid_rows,
            'errors' => $errors,
            'total' => count($data),
            'valid_count' => count($valid_rows),
            'error_count' => count($errors)
        ];
    }
    
    /**
     * Validate guru (teacher) data
     */
    public function validate_guru($data) {
        $this->CI->load->model('Guru_model');
        
        $valid_rows = [];
        $errors = [];
        
        foreach ($data as $index => $row) {
            $row_errors = [];
            $row_number = $index + 2;
            
            // Required fields
            if (empty($row['NIP'])) {
                $row_errors[] = 'NIP wajib diisi';
            } else {
                // Check duplicate NIP
                $existing = $this->CI->Guru_model->get_by_nip($row['NIP']);
                if ($existing) {
                    $row_errors[] = 'NIP sudah terdaftar';
                }
            }
            
            if (empty($row['Nama'])) {
                $row_errors[] = 'Nama wajib diisi';
            }
            
            // Validate email
            if (!empty($row['Email'])) {
                if (!filter_var($row['Email'], FILTER_VALIDATE_EMAIL)) {
                    $row_errors[] = 'Format email tidak valid';
                }
            }
            
            // Validate boolean fields
            if (isset($row['Wali Kelas']) && !in_array($row['Wali Kelas'], ['0', '1', 0, 1])) {
                $row_errors[] = 'Wali Kelas harus 0 atau 1';
            }
            
            if (isset($row['Piket']) && !in_array($row['Piket'], ['0', '1', 0, 1])) {
                $row_errors[] = 'Piket harus 0 atau 1';
            }
            
            if (isset($row['BK']) && !in_array($row['BK'], ['0', '1', 0, 1])) {
                $row_errors[] = 'BK harus 0 atau 1';
            }
            
            if (empty($row_errors)) {
                $valid_rows[] = $row;
            } else {
                $errors[] = [
                    'row' => $row_number,
                    'data' => $row,
                    'errors' => $row_errors
                ];
            }
        }
        
        return [
            'valid' => $valid_rows,
            'errors' => $errors,
            'total' => count($data),
            'valid_count' => count($valid_rows),
            'error_count' => count($errors)
        ];
    }
    
    /**
     * Generate Excel template for siswa
     */
    public function generate_siswa_template() {
        $headers = [
            'NIS', 'NISN', 'Nama', 'Jenis Kelamin', 'Tempat Lahir', 
            'Tanggal Lahir', 'Alamat', 'No HP Siswa', 'Kelas ID', 
            'Nama Orang Tua', 'No HP Orang Tua', 'RFID UID'
        ];
        
        $sample_data = [
            ['12345', '0012345678', 'Contoh Nama Siswa', 'L', 'Jakarta', 
             '2005-01-15', 'Jl. Contoh No. 123', '081234567890', '1', 
             'Nama Orang Tua', '081234567891', 'RFID12345']
        ];
        
        return $this->generate_csv_template($headers, $sample_data, 'template_siswa.csv');
    }
    
    /**
     * Generate Excel template for guru
     */
    public function generate_guru_template() {
        $headers = [
            'NIP', 'Nama', 'Email', 'No HP', 'Alamat',
            'Wali Kelas', 'Piket', 'BK', 'RFID UID'
        ];
        
        $sample_data = [
            ['198001012020121001', 'Contoh Nama Guru', 'guru@sekolah.com', 
             '081234567890', 'Jl. Contoh No. 456', '1', '0', '0', 'RFIDGURU001']
        ];
        
        return $this->generate_csv_template($headers, $sample_data, 'template_guru.csv');
    }
    
    /**
     * Generate CSV template
     */
    private function generate_csv_template($headers, $sample_data, $filename) {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, $headers);
        
        foreach ($sample_data as $row) {
            fputcsv($output, $row);
        }
        
        fclose($output);
        exit;
    }
}
