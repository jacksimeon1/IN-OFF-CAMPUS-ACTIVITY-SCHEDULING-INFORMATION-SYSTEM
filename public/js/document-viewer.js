/**
 * Document Viewer - Enhanced Version
 * Handles different document types with appropriate viewers
 */

// Function to view documents directly in browser
function viewDocument(url, filename, downloadUrl) {
    console.log('viewDocument called:', url);
    window.open(url, '_blank');
}

// Function for direct downloads
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

// Enhanced function for document preview with proper handling
window.showDocumentPreview = function (url, type, filename, downloadUrl) {
    console.log('showDocumentPreview called:', url, 'type:', type);

    // Check if it's a DOCX file
    if (type === 'docx' || url.toLowerCase().includes('.docx')) {
        // Extract filename from URL if not provided
        if (!filename) {
            const urlParts = url.split('/');
            filename = urlParts[urlParts.length - 1];
        }

        // Route to our Mammoth.js DOCX viewer
        const docxViewerUrl = '/attachments/docx-viewer/' + encodeURIComponent(filename);
        console.log('Opening DOCX viewer:', docxViewerUrl);
        window.open(docxViewerUrl, '_blank');
    } else {
        // For other file types, open directly
        window.open(url, '_blank');
    }
};

// Enhanced DOCX viewer function
window.showDocxViewer = function (url, downloadUrl) {
    console.log('showDocxViewer called:', url);

    // Extract filename from URL
    const urlParts = url.split('/');
    const filename = urlParts[urlParts.length - 1];

    // Route to our Mammoth.js DOCX viewer
    const docxViewerUrl = '/attachments/docx-viewer/' + encodeURIComponent(filename);
    console.log('Opening DOCX viewer:', docxViewerUrl);
    window.open(docxViewerUrl, '_blank');
};

// Make functions globally available
window.viewDocument = viewDocument;
window.downloadDocument = downloadDocument;

console.log('Enhanced document viewer functions loaded');