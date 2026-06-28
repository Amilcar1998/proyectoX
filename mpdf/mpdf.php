<?php
// mPDF legacy compatibility wrapper - PHP 8
// Redirects to vendor mPDF library
require_once dirname(__DIR__) . '/controllers/vendor/autoload.php';
use Mpdf\Mpdf;

class_alias(Mpdf::class, 'mPDF');

if (!function_exists('mPDF')) {
    function mPDF($mode = 'utf-8', $format = 'A4', $default_font_size = 0, $default_font = '', $mgl = 0, $mgr = 0, $mgt = 0, $mgb = 0, $mgh = 0, $mgf = 0, $orientation = 'P') {
        return new Mpdf([
            'mode' => $mode,
            'format' => $format,
            'default_font_size' => $default_font_size,
            'default_font' => $default_font,
            'margin_left' => $mgl,
            'margin_right' => $mgr,
            'margin_top' => $mgt,
            'margin_bottom' => $mgb,
            'margin_header' => $mgh,
            'margin_footer' => $mgf,
            'orientation' => $orientation
        ]);
    }
}