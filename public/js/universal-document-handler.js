/**
 * Universal Document Handler
 * Handles all document viewing and downloading across the entire system
 * Simple, reliable, and works with all file types
 */

(function() {
    'use strict';

    // Main function to view documents
    function viewDocument(url, filename, downloadUrl) {
        console.log('Opening document:', url);
        window.open(url, '_blank');
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
        console.log('Legacy showDocumentPreview called, redirecting to viewDocument');
        viewDocument(url, filename, downloadUrl);
    }

    function showDocxViewer(url, downloadUrl) {
        console.log('Legacy showDocxViewer called, redirecting to viewDocument');
        viewDocument(url, null, downloadUrl);
    }

    // Make functions globally available
    window.viewDocument = viewDocument;
    window.downloadDocument = downloadDocument;
    window.showDocumentPreview = showDocumentPreview;
    window.showDocxViewer = showDocxViewer;

    // Initialize when DOM is ready
    function initialize() {
        console.log('Universal Document Handler initialized');
        
        // Find all existing onclick handlers and replace them
        const links = document.querySelectorAll('a[onclick*="showDocumentPreview"], a[onclick*="showDocxViewer"]');
        links.forEach(link => {
            const onclick = link.getAttribute('onclick');
            if (onclick) {
                // Extract URL from onclick
                const urlMatch = onclick.match(/'([^']+)'/);
                if (urlMatch && urlMatch[1]) {
                    const url = urlMatch[1];
                    link.removeAttribute('onclick');
                    link.href = url;
                    link.target = '_blank';
                    console.log('Fixed link:', link);
                }
            }
        });

        // Also fix button elements
        const buttons = document.querySelectorAll('button[onclick*="showDocumentPreview"], button[onclick*="showDocxViewer"]');
        buttons.forEach(button => {
            const onclick = button.getAttribute('onclick');
            if (onclick) {
                const urlMatch = onclick.match(/'([^']+)'/);
                if (urlMatch && urlMatch[1]) {
                    const url = urlMatch[1];
                    button.removeAttribute('onclick');
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        window.open(url, '_blank');
                    });
                    console.log('Fixed button:', button);
                }
            }
        });
    }

    // Initialize immediately if DOM is ready, otherwise wait
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initialize);
    } else {
        initialize();
    }

    // Also run initialization after a short delay to catch dynamically loaded content
    setTimeout(initialize, 1000);

})();