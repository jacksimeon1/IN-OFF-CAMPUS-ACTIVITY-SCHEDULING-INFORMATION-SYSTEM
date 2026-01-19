<!DOCTYPE html>
<html>
<head>
    <title>Document Viewer Test</title>
    <script src="{{ asset('js/docx-viewer.js') }}"></script>
</head>
<body>
    <h1>Document Viewer Test</h1>
    
    <div style="margin: 20px;">
        <h2>Test Links</h2>
        
        <!-- Test with onclick (legacy) -->
        <p>
            <a href="#" onclick="showDocumentPreview('/attachments/view/test.pdf', 'pdf'); return false;">
                Test PDF (Legacy onclick)
            </a>
        </p>
        
        <!-- Test with direct link -->
        <p>
            <a href="/attachments/view/test.pdf" target="_blank">
                Test PDF (Direct link)
            </a>
        </p>
        
        <!-- Test button -->
        <p>
            <button onclick="showDocxViewer('/attachments/view/test.docx')">
                Test DOCX (Button)
            </button>
        </p>
        
        <!-- Test new function -->
        <p>
            <button onclick="viewDocument('/attachments/view/test.pdf', 'test.pdf', '/attachments/download/documents/test.pdf')">
                Test New Function
            </button>
        </p>
    </div>
    
    <script>
        console.log('Test page loaded');
        console.log('showDocumentPreview function:', typeof window.showDocumentPreview);
        console.log('showDocxViewer function:', typeof window.showDocxViewer);
        console.log('viewDocument function:', typeof window.viewDocument);
    </script>
</body>
</html>