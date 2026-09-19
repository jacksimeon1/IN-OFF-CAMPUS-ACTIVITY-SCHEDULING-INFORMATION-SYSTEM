<?php

namespace App\Services;

use PhpOffice\PhpWord\IOFactory;

class DocxPreviewService
{
    /**
     * Extract text content from DOCX file for preview
     */
    public static function extractText($docxPath)
    {
        try {
            // Check if ZipArchive is available
            if (!class_exists('ZipArchive')) {
                return null;
            }

            $zip = new \ZipArchive();
            if ($zip->open($docxPath) !== TRUE) {
                return null;
            }

            // Read the main document XML
            $xml = $zip->getFromName('word/document.xml');
            $zip->close();

            if (!$xml) {
                return null;
            }

            // Remove XML tags and extract text
            $text = strip_tags($xml);
            
            // Clean up common DOCX XML artifacts
            $text = preg_replace('/\s+/', ' ', $text); // Replace multiple spaces with single space
            $text = preg_replace('/[\x00-\x1F\x7F]/', '', $text); // Remove control characters
            $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
            
            // Limit to reasonable preview length
            if (strlen($text) > 10000) {
                $text = substr($text, 0, 10000) . '...\n\n[Document truncated for preview]';
            }

            return trim($text);
            
        } catch (\Exception $e) {
            \Log::error('DOCX text extraction failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Check if service is available
     */
    public static function isAvailable()
    {
        return class_exists('ZipArchive');
    }
}
