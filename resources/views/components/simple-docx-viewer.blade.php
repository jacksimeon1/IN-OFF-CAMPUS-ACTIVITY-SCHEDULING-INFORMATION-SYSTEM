@props(['filename', 'title' => 'Document'])

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - {{ $filename }}</title>
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

        .viewer-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            background: white;
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
        <h1>📄 {{ $title }}</h1>
        <div class="header-actions">
            <a href="{{ route('attachments.view', ['filename' => $filename]) }}?download=1" class="btn btn-primary" download>
                📥 Download
            </a>
            <button onclick="window.close()" class="btn btn-secondary">
                ✕ Close
            </button>
        </div>
    </div>

    <div class="viewer-container">
        <div class="viewer-tabs">
            <button class="tab-btn active" onclick="switchViewer('direct')">
                📄 Direct View
            </button>
            <button class="tab-btn" onclick="switchViewer('iframe')">
                📄 Google Docs Viewer
            </button>
            <button class="tab-btn" onclick="switchViewer('microsoft')">
                📝 Microsoft Office Online
            </button>
            <button class="tab-btn" onclick="switchViewer('download')">
                📥 Download Options
            </button>
        </div>

        <!-- Loading indicator -->
        <div id="loading" class="loading">
            <div class="loading-spinner"></div>
            <p>Loading document...</p>
        </div>

        <!-- Direct iframe viewer -->
        <iframe id="direct-viewer" class="viewer-iframe" style="display: none;"></iframe>

        <!-- Google Docs viewer -->
        <iframe id="iframe-viewer" class="viewer-iframe" style="display: none;"></iframe>

        <!-- Microsoft Office Online Viewer -->
        <iframe id="microsoft-viewer" class="viewer-iframe" style="display: none;"></iframe>

        <!-- Fallback message -->
        <div id="fallback" class="fallback-message" style="display: none;">
            <h3>📄 Document Preview</h3>
            <div class="error-message">
                <strong>Note:</strong> Online document viewers cannot access files hosted on localhost for security reasons.
            </div>
            <p><strong>Filename:</strong> {{ $filename }}</p>
            <p><strong>File Size:</strong> {{ $fileSize ?? 'Unknown' }}</p>
            <p><strong>Why this happens:</strong></p>
            <ul style="text-align: left; margin-bottom: 2rem;">
                <li>Google Docs and Microsoft Office Online require publicly accessible URLs</li>
                <li>Localhost URLs (127.0.0.1) are not accessible from external services</li>
                <li>This is a security feature to protect your local files</li>
            </ul>
            <p><strong>Solutions:</strong></p>
            <ul style="text-align: left; margin-bottom: 2rem;">
                <li><strong>Download</strong> the file and open it in Microsoft Word or Google Docs</li>
                <li><strong>Upload</strong> the file to Google Drive or OneDrive for online viewing</li>
                <li><strong>Deploy</strong> your application to a public server for online viewing</li>
                <li><strong>Use</strong> a desktop application that supports DOCX files</li>
            </ul>
            <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('attachments.view', ['filename' => $filename]) }}?download=1" class="btn btn-primary" download>
                    📥 Download Document
                </a>
                <a href="{{ route('attachments.streamDocx', ['filename' => $filename]) }}" class="btn btn-secondary" target="_blank">
                    🌐 Open in New Tab
                </a>
            </div>
        </div>
    </div>

    <script>
        const filename = '{{ $filename }}';
        const streamUrl = '{{ route('attachments.streamDocx', ['filename' => $filename]) }}';
        const fullStreamUrl = window.location.origin + streamUrl;
        
        // For localhost, create a data URL approach or use direct embedding
        let googleViewerUrl, microsoftViewerUrl;
        
        if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
            // For localhost, online viewers won't work with private URLs
            // We'll show a message explaining this and provide download option
            googleViewerUrl = null;
            microsoftViewerUrl = null;
        } else {
            // For production, use the online viewers
            googleViewerUrl = `https://docs.google.com/gview?embedded=true&url=${encodeURIComponent(fullStreamUrl)}`;
            microsoftViewerUrl = `https://view.officeapps.live.com/op/embed.aspx?src=${encodeURIComponent(fullStreamUrl)}`;
        }

        function switchViewer(viewer) {
            // Update tab buttons
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');

            // Hide all viewers
            document.getElementById('direct-viewer').style.display = 'none';
            document.getElementById('iframe-viewer').style.display = 'none';
            document.getElementById('microsoft-viewer').style.display = 'none';
            document.getElementById('fallback').style.display = 'none';
            document.getElementById('loading').style.display = 'none';

            if (viewer === 'direct') {
                showDirectViewer();
            } else if (viewer === 'iframe') {
                if (googleViewerUrl) {
                    showGoogleViewer();
                } else {
                    showLocalhostMessage();
                }
            } else if (viewer === 'microsoft') {
                if (microsoftViewerUrl) {
                    showMicrosoftViewer();
                } else {
                    showLocalhostMessage();
                }
            } else if (viewer === 'download') {
                showFallback();
            }
        }

        function showDirectViewer() {
            const iframe = document.getElementById('direct-viewer');
            const loading = document.getElementById('loading');
            
            loading.style.display = 'flex';
            loading.querySelector('p').textContent = 'Loading document directly...';
            
            // Try to load the DOCX file directly in iframe
            iframe.src = streamUrl;
            iframe.style.display = 'block';
            
            iframe.onload = function() {
                loading.style.display = 'none';
                // Check if content loaded successfully
                try {
                    if (iframe.contentDocument && iframe.contentDocument.body.innerHTML.trim() !== '') {
                        // Content loaded successfully
                        console.log('Direct view successful');
                    } else {
                        // Fallback to other options
                        loading.style.display = 'flex';
                        loading.querySelector('p').textContent = 'Direct view not supported, trying alternatives...';
                        setTimeout(() => {
                            loading.style.display = 'none';
                            showFallback();
                        }, 2000);
                    }
                } catch (e) {
                    // Cross-origin error, fallback to download
                    loading.style.display = 'none';
                    showFallback();
                }
            };
            
            iframe.onerror = function() {
                loading.style.display = 'none';
                showFallback();
            };

            // Timeout fallback
            setTimeout(() => {
                if (loading.style.display !== 'none') {
                    loading.style.display = 'none';
                    showFallback();
                }
            }, 10000);
        }

        function showLocalhostMessage() {
            const loading = document.getElementById('loading');
            loading.style.display = 'flex';
            loading.querySelector('p').textContent = 'Online viewers cannot access localhost files...';
            
            setTimeout(() => {
                loading.style.display = 'none';
                showFallback();
            }, 2000);
        }

        function showGoogleViewer() {
            const iframe = document.getElementById('iframe-viewer');
            const loading = document.getElementById('loading');
            
            loading.style.display = 'flex';
            loading.querySelector('p').textContent = 'Loading Google Docs viewer...';
            
            iframe.src = googleViewerUrl;
            iframe.style.display = 'block';
            
            iframe.onload = function() {
                loading.style.display = 'none';
            };
            
            iframe.onerror = function() {
                loading.style.display = 'none';
                showMicrosoftViewer();
            };

            setTimeout(() => {
                if (loading.style.display !== 'none') {
                    loading.style.display = 'none';
                    setTimeout(() => {
                        if (iframe.contentDocument && iframe.contentDocument.body.innerHTML.trim() !== '') {
                            // Content loaded successfully
                        } else {
                            showMicrosoftViewer();
                        }
                    }, 5000);
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

            setTimeout(() => {
                if (loading.style.display !== 'none') {
                    loading.style.display = 'none';
                    showFallback();
                }
            }, 20000);
        }

        function showFallback() {
            document.getElementById('fallback').style.display = 'flex';
        }

        // Initialize based on environment
        document.addEventListener('DOMContentLoaded', function() {
            // Hide loading immediately
            document.getElementById('loading').style.display = 'none';
            
            if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
                // For localhost, try direct view first
                showDirectViewer();
            } else {
                // For production, try Google Docs viewer first
                showGoogleViewer();
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
                window.location.href = '{{ route('attachments.view', ['filename' => $filename]) }}?download=1';
            }
        });
    </script>
</body>
</html>
