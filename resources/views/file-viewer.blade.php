<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $filename }} - Document Viewer</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
        }
        
        .viewer-container {
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .viewer-header {
            background: linear-gradient(to right, #16a34a, #15803d);
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .viewer-content {
            flex: 1;
            background: #f8f9fa;
            position: relative;
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
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10;
        }
        
        .btn {
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }
        
        .btn-yellow {
            background: #eab308;
            color: white;
        }
        
        .btn-yellow:hover {
            background: #ca8a04;
            color: white;
        }
        
        .btn-green {
            background: #16a34a;
            color: white;
        }
        
        .btn-green:hover {
            background: #15803d;
            color: white;
        }

        /* Prevent any download behaviors */
        a[download]:not(.btn) {
            pointer-events: none !important;
        }
    </style>
</head>
<body>
    <div class="viewer-container">
        <!-- Header -->
        <div class="viewer-header">
            <div class="flex items-center">
                <i class="fas fa-file-alt text-yellow-300 text-xl mr-3"></i>
                <div>
                    <h1 class="text-lg font-bold">{{ $filename }}</h1>
                </div>
            </div>
            <div class="flex space-x-3">
                <button onclick="window.close()" class="btn btn-yellow">
                    <i class="fas fa-times"></i> Close
                </button>
                <a href="{{ $fileUrl }}" download="{{ $filename }}" class="btn btn-green">
                    <i class="fas fa-download"></i> Download
                </a>
            </div>
        </div>

        <!-- Document Viewer -->
        <div class="viewer-content">
            <div id="loading" class="loading-overlay">
                <div class="text-center">
                    <i class="fas fa-spinner fa-spin text-4xl text-green-600 mb-4"></i>
                    <p class="text-gray-600">Loading document...</p>
                </div>
            </div>

            <!-- Simple Document Viewer -->
            <div class="document-viewer h-full">
                <!-- Basic iframe viewer for all files -->
                <iframe
                    src="{{ $fileUrl }}"
                    class="w-full h-full border-0"
                    title="{{ $filename }}"
                    onload="hideLoading()"
                ></iframe>
            </div>
        </div>
    </div>

    <script>
        function hideLoading() {
            const loading = document.getElementById('loading');
            if (loading) {
                loading.style.display = 'none';
            }
        }

        // Simple auto-hide loading after 3 seconds
        setTimeout(hideLoading, 3000);
    </script>
</body>
</html>
