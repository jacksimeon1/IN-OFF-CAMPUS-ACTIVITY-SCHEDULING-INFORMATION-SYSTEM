/**
 * Document Viewer - Simple Version
 * This is an alias to the main docx-viewer.js functionality
 */

// Simple function to view documents directly in browser
function viewDocument(url, filename, downloadUrl) {
    console.log('viewDocument called:', url);
    window.open(url, '_blank');
}

// Simple function for direct downloads
function downloadDocument(downloadUrl, filename) {
    console.log('downloadDocument called:', downloadUrl);
    
    const link = document.createElement('a');
    link.href = downloadUrl;
    link.download = filename || '';
    link.style.display = 'none';
    
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Backward compatibility functions
window.showDocumentPreview = function(url, type, filename, downloadUrl) {
    console.log('showDocumentPreview called:', url);
    window.open(url, '_blank');
};

window.showDocxViewer = function(url, downloadUrl) {
    console.log('showDocxViewer called:', url);
    window.open(url, '_blank');
};

// Make functions globally available
window.viewDocument = viewDocument;
window.downloadDocument = downloadDocument;

console.log('Document viewer functions loaded');