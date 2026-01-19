/**
 * Universal Document Viewer - Enhanced Version
 * Handles all document viewing and downloading across the entire system
 * Now with proper DOCX viewer support
 */

(function() {
    'use strict';

    console.log('Loading Universal Document Viewer...');

    // Extract filename from URL
    function getFilenameFromUrl(url) {
        const parts = url.split('/');
        return parts[parts.length - 1];
    }

    // Check if file is DOCX
    function isDocxFile(filename) {
        return filename.toLowerCase().endsWith('.docx');
    }

    // Main function to view documents
    function viewDocument(url, filename, downloadUrl) {
        console.log('Opening document:', url);
        
        // If no filename provided, try to extract from URL
        if (!filename) {
            filename = getFilenameFromUrl(url);
        }
        
        // For DOCX files, use the dedicated viewer
        if (isDocxFile(filename)) {
            const docxViewerUrl = url.replace('/attachments/view/', '/attachments/docx-viewer/');
            console.log('Opening DOCX viewer:', docxViewerUrl);
            window.open(docxViewerUrl, '_blank');
        } else {
            // For other files (PDF, images, etc.), open directly
            window.open(url, '_blank');
        }
    }

    // Main function to download documents
    function downloadDocument(url, filename) {
        console.log('Downloading document:', url);
        
        // Create temporary link for download
        const link = document.createElement('a');
        link.href = url;
        if (filename) {
            link.download = filename;
        }
        link.style.display = 'none';
        
        // Trigger download
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    // Legacy compatibility functions
    function showDocumentPreview(url, type, filename, downloadUrl) {
        console.log('Global showDocumentPreview called:', { url, type, filename });
        
        // If no filename provided, try to extract from URL
        if (!filename) {
            filename = getFilenameFromUrl(url);
        }
        
        // For DOCX files, use the dedicated viewer
        if (type && type.toLowerCase() === 'docx') {
            const docxViewerUrl = `/attachments/docx-viewer/${filename}`;
            console.log('Opening DOCX viewer from global function:', docxViewerUrl);
            window.open(docxViewerUrl, '_blank');
            return;
        }
        
        // Use the enhanced viewDocument function for other files
        viewDocument(url, filename, downloadUrl);
    }

    function showDocxViewer(url, downloadUrl) {
        console.log('Legacy showDocxViewer called:', url);
        
        const filename = getFilenameFromUrl(url);
        
        // Use the dedicated DOCX viewer
        const docxViewerUrl = url.replace('/attachments/view/', '/attachments/docx-viewer/');
        console.log('Opening DOCX viewer:', docxViewerUrl);
        window.open(docxViewerUrl, '_blank');
    }

    // Make functions globally available
    window.viewDocument = viewDocument;
    window.downloadDocument = downloadDocument;
    window.showDocumentPreview = showDocumentPreview;
    window.showDocxViewer = showDocxViewer;

    // Initialize and fix existing links
    function initialize() {
        console.log('Initializing Universal Document Viewer...');
        
        // Find all existing onclick handlers and replace them
        const links = document.querySelectorAll('a[onclick*="showDocumentPreview"], a[onclick*="showDocxViewer"]');
        console.log('Found', links.length, 'links to fix');
        
        links.forEach(link => {
            const onclick = link.getAttribute('onclick');
            if (onclick) {
                // Extract URL from onclick
                const urlMatch = onclick.match(/'([^']+)'/);
                if (urlMatch && urlMatch[1]) {
                    const url = urlMatch[1];
                    const filename = getFilenameFromUrl(url);
                    
                    link.removeAttribute('onclick');
                    
                    // Set up the click handler
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        viewDocument(url, filename);
                    });
                    
                    console.log('Fixed link:', url);
                }
            }
        });

        // Also fix button elements
        const buttons = document.querySelectorAll('button[onclick*="showDocumentPreview"], button[onclick*="showDocxViewer"]');
        console.log('Found', buttons.length, 'buttons to fix');
        
        buttons.forEach(button => {
            const onclick = button.getAttribute('onclick');
            if (onclick) {
                const urlMatch = onclick.match(/'([^']+)'/);
                if (urlMatch && urlMatch[1]) {
                    const url = urlMatch[1];
                    const filename = getFilenameFromUrl(url);
                    
                    button.removeAttribute('onclick');
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        viewDocument(url, filename);
                    });
                    
                    console.log('Fixed button:', url);
                }
            }
        });

        console.log('Universal Document Viewer initialization complete');
    }

    // Initialize immediately if DOM is ready, otherwise wait
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initialize);
    } else {
        initialize();
    }

    // Also run initialization after a short delay to catch dynamically loaded content
    setTimeout(initialize, 1000);
    setTimeout(initialize, 3000); // Additional delay for slower loading content

    console.log('Universal Document Viewer loaded successfully');

})();