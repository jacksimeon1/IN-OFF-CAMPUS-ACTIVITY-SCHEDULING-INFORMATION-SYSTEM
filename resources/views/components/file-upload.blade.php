@props([
    'id' => 'file-upload',
    'name' => 'files',
    'multiple' => true,
    'accept' => '.pdf,.docx,.png,.jpg,.jpeg',
    'maxSize' => '10MB',
    'maxFiles' => 10,
    'label' => 'Upload Files',
    'description' => 'Drag and drop files here or click to browse. Supports PDF, DOCX, PNG, and JPG files up to 10MB each.'
])

<div class="file-upload-component" data-max-files="{{ $maxFiles }}" data-max-size="{{ $maxSize }}">
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            {{ $label }}
        </label>
        <div class="file-drop-zone border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors cursor-pointer"
             id="{{ $id }}-drop-zone">
            <div class="file-drop-content">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <p class="text-sm text-gray-600 mb-2">{{ $description }}</p>
                <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    Choose Files
                </button>
            </div>
            <input type="file" 
                   id="{{ $id }}" 
                   name="{{ $name }}{{ $multiple ? '[]' : '' }}"
                   {{ $multiple ? 'multiple' : '' }}
                   accept="{{ $accept }}"
                   class="hidden">
        </div>
    </div>

    <!-- File List -->
    <div id="{{ $id }}-file-list" class="file-list space-y-2"></div>

    <!-- Upload Progress -->
    <div id="{{ $id }}-progress" class="upload-progress hidden">
        <div class="bg-gray-200 rounded-full h-2 mb-2">
            <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
        </div>
        <p class="text-sm text-gray-600 text-center">Uploading files...</p>
    </div>

    <!-- Error Messages -->
    <div id="{{ $id }}-errors" class="error-messages hidden">
        <div class="bg-red-50 border border-red-200 rounded-md p-3">
            <div class="flex">
                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Upload Errors</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc pl-5 space-y-1" id="{{ $id }}-error-list"></ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.file-drop-zone.dragover {
    border-color: #3b82f6;
    background-color: #eff6ff;
}

.file-item {
    display: flex;
    items-center: center;
    justify-content: space-between;
    padding: 12px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    background-color: #f9fafb;
}

.file-item.uploading {
    background-color: #fef3c7;
    border-color: #f59e0b;
}

.file-item.uploaded {
    background-color: #d1fae5;
    border-color: #10b981;
}

.file-item.error {
    background-color: #fee2e2;
    border-color: #ef4444;
}

.file-info {
    display: flex;
    align-items: center;
    flex: 1;
}

.file-icon {
    width: 32px;
    height: 32px;
    margin-right: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
    font-size: 14px;
    font-weight: bold;
    color: white;
}

.file-icon.pdf { background-color: #dc2626; }
.file-icon.docx { background-color: #2563eb; }
.file-icon.png, .file-icon.jpg, .file-icon.jpeg { background-color: #059669; }

.file-details h4 {
    margin: 0;
    font-size: 14px;
    font-weight: 500;
    color: #374151;
}

.file-details p {
    margin: 0;
    font-size: 12px;
    color: #6b7280;
}

.file-actions {
    display: flex;
    gap: 8px;
}

.file-action-btn {
    padding: 4px 8px;
    border: 1px solid #d1d5db;
    border-radius: 4px;
    background-color: white;
    color: #374151;
    text-decoration: none;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.2s;
}

.file-action-btn:hover {
    background-color: #f3f4f6;
    text-decoration: none;
}

.file-action-btn.view {
    color: #2563eb;
    border-color: #2563eb;
}

.file-action-btn.delete {
    color: #dc2626;
    border-color: #dc2626;
}

.file-action-btn.view:hover {
    background-color: #dbeafe;
}

.file-action-btn.delete:hover {
    background-color: #fee2e2;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadId = '{{ $id }}';
    const dropZone = document.getElementById(uploadId + '-drop-zone');
    const fileInput = document.getElementById(uploadId);
    const fileList = document.getElementById(uploadId + '-file-list');
    const progressContainer = document.getElementById(uploadId + '-progress');
    const progressBar = progressContainer.querySelector('.bg-blue-600');
    const errorsContainer = document.getElementById(uploadId + '-errors');
    const errorList = document.getElementById(uploadId + '-error-list');
    
    const maxFiles = parseInt(dropZone.closest('.file-upload-component').dataset.maxFiles);
    const maxSize = dropZone.closest('.file-upload-component').dataset.maxSize;
    const maxSizeBytes = parseMaxSize(maxSize);
    
    let uploadedFiles = [];

    function parseMaxSize(sizeStr) {
        const match = sizeStr.match(/^(\d+)(MB|KB|GB)?$/i);
        if (!match) return 10 * 1024 * 1024; // Default 10MB
        
        const size = parseInt(match[1]);
        const unit = (match[2] || 'MB').toUpperCase();
        
        switch(unit) {
            case 'KB': return size * 1024;
            case 'MB': return size * 1024 * 1024;
            case 'GB': return size * 1024 * 1024 * 1024;
            default: return size * 1024 * 1024;
        }
    }

    // Click to upload
    dropZone.addEventListener('click', () => fileInput.click());

    // Drag and drop
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('dragover');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('dragover');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('dragover');
        handleFiles(e.dataTransfer.files);
    });

    // File input change
    fileInput.addEventListener('change', (e) => {
        handleFiles(e.target.files);
    });

    function handleFiles(files) {
        const errors = [];
        const validFiles = [];

        // Check file count
        if (uploadedFiles.length + files.length > maxFiles) {
            errors.push(`Maximum ${maxFiles} files allowed. You have ${uploadedFiles.length} files and trying to add ${files.length} more.`);
        }

        Array.from(files).forEach(file => {
            // Check file type
            const allowedTypes = ['application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/png', 'image/jpeg'];
            const allowedExtensions = ['pdf', 'docx', 'png', 'jpg', 'jpeg'];
            const fileExtension = file.name.split('.').pop().toLowerCase();
            
            if (!allowedTypes.includes(file.type) && !allowedExtensions.includes(fileExtension)) {
                errors.push(`${file.name}: Invalid file type. Only PDF, DOCX, PNG, and JPG files are allowed.`);
                return;
            }

            // Check file size
            if (file.size > maxSizeBytes) {
                errors.push(`${file.name}: File size (${formatFileSize(file.size)}) exceeds maximum allowed size (${maxSize}).`);
                return;
            }

            validFiles.push(file);
        });

        if (errors.length > 0) {
            showErrors(errors);
            return;
        }

        hideErrors();
        uploadFiles(validFiles);
    }

    function uploadFiles(files) {
        if (files.length === 0) return;

        const formData = new FormData();
        files.forEach(file => {
            formData.append('files[]', file);
        });

        // Add CSRF token
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

        // Show progress
        showProgress();

        // Add files to UI immediately
        files.forEach(file => addFileToList(file, 'uploading'));

        fetch('{{ route("attachments.upload") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            hideProgress();
            
            if (data.success) {
                // Update file items with server response
                data.files.forEach((fileData, index) => {
                    updateFileItem(files[index], fileData, 'uploaded');
                    uploadedFiles.push(fileData);
                });
            } else {
                showErrors([data.message || 'Upload failed']);
                // Remove uploading files from UI
                files.forEach(file => removeFileFromList(file));
            }
        })
        .catch(error => {
            hideProgress();
            showErrors(['Upload failed: ' + error.message]);
            // Remove uploading files from UI
            files.forEach(file => removeFileFromList(file));
        });
    }

    function addFileToList(file, status = 'uploading') {
        const fileExtension = file.name.split('.').pop().toLowerCase();
        const fileId = 'file-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);
        
        const fileItem = document.createElement('div');
        fileItem.className = `file-item ${status}`;
        fileItem.id = fileId;
        fileItem.dataset.fileName = file.name;
        
        fileItem.innerHTML = `
            <div class="file-info">
                <div class="file-icon ${fileExtension}">
                    ${getFileIcon(fileExtension)}
                </div>
                <div class="file-details">
                    <h4>${file.name}</h4>
                    <p>${formatFileSize(file.size)}</p>
                </div>
            </div>
            <div class="file-actions">
                ${status === 'uploaded' ? '<a href="#" class="file-action-btn view" target="_blank">👁️ View</a>' : ''}
                <button type="button" class="file-action-btn delete" onclick="removeFile('${fileId}')">🗑️ Delete</button>
            </div>
        `;
        
        fileList.appendChild(fileItem);
        return fileItem;
    }

    function updateFileItem(file, fileData, status) {
        const fileItem = document.querySelector(`[data-file-name="${file.name}"]`);
        if (!fileItem) return;
        
        fileItem.className = `file-item ${status}`;
        
        const actionsDiv = fileItem.querySelector('.file-actions');
        actionsDiv.innerHTML = `
            <a href="${fileData.view_url}" class="file-action-btn view" target="_blank">👁️ View</a>
            <button type="button" class="file-action-btn delete" onclick="removeFile('${fileItem.id}', '${fileData.filename}')">🗑️ Delete</button>
        `;
    }

    function removeFileFromList(file) {
        const fileItem = document.querySelector(`[data-file-name="${file.name}"]`);
        if (fileItem) {
            fileItem.remove();
        }
    }

    window.removeFile = function(fileId, filename = null) {
        const fileItem = document.getElementById(fileId);
        if (!fileItem) return;

        if (filename) {
            // Remove from server
            fetch(`/attachments/${filename}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    fileItem.remove();
                    // Remove from uploadedFiles array
                    uploadedFiles = uploadedFiles.filter(f => f.filename !== filename);
                } else {
                    showErrors([data.message || 'Failed to delete file']);
                }
            })
            .catch(error => {
                showErrors(['Failed to delete file: ' + error.message]);
            });
        } else {
            // Just remove from UI (for files that failed to upload)
            fileItem.remove();
        }
    };

    function getFileIcon(extension) {
        switch(extension) {
            case 'pdf': return 'PDF';
            case 'docx': return 'DOC';
            case 'png':
            case 'jpg':
            case 'jpeg': return 'IMG';
            default: return 'FILE';
        }
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function showProgress() {
        progressContainer.classList.remove('hidden');
        progressBar.style.width = '0%';
        
        // Simulate progress
        let progress = 0;
        const interval = setInterval(() => {
            progress += Math.random() * 30;
            if (progress > 90) progress = 90;
            progressBar.style.width = progress + '%';
        }, 200);
        
        progressContainer.dataset.interval = interval;
    }

    function hideProgress() {
        const interval = progressContainer.dataset.interval;
        if (interval) {
            clearInterval(interval);
        }
        progressBar.style.width = '100%';
        setTimeout(() => {
            progressContainer.classList.add('hidden');
        }, 500);
    }

    function showErrors(errors) {
        errorList.innerHTML = '';
        errors.forEach(error => {
            const li = document.createElement('li');
            li.textContent = error;
            errorList.appendChild(li);
        });
        errorsContainer.classList.remove('hidden');
    }

    function hideErrors() {
        errorsContainer.classList.add('hidden');
    }
});
</script>
