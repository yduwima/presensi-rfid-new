<?php
/**
 * Minimal FPDF-compatible implementation for simple reports
 */
class FPDF {
    protected $page;
    protected $y;
    protected $x;
    protected $font_family;
    protected $font_style;
    protected $font_size;
    protected $orientation;
    protected $unit;
    protected $format;
    protected $pages;
    protected $current_page;
    protected $page_height;
    protected $page_width;
    protected $left_margin = 10;
    protected $top_margin = 10;
    protected $right_margin = 10;
    protected $auto_page_break = true;
    protected $break_margin = 10;
    
    public function __construct($orientation = 'P', $unit = 'mm', $format = 'A4') {
        $this->orientation = $orientation;
        $this->unit = $unit;
        $this->format = $format;
        $this->pages = [];
        $this->current_page = 0;
        
        // A4 dimensions in mm
        if ($orientation == 'P') {
            $this->page_width = 210;
            $this->page_height = 297;
        } else {
            $this->page_width = 297;
            $this->page_height = 210;
        }
        
        $this->y = $this->top_margin;
        $this->x = $this->left_margin;
    }
    
    public function AddPage($orientation = '') {
        $this->current_page++;
        $this->pages[$this->current_page] = '';
        $this->y = $this->top_margin;
        $this->x = $this->left_margin;
    }
    
    public function SetFont($family, $style = '', $size = 12) {
        $this->font_family = $family;
        $this->font_style = $style;
        $this->font_size = $size;
    }
    
    public function SetAutoPageBreak($auto, $margin = 0) {
        $this->auto_page_break = $auto;
        $this->break_margin = $margin;
    }
    
    public function Cell($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = false) {
        if ($this->current_page == 0) {
            $this->AddPage();
        }
        
        $content = str_repeat(' ', 2) . $txt;
        $this->pages[$this->current_page] .= $content;
        
        if ($ln == 1) {
            $this->pages[$this->current_page] .= "\n";
            $this->y += $h;
            $this->x = $this->left_margin;
        } elseif ($ln == 2) {
            $this->x = $this->left_margin;
        } else {
            $this->x += $w;
        }
        
        // Check for page break
        if ($this->auto_page_break && $this->y > ($this->page_height - $this->break_margin)) {
            $this->AddPage();
        }
    }
    
    public function Ln($h = null) {
        if ($h === null) {
            $h = $this->font_size * 0.35;
        }
        $this->pages[$this->current_page] .= "\n";
        $this->y += $h;
        $this->x = $this->left_margin;
    }
    
    public function GetY() {
        return $this->y;
    }
    
    public function SetY($y) {
        $this->y = $y;
    }
    
    public function Line($x1, $y1, $x2, $y2) {
        if ($this->current_page == 0) {
            $this->AddPage();
        }
        $this->pages[$this->current_page] .= str_repeat('-', 80) . "\n";
    }
    
    public function Image($file, $x, $y, $w = 0, $h = 0) {
        // Image placeholder - not implemented in text mode
        if ($this->current_page == 0) {
            $this->AddPage();
        }
        $this->pages[$this->current_page] .= "[LOGO]\n";
    }
    
    public function Output($dest = 'I', $name = '') {
        $content = '';
        foreach ($this->pages as $page) {
            $content .= $page . "\n\n--- PAGE BREAK ---\n\n";
        }
        
        if ($dest == 'I') {
            // Inline display
            header('Content-Type: text/plain; charset=utf-8');
            echo "==== PDF PREVIEW (Text Mode) ====\n\n";
            echo $content;
            exit;
        } elseif ($dest == 'D') {
            // Download
            header('Content-Type: text/plain; charset=utf-8');
            header('Content-Disposition: attachment; filename="' . $name . '.txt"');
            echo $content;
            exit;
        }
        
        return $content;
    }
}
