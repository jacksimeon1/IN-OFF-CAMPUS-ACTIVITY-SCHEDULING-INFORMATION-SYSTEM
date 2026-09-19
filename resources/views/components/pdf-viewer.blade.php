@props(['filename', 'pdfUrl', 'title' => 'PDF Viewer', 'fileSize' => 'Unknown'])

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

        .error-message {
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

        .error-message h3 {
            margin-bottom: 1rem;
            color: #333;
        }

        .error-message p {
            margin-bottom: 1rem;
            line-height: 1.6;
        }

        .error-box {
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
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📄 {{ $title }}</h1>
        <div class="header-actions">
            <a href="{{ route('attachments.view', ['filename' => $filename]) }}?download=1" class="btn btn-primary" download>
                📥 Download Original
            </a>
            <button onclick="window.close()" class="btn btn-secondary">
                ✕ Close
            </button>
        </div>
    </div>

    <div class="viewer-container">
        <!-- Loading indicator -->
        <div id="loading" class="loading">
            <div class="loading-spinner"></div>
            <p>Loading PDF document...</p>
        </div>

        <!-- PDF iframe -->
        <iframe id="pdf-viewer" class="viewer-iframe" src="{{ $pdfUrl }}"></iframe>

        <!-- Error message -->
        <div id="error" class="error-message" style="display: none;">
            <h3>📄 Document Preview</h3>
            <div class="error-box">
                <strong>Error:</strong> Unable to display the PDF document.
            </div>
            <p><strong>Original Filename:</strong> {{ $filename }}</p>
            <p><strong>File Size:</strong> {{ $fileSize }}</p>
            <p><strong>Suggestions:</strong></p>
            <ul style="text-align: left; margin-bottom: 2rem;">
                <li>Download the original file to view it in a PDF reader</li>
                <li>Make sure you have a modern browser that supports PDF viewing</li>
                <li>Check if the file was converted correctly from DOCX</li>
            </ul>
            <a href="{{ route('attachments.view', ['filename' => $filename]) }}?download=1" class="btn btn-primary" download>
                📥 Download Original Document
            </a>
        </div>
    </div>

    <script>
        const iframe = document.getElementById('pdf-viewer');
        const loading = document.getElementById('loading');
        const error = document.getElementById('error');

        iframe.onload = function() {
            loading.style.display = 'none';
        };

        iframe.onerror = function() {
            loading.style.display = 'none';
            error.style.display = 'flex';
        };

        // Fallback timeout
        setTimeout(() => {
            if (loading.style.display !== 'none') {
                loading.style.display = 'none';
                // Don't show error immediately, give PDF more time to load
                setTimeout(() => {
                    if (iframe.contentDocument && iframe.contentDocument.body.innerHTML.trim() !== '') {
                        // PDF loaded successfully
                    } else {
                        error.style.display = 'flex';
                    }
                }, 5000);
            }
        }, 15000);

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
