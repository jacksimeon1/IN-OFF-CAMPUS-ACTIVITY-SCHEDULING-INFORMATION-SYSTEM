@props([
    'file' => null,
    'title' => 'Document',
    'type' => 'document',
    'icon' => 'fas fa-file',
    'iconColor' => 'text-blue-600',
    'borderColor' => 'border-blue-200'
])

@if($file)
    @php
        $filename = basename($file);
        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        $viewUrl = route('attachments.view', ['filename' => $filename]);
        $downloadUrl = route('activity.download', ['type' => $type, 'filename' => $filename]);
        
        // Set appropriate icon based on file type
        $fileIcon = match($extension) {
            'pdf' => 'fas fa-file-pdf',
            'docx', 'doc' => 'fas fa-file-word',
            'xlsx', 'xls' => 'fas fa-file-excel',
            'pptx', 'ppt' => 'fas fa-file-powerpoint',
            'jpg', 'jpeg', 'png', 'gif', 'webp' => 'fas fa-file-image',
            'txt' => 'fas fa-file-alt',
            default => 'fas fa-file'
        };
        
        $fileIconColor = match($extension) {
            'pdf' => 'text-red-500',
            'docx', 'doc' => 'text-blue-500',
            'xlsx', 'xls' => 'text-green-500',
            'pptx', 'ppt' => 'text-orange-500',
            'jpg', 'jpeg', 'png', 'gif', 'webp' => 'text-purple-500',
            'txt' => 'text-gray-500',
            default => 'text-gray-500'
        };
    @endphp
    
    <div class="border {{ $borderColor }} rounded-lg p-4 hover:shadow-md transition-shadow">
        <div class="flex items-start space-x-3">
            <div class="flex-shrink-0">
                <i class="{{ $fileIcon }} {{ $fileIconColor }} text-2xl"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 mb-2">{{ $title }}</p>
                <p class="text-xs text-gray-500 mb-3">{{ strtoupper($extension) }} File</p>
                
                <div class="flex flex-wrap gap-2">
                    <a 
                        href="{{ $viewUrl }}" 
                        target="_blank"
                        class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-xs font-medium rounded-md hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1"
                    >
                        <i class="fas fa-eye mr-1"></i>
                        View
                    </a>
                    
                    <a 
                        href="{{ $downloadUrl }}" 
                        class="inline-flex items-center px-3 py-1 bg-green-600 text-white text-xs font-medium rounded-md hover:bg-green-700 transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-1"
                        download
                    >
                        <i class="fas fa-download mr-1"></i>
                        Download
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif