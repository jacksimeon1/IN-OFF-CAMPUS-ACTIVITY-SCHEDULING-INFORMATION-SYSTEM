<?php

namespace App\Services;

use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;

class DocxToPdfConverter
{
    /**
     * Convert DOCX to PDF using DOMPDF (works locally)
     */
    public static function convert($docxPath, $outputPath)
    {
        try {
            // Check if required extensions are available
            if (!class_exists('ZipArchive')) {
                \Log::error('ZipArchive class not found - PHP Zip extension is required for DOCX processing');
                return null;
            }

            // Ensure the output directory exists
            $outputDir = dirname($outputPath);
            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0755, true);
            }

            // Load the DOCX file
            $phpWord = IOFactory::load($docxPath);
            
            // Set PDF renderer to DOMPDF
            Settings::setPdfRendererName(Settings::PDF_RENDERER_DOMPDF);
            Settings::setPdfRendererPath(null); // Let it autodetect
            
            // Save as PDF
            $pdfWriter = IOFactory::createWriter($phpWord, 'PDF');
            $pdfWriter->save($outputPath);
            
            return file_exists($outputPath) ? $outputPath : null;
            
        } catch (\Exception $e) {
            \Log::error('DOCX to PDF conversion failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Check if the required dependencies are available
     */
    public static function isAvailable()
    {
        return class_exists('PhpOffice\PhpWord\IOFactory') && 
               class_exists('Dompdf\Dompdf') &&
               class_exists('ZipArchive');
    }
}
