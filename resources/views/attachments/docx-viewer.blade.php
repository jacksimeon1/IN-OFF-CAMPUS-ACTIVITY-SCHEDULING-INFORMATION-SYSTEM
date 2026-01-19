<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Viewer - {{ $filename }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f5f5f5;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .header {
            background: #fff;
            border-bottom: 1px solid #e0e0e0;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            z-index: 1000;
            position: relative;
        }

        .header h1 {
            font-size: 1.2rem;
            color: #333;
            flex: 1;
        }

        .header-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: #059669;
            color: white;
        }

        .btn-primary:hover {
            background: #047857;
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .btn-secondary:hover {
            background: #4b5563;
        }

        .btn-info {
            background: #3b82f6;
            color: white;
        }

        .btn-info:hover {
            background: #2563eb;
        }

        .viewer-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .viewer-tabs {
            background: #fff;
            border-bottom: 1px solid #e0e0e0;
            padding: 0 2rem;
            display: flex;
            gap: 1rem;
        }

        .tab-btn {
            padding: 0.75rem 1rem;
            border: none;
            background: none;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: all 0.2s;
            font-size: 0.9rem;
        }

        .tab-btn.active {
            border-bottom-color: #059669;
            color: #059669;
            font-weight: 600;
        }

        .tab-btn:hover {
            background: #f9fafb;
        }

        .viewer-content {
            flex: 1;
            position: relative;
            overflow: hidden;
        }

        .viewer-iframe {
            width: 100%;
            height: 100%;
            border: none;
            background: white;
        }

        .loading {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: white;
            z-index: 100;
        }

        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #059669;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 1rem;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .fallback-message {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: white;
            text-align: center;
            padding: 2rem;
            color: #666;
        }

        .fallback-message h3 {
            margin-bottom: 1rem;
            color: #333;
        }

        .fallback-message p {
            margin-bottom: 1rem;
            line-height: 1.6;
        }

        .error-message {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .header {
                padding: 1rem;
                flex-direction: column;
                gap: 1rem;
            }

            .header h1 {
                font-size: 1rem;
                text-align: center;
            }

            .viewer-tabs {
                padding: 0 1rem;
                overflow-x: auto;
            }

            .tab-btn {
                white-space: nowrap;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📄 {{ $filename }}</h1>
        <div class="header-actions">
            <a href="{{ $downloadUrl }}" class="btn btn-primary" download>
                📥 Download
            </a>
            <button onclick="window.close()" class="btn btn-secondary">
                ✕ Close
            </button>
        </div>
    </div>

    <div class="viewer-container">
        <div class="viewer-tabs">
            <button class="tab-btn active" onclick="switchViewer('pdf')">
                📄 PDF Viewer (Converted)
            </button>
            <button class="tab-btn" onclick="switchViewer('microsoft')">
                📝 Microsoft Office Online
            </button>
            <button class="tab-btn" onclick="switchViewer('download')">
                📥 Download Only
            </button>
        </div>

        <div class="viewer-content">
            <!-- Loading indicator -->
            <div id="loading" class="loading">
                <div class="loading-spinner"></div>
                <p>Loading document viewer...</p>
            </div>

            <!-- PDF Viewer (Google Docs converts DOCX to PDF) -->
            <iframe id="pdf-viewer" class="viewer-iframe" style="display: none;"></iframe>

            <!-- Microsoft Office Online Viewer -->
            <iframe id="microsoft-viewer" class="viewer-iframe" style="display: none;"></iframe>

            <!-- Fallback message -->
            <div id="fallback" class="fallback-message" style="display: none;">
                <h3>📄 Document Preview</h3>
                <div class="error-message">
                    <strong>Note:</strong> Online document viewers may not work for files hosted on localhost or private networks.
                </div>
                <p>This DOCX document cannot be displayed directly in the browser using online viewers.</p>
                <p><strong>Filename:</strong> {{ $filename }}</p>
                <p><strong>File Size:</strong> {{ $fileSize ?? 'Unknown' }}</p>
                <p><strong>Suggestions:</strong></p>
                <ul style="text-align: left; margin-bottom: 2rem;">
                    <li>Download the file to view it in Microsoft Word or Google Docs</li>
                    <li>Upload the file to Google Drive or OneDrive for online viewing</li>
                    <li>Use a desktop application that supports DOCX files</li>
                </ul>
                <a href="{{ $downloadUrl }}" class="btn btn-primary" download>
                    📥 Download Document
                </a>
            </div>
        </div>
    </div>

    <script>
        let currentViewer = 'pdf';
        const fileUrl = '{{ $fileUrl }}';
        const publicFileUrl = '{{ $publicFileUrl ?? $fileUrl }}';
        
        // Generate viewer URLs - Use Google Docs to convert DOCX to PDF for viewing
        const googlePdfUrl = `https://docs.google.com/gview?url=${encodeURIComponent(publicFileUrl)}&embedded=true`;
        const microsoftViewerUrl = `https://view.officeapps.live.com/op/embed.aspx?src=${encodeURIComponent(publicFileUrl)}`;

        function switchViewer(viewer) {
            // Update tab buttons
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');

            // Hide all viewers
            document.getElementById('pdf-viewer').style.display = 'none';
            document.getElementById('microsoft-viewer').style.display = 'none';
            document.getElementById('fallback').style.display = 'none';
            document.getElementById('loading').style.display = 'none';

            currentViewer = viewer;

            if (viewer === 'pdf') {
                showPdfViewer();
            } else if (viewer === 'microsoft') {
                showMicrosoftViewer();
            } else if (viewer === 'download') {
                showFallback();
            }
        }

        function showPdfViewer() {
            const iframe = document.getElementById('pdf-viewer');
            const loading = document.getElementById('loading');
            
            loading.style.display = 'flex';
            loading.querySelector('p').textContent = 'Converting DOCX to PDF for viewing...';
            
            // Use Google Docs viewer which automatically converts DOCX to PDF for display
            iframe.src = googlePdfUrl;
            iframe.style.display = 'block';
            
            iframe.onload = function() {
                loading.style.display = 'none';
            };
            
            iframe.onerror = function() {
                loading.style.display = 'none';
                showFallback();
            };

            // Fallback timeout
            setTimeout(() => {
                if (loading.style.display !== 'none') {
                    loading.style.display = 'none';
                }
            }, 15000);
        }

        function showMicrosoftViewer() {
            const iframe = document.getElementById('microsoft-viewer');
            const loading = document.getElementById('loading');
            
            loading.style.display = 'flex';
            loading.querySelector('p').textContent = 'Loading Microsoft Office Online Viewer...';
            
            iframe.src = microsoftViewerUrl;
            iframe.style.display = 'block';
            
            iframe.onload = function() {
                loading.style.display = 'none';
            };
            
            iframe.onerror = function() {
                loading.style.display = 'none';
                showFallback();
            };

            // Fallback timeout
            setTimeout(() => {
                if (loading.style.display !== 'none') {
                    loading.style.display = 'none';
                }
            }, 15000);
        }

        function showFallback() {
            document.getElementById('fallback').style.display = 'flex';
        }

        // Initialize with PDF Viewer (Google Docs conversion)
        document.addEventListener('DOMContentLoaded', function() {
            // Check if we're on localhost (which won't work with online viewers)
            if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
                // Show fallback immediately for localhost
                setTimeout(() => {
                    document.getElementById('loading').style.display = 'none';
                    showFallback();
                }, 1000);
            } else {
                // Try PDF conversion viewer for public URLs
                showPdfViewer();
            }
        });

        // Handle keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Escape key to close
            if (e.key === 'Escape') {
                window.close();
            }
            // Ctrl+D or Cmd+D to download
            if ((e.ctrlKey || e.metaKey) && e.key === 'd') {
                e.preventDefault();
                window.location.href = '{{ $downloadUrl }}';
            }
            // Tab switching
            if (e.key === '1' && (e.ctrlKey || e.metaKey)) {
                e.preventDefault();
                switchViewer('pdf');
            }
            if (e.key === '2' && (e.ctrlKey || e.metaKey)) {
                e.preventDefault();
                switchViewer('microsoft');
            }
        });
    </script>
</body>
</html>