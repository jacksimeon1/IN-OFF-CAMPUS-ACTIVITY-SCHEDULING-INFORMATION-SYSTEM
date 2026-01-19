/**
 * Fix for existing document viewer calls
 * This script overrides the existing functions to work properly
 */

// Override the showDocumentPreview function to open directly in browser
window.showDocumentPreview = function(url, type, filename, downloadUrl) {
    // Simply open the document in a new tab
    window.open(url, '_blank');
};

// Override the showDocxViewer function to open directly in browser
window.showDocxViewer = function(url, downloadUrl) {
    // Simply open the document in a new tab
    window.open(url, '_blank');
};

// Simple view document function
window.viewDocument = function(url, filename, downloadUrl) {
    window.open(url, '_blank');
};

// Simple download function
window.downloadDocument = function(downloadUrl, filename) {
    const link = document.createElement('a');
    link.href = downloadUrl;
    link.download = filename || '';
    link.style.display = 'none';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
};

console.log('Document viewer functions fixed - documents will now open directly in browser');