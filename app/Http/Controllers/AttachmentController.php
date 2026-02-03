<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class AttachmentController extends Controller
{
    /**
     * View any file type (PDF, DOCX, etc.) in the browser
     */
    public function view($filename, Request $request = null)
    {
        // URL decode the filename to handle spaces and special characters
        $decodedFilename = urldecode($filename);
        
        // Try to find the file in different activity directories
        $possiblePaths = [
            'activities/budget/' . $decodedFilename,
            'activities/permits/' . $decodedFilename,
            'activities/documents/' . $decodedFilename,
            'attachments/' . $decodedFilename, // Keep as fallback
            // Also try with the original filename in case it's not URL encoded
            'activities/budget/' . $filename,
            'activities/permits/' . $filename,
            'activities/documents/' . $filename,
            'attachments/' . $filename,
        ];

        $filePath = null;
        foreach ($possiblePaths as $path) {
            $fullPath = Storage::disk('public')->path($path);
            if (file_exists($fullPath)) {
                $filePath = $fullPath;
                break;
            }
        }

        if (!$filePath) {
            abort(404, 'File not found');
        }

        $extension = strtolower(pathinfo($decodedFilename, PATHINFO_EXTENSION));
        $contentType = $this->getContentType($extension);

        // Check if download is forced
        $forceDownload = $request && $request->has('download');
        
        // For DOCX files, always try to display inline unless download is forced
        if ($extension === 'docx' && !$forceDownload) {
            $headers = [
                'Content-Type' => $contentType,
                'Content-Disposition' => 'inline; filename="' . $decodedFilename . '"',
                'Cache-Control' => 'public, max-age=3600',
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Methods' => 'GET, POST, OPTIONS',
                'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
                'X-Content-Type-Options' => 'nosniff'
            ];
        } else {
            $headers = [
                'Content-Type' => $contentType,
                'Content-Disposition' => ($forceDownload ? 'attachment' : 'inline') . '; filename="' . $decodedFilename . '"',
                'Cache-Control' => 'public, max-age=3600',
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Methods' => 'GET, POST, OPTIONS',
                'Access-Control-Allow-Headers' => 'Content-Type, Authorization'
            ];
        }

        return response()->file($filePath, $headers);
    }

    /**
     * Stream a DOCX file as binary for document viewers
     */
    public function streamDocx($filename)
    {
        // URL decode the filename to handle spaces and special characters
        $decodedFilename = urldecode($filename);
        
        // Try to find the file in different activity directories
        $possiblePaths = [
            'activities/budget/' . $decodedFilename,
            'activities/permits/' . $decodedFilename,
            'activities/documents/' . $decodedFilename,
            'attachments/' . $decodedFilename, // Keep as fallback
            // Also try with the original filename in case it's not URL encoded
            'activities/budget/' . $filename,
            'activities/permits/' . $filename,
            'activities/documents/' . $filename,
            'attachments/' . $filename,
        ];

        $filePath = null;
        foreach ($possiblePaths as $path) {
            $fullPath = Storage::disk('public')->path($path);
            if (file_exists($fullPath)) {
                $filePath = $fullPath;
                break;
            }
        }

        if (!$filePath) {
            abort(404, 'File not found');
        }

        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'Content-Disposition' => 'inline; filename="' . $decodedFilename . '"',
            'Content-Length' => filesize($filePath),
            'Cache-Control' => 'public, max-age=3600',
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, POST, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization'
        ];

        return response()->file($filePath, $headers);
    }

    /**
     * Download any file type
     */
    public function download($type, $filename)
    {
        // Map type to directory
        $directory = match($type) {
            'budget' => 'activities/budget/',
            'permits' => 'activities/permits/',
            'documents' => 'activities/documents/',
            default => 'attachments/'
        };

        $filePath = Storage::disk('public')->path($directory . $filename);
        
        // If not found in the expected directory, try other locations
        if (!file_exists($filePath)) {
            $possiblePaths = [
                'activities/budget/' . $filename,
                'activities/permits/' . $filename,
                'activities/documents/' . $filename,
                'attachments/' . $filename,
            ];

            $filePath = null;
            foreach ($possiblePaths as $path) {
                $fullPath = Storage::disk('public')->path($path);
                if (file_exists($fullPath)) {
                    $filePath = $fullPath;
                    break;
                }
            }

            if (!$filePath) {
                abort(404, 'File not found');
            }
        }

        // Get original filename if possible
        $originalName = $filename;
        
        // Set appropriate filename based on type
        switch ($type) {
            case 'budget':
                $originalName = 'Letter_of_Request_' . $filename;
                break;
            case 'permits':
                $originalName = 'Program_of_Activities_' . $filename;
                break;
            case 'documents':
                $originalName = 'Supporting_Documents_' . $filename;
                break;
        }

        return response()->download($filePath, $originalName);
    }

    /**
     * Show DOCX viewer page
     */
    public function showDocxViewer($filename)
    {
        // URL decode the filename to handle spaces and special characters
        $decodedFilename = urldecode($filename);
        
        // Try to find the file in different activity directories
        $possiblePaths = [
            'activities/budget/' . $decodedFilename,
            'activities/permits/' . $decodedFilename,
            'activities/documents/' . $decodedFilename,
            'attachments/' . $decodedFilename, // Keep as fallback
            // Also try with the original filename in case it's not URL encoded
            'activities/budget/' . $filename,
            'activities/permits/' . $filename,
            'activities/documents/' . $filename,
            'attachments/' . $filename,
        ];

        $filePath = null;
        foreach ($possiblePaths as $path) {
            $fullPath = Storage::disk('public')->path($path);
            if (file_exists($fullPath)) {
                $filePath = $fullPath;
                break;
            }
        }

        if (!$filePath) {
            abort(404, 'File not found');
        }

        $fileSize = $this->formatFileSize(filesize($filePath));

        return view('components.simple-docx-viewer', [
            'filename' => $decodedFilename,
            'title' => 'Document Viewer',
            'fileSize' => $fileSize,
        ]);
    }

    /**
     * Convert DOCX to PDF using LibreOffice (if available)
     */
    private function convertDocxToPdf($docxPath, $filename)
    {
        try {
            // Create PDF directory if it doesn't exist
            $pdfDir = Storage::disk('public')->path('pdf_cache');
            if (!file_exists($pdfDir)) {
                mkdir($pdfDir, 0755, true);
            }

            // Generate PDF filename
            $pdfFilename = pathinfo($filename, PATHINFO_FILENAME) . '.pdf';
            $pdfPath = $pdfDir . DIRECTORY_SEPARATOR . $pdfFilename;

            // Check if PDF already exists and is newer than DOCX
            if (file_exists($pdfPath) && filemtime($pdfPath) > filemtime($docxPath)) {
                return $pdfPath;
            }

            // Try to convert using LibreOffice (if available)
            $libreOfficePath = $this->findLibreOffice();
            if ($libreOfficePath) {
                $command = sprintf(
                    '"%s" --headless --convert-to pdf --outdir "%s" "%s" 2>&1',
                    $libreOfficePath,
                    $pdfDir,
                    $docxPath
                );

                $output = [];
                $returnCode = 0;
                exec($command, $output, $returnCode);

                if ($returnCode === 0 && file_exists($pdfPath)) {
                    return $pdfPath;
                }
            }

            // If LibreOffice conversion failed, try online conversion service
            return $this->convertDocxToPdfOnline($docxPath, $pdfPath);

        } catch (Exception $e) {
            \Log::error('DOCX to PDF conversion failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Find LibreOffice installation
     */
    private function findLibreOffice()
    {
        $possiblePaths = [
            'C:\Program Files\LibreOffice\program\soffice.exe',
            'C:\Program Files (x86)\LibreOffice\program\soffice.exe',
            '/usr/bin/libreoffice',
            '/usr/local/bin/libreoffice',
            '/opt/libreoffice/program/soffice',
        ];

        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }

        // Try to find in PATH
        $command = PHP_OS_FAMILY === 'Windows' ? 'where soffice' : 'which libreoffice';
        $output = shell_exec($command);
        if ($output && trim($output)) {
            $path = trim(explode("\n", $output)[0]);
            if (file_exists($path)) {
                return $path;
            }
        }

        return null;
    }

    /**
     * Convert DOCX to PDF using online service (fallback)
     */
    private function convertDocxToPdfOnline($docxPath, $pdfPath)
    {
        // For now, return null - online conversion would require API keys
        // This is a placeholder for future implementation
        return null;
    }

    /**
     * Get public URL for file (for online viewers)
     */
    private function getPublicFileUrl($filename)
    {
        // For localhost/development, we can still try to use the stream URL
        if (request()->getHost() === 'localhost' || request()->getHost() === '127.0.0.1') {
            // Use the stream URL which serves the file with proper headers
            return route('attachments.streamDocx', ['filename' => $filename]);
        }

        // For production, generate the full public URL
        return request()->getSchemeAndHttpHost() . route('attachments.streamDocx', ['filename' => $filename], false);
    }

    /**
     * Format file size in human readable format
     */
    private function formatFileSize($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    /**
     * Get file information (for debugging)
     */
    public function info($filename)
    {
        // URL decode the filename to handle spaces and special characters
        $decodedFilename = urldecode($filename);
        
        // Try to find the file in different activity directories
        $possiblePaths = [
            'activities/budget/' . $decodedFilename,
            'activities/permits/' . $decodedFilename,
            'activities/documents/' . $decodedFilename,
            'attachments/' . $decodedFilename, // Keep as fallback
            // Also try with the original filename in case it's not URL encoded
            'activities/budget/' . $filename,
            'activities/permits/' . $filename,
            'activities/documents/' . $filename,
            'attachments/' . $filename,
        ];

        $filePath = null;
        $relativePath = null;
        foreach ($possiblePaths as $path) {
            $fullPath = Storage::disk('public')->path($path);
            if (file_exists($fullPath)) {
                $filePath = $fullPath;
                $relativePath = $path;
                break;
            }
        }

        if (!$filePath) {
            return response()->json(['error' => 'File not found'], 404);
        }

        return response()->json([
            'filename' => $filename,
            'decoded_filename' => $decodedFilename,
            'path' => $filePath,
            'relative_path' => $relativePath ?? 'unknown',
            'size' => filesize($filePath),
            'extension' => pathinfo($decodedFilename, PATHINFO_EXTENSION),
            'mime_type' => $this->getContentType(strtolower(pathinfo($decodedFilename, PATHINFO_EXTENSION))),
            'exists' => file_exists($filePath),
            'readable' => is_readable($filePath),
            'url' => route('attachments.view', ['filename' => $filename])
        ]);
    }

    /**
     * Get the correct content type for a file extension
     */
    private function getContentType($extension)
    {
        return match($extension) {
            'pdf' => 'application/pdf',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'doc' => 'application/msword',
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'txt' => 'text/plain',
            'csv' => 'text/csv',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'xls' => 'application/vnd.ms-excel',
            'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'ppt' => 'application/vnd.ms-powerpoint',
            default => 'application/octet-stream',
        };
    }
}
