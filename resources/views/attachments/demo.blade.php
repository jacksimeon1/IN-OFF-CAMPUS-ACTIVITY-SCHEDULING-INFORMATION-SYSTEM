@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">File Attachment System</h1>
            <p class="text-gray-600">Upload and manage files with support for PDF, DOCX, PNG, and JPG formats.</p>
        </div>

        <!-- Features Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
                <div class="text-3xl mb-3">📄</div>
                <h3 class="font-semibold text-gray-900 mb-2">PDF Support</h3>
                <p class="text-sm text-gray-600">View PDF files directly in browser with full navigation</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
                <div class="text-3xl mb-3">📝</div>
                <h3 class="font-semibold text-gray-900 mb-2">DOCX Support</h3>
                <p class="text-sm text-gray-600">Preview Word documents with proper formatting</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
                <div class="text-3xl mb-3">🖼️</div>
                <h3 class="font-semibold text-gray-900 mb-2">Image Support</h3>
                <p class="text-sm text-gray-600">View PNG and JPG images in optimized viewer</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
                <div class="text-3xl mb-3">🔒</div>
                <h3 class="font-semibold text-gray-900 mb-2">Secure Upload</h3>
                <p class="text-sm text-gray-600">10MB max size with file type validation</p>
            </div>
        </div>

        <!-- File Upload Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Upload Files</h2>
            
            <x-file-upload 
                id="demo-upload"
                name="demo_files"
                :multiple="true"
                accept=".pdf,.docx,.png,.jpg,.jpeg"
                maxSize="10MB"
                :maxFiles="10"
                label="Select Files to Upload"
                description="Drag and drop files here or click to browse. Supports PDF, DOCX, PNG, and JPG files up to 10MB each."
            />
        </div>

        <!-- API Documentation -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">API Endpoints</h2>
            
            <div class="space-y-4">
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center mb-2">
                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded mr-3">POST</span>
                        <code class="text-sm font-mono text-gray-900">/attachments/upload</code>
                    </div>
                    <p class="text-sm text-gray-600 mb-2">Upload one or more files</p>
                    <div class="text-xs text-gray-500">
                        <strong>Parameters:</strong> files[] (array of files)<br>
                        <strong>Validation:</strong> PDF, DOCX, PNG, JPG only, max 10MB each, max 10 files
                    </div>
                </div>

                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center mb-2">
                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded mr-3">GET</span>
                        <code class="text-sm font-mono text-gray-900">/attachments/view/{filename}</code>
                    </div>
                    <p class="text-sm text-gray-600 mb-2">View file in browser (opens in new tab)</p>
                    <div class="text-xs text-gray-500">
                        <strong>Response:</strong> HTML viewer for PDF/DOCX, image viewer for PNG/JPG
                    </div>
                </div>

                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center mb-2">
                        <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded mr-3">DELETE</span>
                        <code class="text-sm font-mono text-gray-900">/attachments/{filename}</code>
                    </div>
                    <p class="text-sm text-gray-600 mb-2">Delete uploaded file</p>
                    <div class="text-xs text-gray-500">
                        <strong>Response:</strong> JSON with success/error message
                    </div>
                </div>
            </div>
        </div>

        <!-- Usage Examples -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Usage Examples</h2>
            
            <div class="space-y-6">
                <!-- Blade Component Usage -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Blade Component</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <pre class="text-sm text-gray-800 overflow-x-auto"><code>&lt;x-file-upload 
    id="my-upload"
    name="attachments"
    :multiple="true"
    accept=".pdf,.docx,.png,.jpg,.jpeg"
    maxSize="10MB"
    :maxFiles="5"
    label="Upload Documents"
    description="Upload your documents here"
/&gt;</code></pre>
                    </div>
                </div>

                <!-- JavaScript Usage -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">JavaScript Upload</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <pre class="text-sm text-gray-800 overflow-x-auto"><code>const formData = new FormData();
formData.append('files[]', file);
formData.append('_token', csrfToken);

fetch('/attachments/upload', {
    method: 'POST',
    body: formData
})
.then(response => response.json())
.then(data => {
    if (data.success) {
        console.log('Files uploaded:', data.files);
    }
});</code></pre>
                    </div>
                </div>

                <!-- File Viewing -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">File Viewing</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <pre class="text-sm text-gray-800 overflow-x-auto"><code>// Open file in new tab
window.open('/attachments/view/' + filename, '_blank');

// Or use direct link
&lt;a href="/attachments/view/FILENAME_HERE" target="_blank"&gt;
    View File
&lt;/a&gt;</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Storage Information -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-8">
            <div class="flex">
                <svg class="h-5 w-5 text-blue-400 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
                <div>
                    <h3 class="text-sm font-medium text-blue-800">Storage Information</h3>
                    <div class="mt-1 text-sm text-blue-700">
                        <p>Files are stored in <code>/storage/app/public/attachments</code> directory.</p>
                        <p>Make sure to run <code>php artisan storage:link</code> to create the symbolic link for public access.</p>
                        <p>Current storage path: <code>{{ storage_path('app/public/attachments') }}</code></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Additional demo functionality can be added here
document.addEventListener('DOMContentLoaded', function() {
    console.log('File attachment system demo loaded');
});
</script>
@endpush
