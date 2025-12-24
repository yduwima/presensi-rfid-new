<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Simple PDF Generator Library
 * Uses basic FPDF-compatible structure
 */
class Pdf_generator {
    
    protected $CI;
    protected $pdf;
    
    public function __construct()
    {
        $this->CI =& get_instance();
        // Load required libraries
        require_once APPPATH . 'third_party/fpdf/fpdf.php';
    }
    
    /**
     * Create new PDF instance
     */
    public function create($orientation = 'P', $unit = 'mm', $size = 'A4')
    {
        $this->pdf = new FPDF($orientation, $unit, $size);
        $this->pdf->SetAutoPageBreak(true, 10);
        return $this->pdf;
    }
    
    /**
     * Get PDF instance
     */
    public function get_instance()
    {
        return $this->pdf;
    }
    
    /**
     * Add header with school logo and info
     */
    public function add_school_header($settings, $title)
    {
        $this->pdf->SetFont('Arial', 'B', 14);
        
        // Logo if exists
        if (!empty($settings->logo) && file_exists('./assets/uploads/' . $settings->logo)) {
            $this->pdf->Image('./assets/uploads/' . $settings->logo, 10, 10, 30);
        }
        
        // School name
        $this->pdf->Cell(0, 7, strtoupper($settings->nama_sekolah), 0, 1, 'C');
        $this->pdf->SetFont('Arial', '', 10);
        $this->pdf->Cell(0, 5, $settings->alamat_sekolah ?: '', 0, 1, 'C');
        $this->pdf->Cell(0, 5, 'Telp: ' . ($settings->no_telephone ?: '') . ' | Email: ' . ($settings->email ?: ''), 0, 1, 'C');
        
        // Line
        $this->pdf->Ln(2);
        $this->pdf->Line(10, $this->pdf->GetY(), 200, $this->pdf->GetY());
        $this->pdf->Ln(5);
        
        // Title
        $this->pdf->SetFont('Arial', 'B', 12);
        $this->pdf->Cell(0, 7, $title, 0, 1, 'C');
        $this->pdf->Ln(3);
    }
    
    /**
     * Output PDF
     */
    public function output($filename, $dest = 'I')
    {
        return $this->pdf->Output($dest, $filename);
    }
}
