<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class FileController extends Controller
{
    /**
     * View activity file in browser (creates a viewer page)
     */
    public function viewActivityFile($type, $filename)
    {
        // Validate file type
        if (!in_array($type, ['budget', 'permits', 'documents'])) {
            abort(404);
        }

        // Build file path
        $filePath = "activities/{$type}/{$filename}";

        // Check if file exists in public storage
        if (!Storage::disk('public')->exists($filePath)) {
            abort(404);
        }

        // Get file info
        $fileExtension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $fileUrl = Storage::url($filePath);
        $fullFileUrl = url($fileUrl);

        // For PDF files, serve directly to browser
        if ($fileExtension === 'pdf') {
            $fullPath = Storage::disk('public')->path($filePath);
            $mimeType = 'application/pdf';
            
            $headers = [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
                'X-Content-Type-Options' => 'nosniff',
                'Cache-Control' => 'public, max-age=0',
            ];

            return response()->file($fullPath, $headers);
        }

        // For DOCX files, serve directly to browser with proper headers for inline viewing
        if ($fileExtension === 'docx') {
            $fullPath = Storage::disk('public')->path($filePath);
            $mimeType = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
            
            $headers = [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
                'X-Content-Type-Options' => 'nosniff',
                'Cache-Control' => 'public, max-age=0',
            ];

            return response()->file($fullPath, $headers);
        }

        // For image files, serve directly to browser
        if (in_array($fileExtension, ['png', 'jpg', 'jpeg'])) {
            $fullPath = Storage::disk('public')->path($filePath);
            $mimeType = Storage::disk('public')->mimeType($filePath);
            
            $headers = [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
                'X-Content-Type-Options' => 'nosniff',
                'Cache-Control' => 'public, max-age=0',
            ];

            return response()->file($fullPath, $headers);
        }

        // For other files, use the regular viewer
        $viewerData = [
            'filename' => $filename,
            'fileUrl' => $fileUrl,
            'fullFileUrl' => $fullFileUrl,
            'fileExtension' => $fileExtension,
        ];

        return view('file-viewer', $viewerData);
    }

    /**
     * Create unified document viewer for both PDF and DOCX files
     */
    private function createDocxContentViewer($type, $filename, $filePath)
    {
        $fileUrl = Storage::url($filePath);
        $fullFileUrl = url($fileUrl);
        $fileExtension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        $html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . htmlspecialchars($filename) . ' - Document Viewer</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
        }
        .viewer-container {
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .viewer-header {
            background: #2b579a;
            color: white;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .file-icon {
            width: 24px;
            height: 24px;
            background: #ff6c37;
            border-radius: 3px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 12px;
            color: white;
        }
        .document-title {
            font-size: 14px;
            font-weight: 500;
        }
        .close-btn {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
            padding: 6px 12px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
        }
        .close-btn:hover {
            background: rgba(255,255,255,0.2);
        }
        .viewer-content {
            flex: 1;
            position: relative;
            background: white;
            overflow: hidden;
        }
        .document-frame {
            width: 100%;
            height: 100%;
            border: none;
            background: white;
        }


        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }
        .loading-content {
            text-align: center;
            color: #666;
        }
        .spinner {
            width: 32px;
            height: 32px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #2b579a;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 16px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .error-message {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: white;
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 20;
        }
        .error-content {
            text-align: center;
            max-width: 400px;
            padding: 40px;
        }
        .error-icon {
            font-size: 48px;
            color: #dc3545;
            margin-bottom: 20px;
        }
        .btn {
            background: #2b579a;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin: 5px;
            text-decoration: none;
            display: inline-block;
        }
        .btn:hover {
            background: #1e3f73;
        }

    </style>
</head>
<body>
    <div class="viewer-container">
        <div class="viewer-header">
            <div class="header-info">
                <div class="file-icon">' . strtoupper(substr($fileExtension, 0, 1)) . '</div>
                <div class="document-title">' . htmlspecialchars($filename) . '</div>
            </div>
            <button onclick="window.close()" class="close-btn">✕ Close</button>
        </div>

        <div class="viewer-content">
            <div id="loading" class="loading-overlay">
                <div class="loading-content">
                    <div class="spinner"></div>
                    <h3>Loading Document</h3>
                    <p>Please wait while we load your document...</p>
                </div>
            </div>';

        // Different content based on file type
        if ($fileExtension === 'pdf') {
            $html .= '
            <div id="pdfViewerContainer" style="width: 100%; height: 100%; background: white; display: flex; align-items: center; justify-content: center;">
                <div style="max-width: 600px; padding: 40px; text-align: center;">
                    <div style="font-size: 64px; margin-bottom: 20px;">📄</div>
                    <h3 style="color: #333; margin-bottom: 15px; font-size: 24px;">' . htmlspecialchars($filename) . '</h3>
                    <p style="color: #666; margin-bottom: 30px; font-size: 16px;">PDF Document</p>

                    <div style="display: flex; flex-direction: column; gap: 15px; align-items: center; margin-bottom: 30px;">
                        <a href="' . route('activity.download', ['type' => $type, 'filename' => basename($filePath)]) . '"
                           style="background: #dc3545; color: white; padding: 15px 30px; text-decoration: none; border-radius: 8px; display: inline-block; font-size: 16px; font-weight: 500; min-width: 200px;"
                           onclick="hideLoading()">
                            📥 Download
                        </a>

                        <a href="' . $fileUrl . '"
                           target="_blank"
                           style="background: #28a745; color: white; padding: 15px 30px; text-decoration: none; border-radius: 8px; display: inline-block; font-size: 16px; font-weight: 500; min-width: 200px;"
                           onclick="hideLoading()">
                            🔗 Open in New Tab
                        </a>
                    </div>

                    <div style="background: #e3f2fd; padding: 20px; border-radius: 8px; text-align: left; border-left: 4px solid #2196f3;">
                        <h4 style="margin: 0 0 10px 0; color: #1976d2; font-size: 16px;">💡 How to View:</h4>
                        <ul style="margin: 0; padding-left: 20px; color: #424242; line-height: 1.6;">
                            <li><strong>Download:</strong> Downloads the PDF file to your computer</li>
                            <li><strong>Open in New Tab:</strong> Opens the PDF directly in a new browser tab</li>
                        </ul>
                    </div>

                    <div style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 6px; font-size: 14px; color: #6c757d;">
                        <strong>File Info:</strong> ' . number_format(Storage::disk('public')->size($filePath) / 1024, 1) . ' KB • PDF Format
                    </div>
                </div>
            </div>';
        } else {
            // For DOCX files, embed Microsoft Office Online viewer directly
            $encodedFileUrl = urlencode($fullFileUrl);
            $officeViewerUrl = "https://view.officeapps.live.com/op/embed.aspx?src=" . $encodedFileUrl;
            
            $html .= '
            <div id="docxViewerContainer" style="width: 100%; height: 100%; position: relative;">
                <!-- Loading overlay -->
                <div id="loadingOverlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: white; display: flex; align-items: center; justify-content: center; z-index: 10;">
                    <div style="text-align: center;">
                        <div style="width: 40px; height: 40px; border: 4px solid #f3f3f3; border-top: 4px solid #2b579a; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 20px;"></div>
                        <h3 style="color: #333; margin: 0 0 10px 0;">Loading Document</h3>
                        <p style="color: #666; margin: 0;">Please wait while we load your DOCX document...</p>
                    </div>
                </div>
                
                <!-- Office Online embedded viewer -->
                <iframe id="documentFrame" 
                        src="' . $officeViewerUrl . '" 
                        style="width: 100%; height: 100%; border: none; background: white;"
                        onload="hideLoadingOverlay()"
                        onerror="showFallbackContent()">
                </iframe>
                
                <!-- Fallback content if iframe fails -->
                <div id="fallbackContent" style="display: none; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: white; align-items: center; justify-content: center; z-index: 5;">
                    <div style="max-width: 500px; padding: 40px; text-align: center;">
                        <div style="font-size: 48px; margin-bottom: 20px;">⚠️</div>
                        <h3 style="color: #333; margin-bottom: 15px;">Unable to Load Document</h3>
                        <p style="color: #666; margin-bottom: 30px;">The online document viewer is temporarily unavailable.</p>
                        
                        <div style="display: flex; flex-direction: column; gap: 15px; align-items: center;">
                            <a href="https://view.officeapps.live.com/op/view.aspx?src=' . urlencode($fullFileUrl) . '" 
                               target="_blank"
                               style="background: #0078d4; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; display: inline-block; font-size: 14px; font-weight: 500;">
                                🔗 Open in New Tab
                            </a>
                            
                            <a href="' . route('activity.download', ['type' => $type, 'filename' => basename($filePath)]) . '"
                               style="background: #28a745; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; display: inline-block; font-size: 14px; font-weight: 500;">
                                📥 Download File
                            </a>
                        </div>
                    </div>
                </div>
            </div>';
        }

        $html .= '
            <div id="errorMessage" class="error-message">
                <div class="error-content">
                    <div class="error-icon">⚠️</div>
                    <h3>Unable to Load Document</h3>
                    <p>There was an error loading the document.</p>
                    <a href="' . $fileUrl . '" target="_blank" class="btn">Download File</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function hideLoading() {
            console.log("Document loaded successfully");
            const loading = document.getElementById("loading");
            const loadingOverlay = document.getElementById("loadingOverlay");
            
            if (loading) {
                loading.style.display = "none";
            }
            if (loadingOverlay) {
                loadingOverlay.style.display = "none";
            }
        }

        function hideLoadingOverlay() {
            console.log("DOCX document loaded successfully");
            const loadingOverlay = document.getElementById("loadingOverlay");
            if (loadingOverlay) {
                loadingOverlay.style.display = "none";
            }
        }

        function showError() {
            console.log("Error loading document");
            const loading = document.getElementById("loading");
            const loadingOverlay = document.getElementById("loadingOverlay");
            
            if (loading) {
                loading.style.display = "none";
            }
            if (loadingOverlay) {
                loadingOverlay.style.display = "none";
            }
            
            // For DOCX files, show fallback content instead of error message
            const fallbackContent = document.getElementById("fallbackContent");
            if (fallbackContent) {
                fallbackContent.style.display = "flex";
            } else {
                const errorMessage = document.getElementById("errorMessage");
                if (errorMessage) {
                    errorMessage.style.display = "flex";
                }
            }
        }

        function showFallbackContent() {
            console.log("DOCX viewer failed to load, showing fallback");
            const loadingOverlay = document.getElementById("loadingOverlay");
            const fallbackContent = document.getElementById("fallbackContent");
            
            if (loadingOverlay) {
                loadingOverlay.style.display = "none";
            }
            if (fallbackContent) {
                fallbackContent.style.display = "flex";
            }
        }

        // Add error handling for document iframe
        document.addEventListener("DOMContentLoaded", function() {
            const iframe = document.getElementById("documentFrame");

            if (iframe) {
                iframe.addEventListener("error", function() {
                    console.error("Document iframe failed to load");
                    showError();
                });

                iframe.addEventListener("load", function() {
                    // Check if iframe loaded successfully by trying to access its content
                    try {
                        // Cross-origin iframe loaded successfully
                        console.log("Document iframe loaded successfully");
                        hideLoading();
                    } catch (e) {
                        // If we get a cross-origin error, the iframe actually loaded fine
                        console.log("Document iframe loaded (cross-origin)");
                        hideLoading();
                    }
                });

                // Timeout for document loading - give more time for Office Online viewer
                setTimeout(function() {
                    if (document.getElementById("loading").style.display !== "none") {
                        console.warn("Document loading timeout - showing fallback");
                        showError();
                    }
                }, 15000); // Increased timeout to 15 seconds for Office Online viewer
            } else {
                // For non-iframe content, hide loading immediately
                hideLoading();
            }
        });

        // Function to force download
        function forceDownload() {
            const link = document.createElement("a");
            link.href = "' . $fileUrl . '";
            link.download = "' . htmlspecialchars($filename) . '";
            link.style.display = "none";
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            // Show success message
            const button = event.target;
            const originalText = button.textContent;
            button.textContent = "✅ Download Started";
            button.style.background = "#28a745";

            setTimeout(function() {
                button.textContent = originalText;
                button.style.background = "#6c757d";
            }, 3000);
        }

        // Auto-hide loading after 10 seconds
        setTimeout(() => {
            if (document.getElementById("loading").style.display !== "none") {
                showError();
            }
        }, 10000);
    </script>
</body>
</html>';

        return response($html, 200, [
            'Content-Type' => 'text/html; charset=utf-8',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }



    /**
     * Serve activity files for direct access with forced inline viewing
     */
    public function serveActivityFile($type, $filename)
    {
        // Validate file type
        if (!in_array($type, ['budget', 'permits', 'documents'])) {
            abort(404);
        }

        // Build file path
        $filePath = "activities/{$type}/{$filename}";

        // Check if file exists in public storage
        if (!Storage::disk('public')->exists($filePath)) {
            abort(404);
        }

        // Get the full path
        $fullPath = Storage::disk('public')->path($filePath);

        // Get file info
        $mimeType = Storage::disk('public')->mimeType($filePath);
        $fileSize = Storage::disk('public')->size($filePath);
        $fileExtension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        // STRONGEST POSSIBLE HEADERS TO FORCE INLINE DISPLAY
        $headers = [
            'Content-Type' => $this->getProperMimeType($fileExtension, $mimeType),
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'SAMEORIGIN',
            'Cache-Control' => 'public, max-age=0',
            'Pragma' => 'public',
        ];

        // Return file response with strict inline headers
        return response()->file($fullPath, $headers);
    }

    /**
     * Get proper MIME type for different file extensions
     */
    private function getProperMimeType($fileExtension, $defaultMimeType)
    {
        // For DOCX files, use the proper MIME type for inline viewing
        if ($fileExtension === 'docx') {
            return 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
        }

        // For PDF files, ensure proper PDF MIME type
        if ($fileExtension === 'pdf') {
            return 'application/pdf';
        }

        // For image files, use proper image MIME types
        if ($fileExtension === 'png') {
            return 'image/png';
        }

        if (in_array($fileExtension, ['jpg', 'jpeg'])) {
            return 'image/jpeg';
        }

        // For other files, use default
        return $defaultMimeType;
    }





    /**
     * Download activity file
     */
    public function downloadActivityFile($type, $filename)
    {
        // Validate file type
        if (!in_array($type, ['budget', 'permits', 'documents'])) {
            abort(404);
        }

        // Build file path
        $filePath = "activities/{$type}/{$filename}";

        // Check if file exists in public storage
        if (!Storage::disk('public')->exists($filePath)) {
            abort(404);
        }

        // Extract original filename from stored filename
        // Format: timestamp_originalname.ext
        $downloadFilename = $filename;
        if (preg_match('/^\d+_(.+)$/', $filename, $matches)) {
            // If filename follows the new format (timestamp_originalname.ext), use the original name
            $downloadFilename = $matches[1];
        }

        // Return download response with original filename
        return Storage::disk('public')->download($filePath, $downloadFilename);
    }

    /**
     * Upload attachment files
     */
    public function uploadAttachment(Request $request)
    {
        $request->validate([
            'files' => 'required|array|max:10',
            'files.*' => 'required|file|mimes:pdf,docx,png,jpg,jpeg|max:10240', // 10MB max
        ]);

        // Ensure attachments directory exists
        $attachmentsPath = storage_path('app/public/attachments');
        if (!file_exists($attachmentsPath)) {
            mkdir($attachmentsPath, 0755, true);
        }

        $uploadedFiles = [];

        foreach ($request->file('files') as $file) {
            // Generate unique filename
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '_' . uniqid() . '.' . $extension;

            // Store file in attachments directory
            $filePath = $file->storeAs('attachments', $filename, 'public');

            $uploadedFiles[] = [
                'id' => uniqid(),
                'original_name' => $originalName,
                'filename' => $filename,
                'file_path' => $filePath,
                'file_size' => $file->getSize(),
                'file_type' => $extension,
                'url' => Storage::url($filePath),
                'view_url' => route('attachments.view', ['filename' => $filename]),
                'delete_url' => route('attachments.delete', ['filename' => $filename])
            ];
        }

        return response()->json([
            'success' => true,
            'message' => 'Files uploaded successfully',
            'files' => $uploadedFiles
        ]);
    }

    /**
     * View attachment file in browser
     */
    public function viewAttachment($filename)
    {
        // Validate filename to prevent directory traversal
        if (strpos($filename, '..') !== false || strpos($filename, '/') !== false || strpos($filename, '\\') !== false) {
            abort(404);
        }

        // Build file path
        $filePath = "attachments/{$filename}";

        // Check if file exists in public storage
        if (!Storage::disk('public')->exists($filePath)) {
            abort(404);
        }

        // Validate file extension
        $fileExtension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $allowedExtensions = ['pdf', 'docx', 'png', 'jpg', 'jpeg'];

        if (!in_array($fileExtension, $allowedExtensions)) {
            abort(403, 'File type not allowed');
        }

        // Get file info
        $fileUrl = Storage::url($filePath);
        $fullFileUrl = url($fileUrl);

        // For DOCX and PDF files, use unified document viewer
        if (in_array($fileExtension, ['docx', 'pdf'])) {
            return $this->createAttachmentDocumentViewer($filename, $filePath);
        }

        // For images, create image viewer
        if (in_array($fileExtension, ['png', 'jpg', 'jpeg'])) {
            return $this->createImageViewer($filename, $filePath);
        }

        // For other files, redirect to direct file URL
        return redirect($fileUrl);
    }

    /**
     * Delete attachment file
     */
    public function deleteAttachment($filename)
    {
        // Validate filename to prevent directory traversal
        if (strpos($filename, '..') !== false || strpos($filename, '/') !== false || strpos($filename, '\\') !== false) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid filename'
            ], 400);
        }

        // Build file path
        $filePath = "attachments/{$filename}";

        // Check if file exists in public storage
        if (!Storage::disk('public')->exists($filePath)) {
            return response()->json([
                'success' => false,
                'message' => 'File not found'
            ], 404);
        }

        // Validate file extension
        $fileExtension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $allowedExtensions = ['pdf', 'docx', 'png', 'jpg', 'jpeg'];

        if (!in_array($fileExtension, $allowedExtensions)) {
            return response()->json([
                'success' => false,
                'message' => 'File type not allowed'
            ], 403);
        }

        try {
            // Delete the file
            Storage::disk('public')->delete($filePath);

            return response()->json([
                'success' => true,
                'message' => 'File deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete file: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create document viewer for attachments (PDF/DOCX)
     */
    private function createAttachmentDocumentViewer($filename, $filePath)
    {
        $fileUrl = Storage::url($filePath);
        $fullFileUrl = url($fileUrl);
        $fileExtension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        $html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . htmlspecialchars($filename) . ' - Document Viewer</title>
    <script src="https://unpkg.com/mammoth@1.6.0/mammoth.browser.min.js"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
        }
        .viewer-container {
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .header {
            background: #2b579a;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
        }
        .header-actions {
            display: flex;
            gap: 10px;
        }
        .btn {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
            padding: 8px 16px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.2s;
        }
        .btn:hover {
            background: rgba(255,255,255,0.3);
            color: white;
            text-decoration: none;
        }
        .content-area {
            flex: 1;
            position: relative;
            overflow: hidden;
        }
        .document-frame {
            width: 100%;
            height: 100%;
            border: none;
            background: white;
        }
        .document-content {
            width: 100%;
            height: 100%;
            overflow: auto;
            background: white;
            padding: 40px;
            box-sizing: border-box;
        }
        .loading {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255,255,255,0.9);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #2b579a;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 20px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .error-message {
            display: none;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: white;
            justify-content: center;
            align-items: center;
            z-index: 999;
        }
        .error-content {
            text-align: center;
            max-width: 400px;
            padding: 40px;
        }
        .error-icon {
            font-size: 48px;
            margin-bottom: 20px;
        }
        .error-content h3 {
            color: #dc3545;
            margin-bottom: 15px;
        }
        .error-content p {
            color: #666;
            margin-bottom: 20px;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="viewer-container">
        <div class="header">
            <h1>📄 ' . htmlspecialchars($filename) . '</h1>
            <div class="header-actions">
                <a href="' . $fileUrl . '" target="_blank" class="btn">📥 Download</a>
                <a href="javascript:window.close()" class="btn">✕ Close</a>
            </div>
        </div>
        <div class="content-area">
            <div id="loading" class="loading">
                <div class="spinner"></div>
                <p>Loading document...</p>
            </div>';

        // Different content based on file type
        if ($fileExtension === 'pdf') {
            $html .= '
            <iframe
                id="documentFrame"
                src="' . $fileUrl . '#toolbar=1&navpanes=1&scrollbar=1&page=1&view=FitH"
                class="document-frame"
                onload="hideLoading()"
            ></iframe>';
        } else {
            // For DOCX files, use local document viewer
            $html .= '
            <div id="documentContent" class="document-content">
                <!-- DOCX content will be loaded here -->
            </div>';
        }

        $html .= '
            <div id="errorMessage" class="error-message">
                <div class="error-content">
                    <div class="error-icon">⚠️</div>
                    <h3>Unable to Load Document</h3>
                    <p>There was an error loading the document.</p>
                    <a href="' . $fileUrl . '" target="_blank" class="btn">Download File</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function hideLoading() {
            document.getElementById("loading").style.display = "none";
        }

        function showError() {
            document.getElementById("loading").style.display = "none";
            document.getElementById("errorMessage").style.display = "flex";
        }';

        if ($fileExtension === 'docx') {
            $html .= '
        // Load DOCX content
        async function loadDocxContent() {
            try {
                const response = await fetch("' . $fileUrl . '");
                const arrayBuffer = await response.arrayBuffer();

                const result = await mammoth.convertToHtml({arrayBuffer: arrayBuffer});
                document.getElementById("documentContent").innerHTML = result.value;
                hideLoading();
            } catch (error) {
                console.error("Error loading DOCX:", error);
                showError();
            }
        }

        // Load document when page loads
        document.addEventListener("DOMContentLoaded", loadDocxContent);';
        }

        $html .= '

        // Auto-hide loading after 10 seconds
        setTimeout(() => {
            if (document.getElementById("loading").style.display !== "none") {
                showError();
            }
        }, 10000);
    </script>
</body>
</html>';

        return response($html, 200, [
            'Content-Type' => 'text/html; charset=utf-8',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    /**
     * Create image viewer for attachments
     */
    private function createImageViewer($filename, $filePath)
    {
        $fileUrl = Storage::url($filePath);

        $html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . htmlspecialchars($filename) . ' - Image Viewer</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: #000;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }
        .header {
            background: #2b579a;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
        }
        .header-actions {
            display: flex;
            gap: 10px;
        }
        .btn {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
            padding: 8px 16px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.2s;
        }
        .btn:hover {
            background: rgba(255,255,255,0.3);
            color: white;
            text-decoration: none;
        }
        .image-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .image {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.5);
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🖼️ ' . htmlspecialchars($filename) . '</h1>
        <div class="header-actions">
            <a href="' . $fileUrl . '" target="_blank" class="btn">📥 Download</a>
            <a href="javascript:window.close()" class="btn">✕ Close</a>
        </div>
    </div>
    <div class="image-container">
        <img src="' . $fileUrl . '" alt="' . htmlspecialchars($filename) . '" class="image">
    </div>
</body>
</html>';

        return response($html, 200, [
            'Content-Type' => 'text/html; charset=utf-8',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }
}
