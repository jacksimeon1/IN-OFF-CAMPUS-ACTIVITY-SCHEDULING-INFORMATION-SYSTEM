/**
 * Simple Document Viewer - Direct browser opening and downloads
 * No complex modals, just direct browser functionality
 */

// Simple function to view documents directly in browser
function viewDocument(url, filename, downloadUrl) {
    // Open the document directly in a new browser tab/window
    window.open(url, '_blank');
}

// Simple function for direct downloads
function downloadDocument(downloadUrl, filename) {
    // Create a temporary link element for download
    const link = document.createElement('a');
    link.href = downloadUrl;
    link.download = filename || '';
    link.style.display = 'none';
    
    // Add to DOM, click, and remove
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Backward compatibility functions
window.showDocumentPreview = function(url, type, filename, downloadUrl) {
    viewDocument(url, filename, downloadUrl);
};

window.showDocxViewer = function(url, downloadUrl) {
    viewDocument(url, 'document.docx', downloadUrl);
};

// Make functions globally available
window.viewDocument = viewDocument;
window.downloadDocument = downloadDocument;