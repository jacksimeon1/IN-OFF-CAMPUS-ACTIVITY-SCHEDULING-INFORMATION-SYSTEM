@props(['filename', 'title' => 'Document Viewer', 'fileSize' => 'Unknown'])

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - {{ $filename }}</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #0dcaf0;
            --light-color: #f8f9fa;
            --dark-color: #212529;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .viewer-container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .viewer-header {
            background: linear-gradient(135deg, var(--primary-color), #0056b3);
            color: white;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .viewer-header h1 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .viewer-content {
            height: 70vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .toolbar {
            background: var(--light-color);
            padding: 15px 30px;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
        }

        .toolbar button {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .toolbar button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        .btn-primary-custom {
            background: var(--primary-color);
            color: white;
        }

        .btn-secondary-custom {
            background: var(--secondary-color);
            color: white;
        }

        .btn-success-custom {
            background: var(--success-color);
            color: white;
        }

        .document-viewer {
            flex: 1;
            overflow: auto;
            padding: 30px;
            background: white;
        }

        .document-content {
            max-width: 800px;
            margin: 0 auto;
            font-family: 'Calibri', 'Times New Roman', serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #333;
        }

        .document-content h1, .document-content h2, .document-content h3 {
            color: #2c3e50;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .document-content h1 { font-size: 18pt; }
        .document-content h2 { font-size: 16pt; }
        .document-content h3 { font-size: 14pt; }

        .document-content p {
            margin-bottom: 12px;
            text-align: justify;
        }

        .document-content ul, .document-content ol {
            margin-bottom: 12px;
            padding-left: 30px;
        }

        .document-content li {
            margin-bottom: 6px;
        }

        .document-content table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 15px;
        }

        .document-content th, .document-content td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .document-content th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .loading-spinner {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 400px;
            color: var(--secondary-color);
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 20px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .error-message {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 20px;
            border-radius: 8px;
            margin: 20px;
        }

        .info-message {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
            padding: 20px;
            border-radius: 8px;
            margin: 20px;
        }

        .success-message {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 20px;
            border-radius: 8px;
            margin: 20px;
        }

        @media (max-width: 768px) {
            body {
                padding: 10px;
            }
            
            .viewer-header {
                padding: 15px 20px;
                flex-direction: column;
                gap: 10px;
            }
            
            .viewer-header h1 {
                font-size: 1.2rem;
            }
            
            .toolbar {
                padding: 10px 20px;
            }
            
            .document-viewer {
                padding: 20px;
            }
            
            .toolbar button {
                font-size: 12px;
                padding: 6px 12px;
            }
        }
    </style>
</head>
<body>
    <div class="viewer-container">
        <div class="viewer-header">
            <h1><i class="fas fa-file-word me-2"></i>{{ $title }}</h1>
            <div>
                <span class="badge bg-light text-dark me-2">{{ $filename }}</span>
                <span class="badge bg-info">{{ $fileSize }}</span>
            </div>
        </div>

        <div class="viewer-content">
            <div class="toolbar">
                <button class="btn-primary-custom" onclick="zoomIn()">
                    <i class="fas fa-search-plus"></i> Zoom In
                </button>
                <button class="btn-secondary-custom" onclick="zoomOut()">
                    <i class="fas fa-search-minus"></i> Zoom Out
                </button>
                <button class="btn-secondary-custom" onclick="resetZoom()">
                    <i class="fas fa-compress"></i> Reset
                </button>
                <button class="btn-success-custom" onclick="printDocument()">
                    <i class="fas fa-print"></i> Print
                </button>
                <a href="{{ route('attachments.view', ['filename' => $filename]) }}?download=1" 
                   class="btn-primary-custom" download>
                    <i class="fas fa-download"></i> Download
                </a>
                <button class="btn-secondary-custom" onclick="window.close()">
                    <i class="fas fa-times"></i> Close
                </button>
            </div>

            <div class="document-viewer">
                <div id="loading" class="loading-spinner">
                    <div class="spinner"></div>
                    <h4>Loading Document...</h4>
                    <p class="text-muted">Please wait while we process your DOCX file</p>
                </div>

                <div id="document-content" class="document-content" style="display: none;">
                    <!-- Document content will be loaded here -->
                </div>

                <div id="error-content" style="display: none;">
                    <div class="error-message">
                        <h4><i class="fas fa-exclamation-triangle me-2"></i>Unable to Load Document</h4>
                        <p>We encountered an error while trying to load your DOCX file.</p>
                        <hr>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('attachments.view', ['filename' => $filename]) }}?download=1" 
                               class="btn btn-danger" download>
                                <i class="fas fa-download me-2"></i>Download Document
                            </a>
                            <button class="btn btn-primary" onclick="location.reload()">
                                <i class="fas fa-redo me-2"></i>Try Again
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Mammoth.js for DOCX parsing -->
    <script src="https://unpkg.com/mammoth@1.6.0/mammoth.browser.min.js"></script>
    
    <script>
        let currentZoom = 1;
        const docxUrl = '{{ route('attachments.streamDocx', ['filename' => $filename]) }}';
        
        // Load document when page loads
        document.addEventListener('DOMContentLoaded', function() {
            loadDocument();
        });

        async function loadDocument() {
            const loading = document.getElementById('loading');
            const content = document.getElementById('document-content');
            const error = document.getElementById('error-content');

            try {
                // Fetch the DOCX file
                const response = await fetch(docxUrl);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const arrayBuffer = await response.arrayBuffer();
                
                // Convert DOCX to HTML using Mammoth.js
                const options = {
                    styleMap: [
                        "p[style-name='Heading 1] => h1:fresh",
                        "p[style-name='Heading 2'] => h2:fresh",
                        "p[style-name='Heading 3'] => h3:fresh",
                        "p[style-name='Title'] => h1.title:fresh",
                        "p[style-name='Subtitle'] => h2.subtitle:fresh",
                        "table => table.table",
                        "tr => tr",
                        "td => td",
                        "th => th"
                    ],
                    includeDefaultStyleMap: true,
                    convertImage: mammoth.images.imgElement(function(image) {
                        return image.read("base64").then(function(imageBuffer) {
                            return {
                                src: "data:" + image.contentType + ";base64," + imageBuffer
                            };
                        });
                    })
                };

                const result = await mammoth.convertToHtml({arrayBuffer: arrayBuffer}, options);
                
                // Display the HTML content
                content.innerHTML = result.value;
                
                // Hide loading and show content
                loading.style.display = 'none';
                content.style.display = 'block';
                
                // Show any warnings
                if (result.messages.length > 0) {
                    console.log('Conversion warnings:', result.messages);
                }
                
                console.log('Document loaded successfully with Mammoth.js');

            } catch (error) {
                console.error('Error loading document:', error);
                
                // Show error message
                loading.style.display = 'none';
                error.style.display = 'block';
                
                // Add specific error info
                const errorDiv = error.querySelector('.error-message p');
                if (error.message.includes('HTTP error')) {
                    errorDiv.innerHTML = 'Unable to fetch the document file. Please check if the file exists and try again.';
                } else if (error.message.includes('parse')) {
                    errorDiv.innerHTML = 'The document file appears to be corrupted or in an unsupported format.';
                } else {
                    errorDiv.innerHTML = 'An unexpected error occurred: ' + error.message;
                }
            }
        }

        function zoomIn() {
            currentZoom += 0.1;
            applyZoom();
        }

        function zoomOut() {
            currentZoom -= 0.1;
            if (currentZoom < 0.5) currentZoom = 0.5;
            applyZoom();
        }

        function resetZoom() {
            currentZoom = 1;
            applyZoom();
        }

        function applyZoom() {
            const content = document.getElementById('document-content');
            content.style.transform = `scale(${currentZoom})`;
            content.style.transformOrigin = 'top left';
            content.style.width = currentZoom === 1 ? 'auto' : `${100 / currentZoom}%`;
        }

        function printDocument() {
            const content = document.getElementById('document-content');
            const printWindow = window.open('', '_blank');
            
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>{{ $filename }}</title>
                    <style>
                        body { font-family: 'Calibri', 'Times New Roman', serif; margin: 20px; }
                        h1, h2, h3 { color: #2c3e50; margin-bottom: 15px; }
                        h1 { font-size: 18pt; }
                        h2 { font-size: 16pt; }
                        h3 { font-size: 14pt; }
                        p { margin-bottom: 12px; text-align: justify; }
                        ul, ol { margin-bottom: 12px; padding-left: 30px; }
                        li { margin-bottom: 6px; }
                        table { border-collapse: collapse; width: 100%; margin-bottom: 15px; }
                        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                        th { background-color: #f2f2f2; font-weight: bold; }
                    </style>
                </head>
                <body>
                    ${content.innerHTML}
                </body>
                </html>
            `);
            
            printWindow.document.close();
            printWindow.print();
            printWindow.close();
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey || e.metaKey) {
                switch(e.key) {
                    case '+':
                    case '=':
                        e.preventDefault();
                        zoomIn();
                        break;
                    case '-':
                        e.preventDefault();
                        zoomOut();
                        break;
                    case '0':
                        e.preventDefault();
                        resetZoom();
                        break;
                    case 'p':
                        e.preventDefault();
                        printDocument();
                        break;
                    case 'd':
                        e.preventDefault();
                        window.location.href = '{{ route('attachments.view', ['filename' => $filename]) }}?download=1';
                        break;
                }
            }
            
            if (e.key === 'Escape') {
                window.close();
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            if (currentZoom !== 1) {
                applyZoom();
            }
        });
    </script>
</body>
</html>
