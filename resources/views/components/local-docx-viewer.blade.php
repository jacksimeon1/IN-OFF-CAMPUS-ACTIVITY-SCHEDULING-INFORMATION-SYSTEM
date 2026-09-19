@props(['filename', 'title' => 'Document Viewer', 'fileSize' => 'Unknown', 'textContent' => null])

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
            background: white;
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
            overflow: auto;
            padding: 2rem;
        }

        .text-preview {
            background: #fafafa;
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            padding: 2rem;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            line-height: 1.6;
            white-space: pre-wrap;
            word-wrap: break-word;
            max-height: 100%;
            overflow-y: auto;
        }

        .loading {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 200px;
            color: #666;
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
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
        }

        .info-message {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1e40af;
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
        }

        .file-info {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
        }

        .file-info p {
            margin: 0.5rem 0;
            font-size: 14px;
        }

        .file-info strong {
            color: #374151;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
            flex-wrap: wrap;
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

            .viewer-content {
                padding: 1rem;
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
            <button class="tab-btn active" onclick="switchTab('preview')">
                📄 Text Preview
            </button>
            <button class="tab-btn" onclick="switchTab('info')">
                ℹ️ File Info
            </button>
            <button class="tab-btn" onclick="switchTab('help')">
                ❓ Help
            </button>
        </div>

        <div class="viewer-content">
            <!-- Text Preview Tab -->
            <div id="preview-tab" class="tab-content">
                @if($textContent)
                    <div class="info-message">
                        <strong>📋 Text Content Preview:</strong> This is the extracted text from your DOCX file. 
                        Formatting, images, and complex elements are not displayed in this preview.
                    </div>
                    <div class="text-preview">{{ $textContent }}</div>
                @else
                    <div class="error-message">
                        <strong>Unable to extract text content:</strong> The DOCX file could not be processed for text preview.
                    </div>
                    <div class="action-buttons">
                        <a href="{{ route('attachments.view', ['filename' => $filename]) }}?download=1" class="btn btn-primary" download>
                            📥 Download Document
                        </a>
                        <a href="{{ route('attachments.streamDocx', ['filename' => $filename]) }}" class="btn btn-info" target="_blank">
                            🌐 Open in Browser
                        </a>
                    </div>
                @endif
            </div>

            <!-- File Info Tab -->
            <div id="info-tab" class="tab-content" style="display: none;">
                <div class="file-info">
                    <p><strong>Filename:</strong> {{ $filename }}</p>
                    <p><strong>File Size:</strong> {{ $fileSize }}</p>
                    <p><strong>File Type:</strong> Microsoft Word Document (.docx)</p>
                    <p><strong>Preview Type:</strong> Text extraction</p>
                </div>
                
                <div class="info-message">
                    <strong>📖 About this preview:</strong><br>
                    This preview shows the extracted text content from your DOCX file. 
                    Complex formatting, images, tables, and other elements are not included in this text-only preview.
                </div>

                <div class="action-buttons">
                    <a href="{{ route('attachments.view', ['filename' => $filename]) }}?download=1" class="btn btn-primary" download>
                        📥 Download Document
                    </a>
                    <a href="{{ route('attachments.streamDocx', ['filename' => $filename]) }}" class="btn btn-info" target="_blank">
                        🌐 Open in Browser
                    </a>
                </div>
            </div>

            <!-- Help Tab -->
            <div id="help-tab" class="tab-content" style="display: none;">
                <div class="info-message">
                    <strong>🤔 Why can't I view the full document?</strong><br>
                    DOCX files are complex binary formats that require Microsoft Word or compatible software to render properly.
                </div>

                <h3 style="margin: 1.5rem 0 1rem 0; color: #374151;">Available Options:</h3>
                
                <div style="space-y: 1rem;">
                    <div style="background: #f9fafb; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
                        <strong>📥 Download & Open Locally:</strong><br>
                        Download the file and open it in Microsoft Word, LibreOffice Writer, or Google Docs.
                    </div>
                    
                    <div style="background: #f9fafb; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
                        <strong>🌐 Browser Opening:</strong><br>
                        Click "Open in Browser" to try opening the file directly in your browser.
                    </div>
                    
                    <div style="background: #f9fafb; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
                        <strong>📋 Text Preview:</strong><br>
                        View the extracted text content (current tab) to read the document content.
                    </div>
                </div>

                <div class="action-buttons">
                    <a href="{{ route('attachments.view', ['filename' => $filename]) }}?download=1" class="btn btn-primary" download>
                        📥 Download Document
                    </a>
                    <a href="{{ route('attachments.streamDocx', ['filename' => $filename]) }}" class="btn btn-info" target="_blank">
                        🌐 Open in Browser
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function switchTab(tabName) {
            // Update tab buttons
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');

            // Hide all tabs
            document.getElementById('preview-tab').style.display = 'none';
            document.getElementById('info-tab').style.display = 'none';
            document.getElementById('help-tab').style.display = 'none';

            // Show selected tab
            document.getElementById(tabName + '-tab').style.display = 'block';
        }

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
