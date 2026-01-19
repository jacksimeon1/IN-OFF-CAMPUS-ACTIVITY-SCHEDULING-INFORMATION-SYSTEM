@push('scripts')
<script>
// Override the global function to handle DOCX files properly
window.showDocumentPreview = function(url, type) {
    console.log('showDocumentPreview called with:', { url, type });
    
    // Extract filename from URL
    const filename = url.split('/').pop();
    console.log('Extracted filename:', filename);
    
    // For DOCX files, use the dedicated viewer - PREVENT DEFAULT DOWNLOAD
    if (type && type.toLowerCase() === 'docx') {
        console.log('DOCX file detected - opening viewer');
        const docxViewerUrl = `/attachments/docx-viewer/${filename}`;
        console.log('Opening DOCX viewer at:', docxViewerUrl);
        
        // Open in new window/tab
        const newWindow = window.open(docxViewerUrl, '_blank');
        if (!newWindow) {
            alert('Please allow popups for this site to view documents');
        }
        return false; // Prevent any default action
    }
    
    // For other files, use the modal
    const modal = document.getElementById('documentPreviewModal');
    const content = document.getElementById('documentPreviewContent');
    
    if (type === 'pdf') {
        content.innerHTML = `<iframe src="${url}" frameborder="0" style="width:100%;height:640px;"></iframe>`;
    } else if (['jpg', 'jpeg', 'png', 'gif'].includes(type)) {
        content.innerHTML = `<img src="${url}" class="max-w-full h-auto mx-auto" />`;
    }
    
    modal.classList.remove('hidden');
    return false; // Prevent default action
};

function closeDocumentPreview() {
    const modal = document.getElementById('documentPreviewModal');
    modal.classList.add('hidden');
    document.getElementById('documentPreviewContent').innerHTML = '';
}

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDocumentPreview();
    }
});

// Make sure our function is loaded after the page
document.addEventListener('DOMContentLoaded', function() {
    console.log('Document ready - showDocumentPreview function available:', typeof window.showDocumentPreview);
});
</script>
@endpush
@extends('layouts.sidebar')

@section('title', 'Activity Approval')
@section('page-title', 'Activity Approval - ' . auth()->user()->getRoleDisplayName())
@section('page-subtitle', 'Review and approve activity request')

@section('content')
<div class="pb-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Activity Details Card -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-2 border-yellow-500 mb-6">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">{{ $activity->title }}</h2>
                    <div class="flex items-center space-x-3">
                        <span class="px-3 py-1 text-sm font-medium rounded-full {{ $activity->type === 'in-campus' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ ucfirst(str_replace('-', ' ', $activity->type)) }}
                        </span>
                        <span class="px-3 py-1 text-sm font-medium rounded-full bg-yellow-100 text-yellow-800">
                            {{ $activity->getCurrentApprovalStepName() }}
                        </span>
                    </div>
                </div>

                <!-- Document Preview Modal -->
                <div id="documentPreviewModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
                    <div class="flex items-center justify-center min-h-screen p-4">
                        <div class="fixed inset-0 bg-black opacity-50"></div>
                        <div class="relative bg-white rounded-lg w-full max-w-6xl">
                            <div class="flex justify-between items-center p-4 border-b">
                                <h3 class="text-xl font-semibold text-gray-900">Document Preview</h3>
                                <button type="button" onclick="closeDocumentPreview()" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                                    <i class="fas fa-times text-xl"></i>
                                </button>
                            </div>
                            <div class="p-6">
                                <div id="documentPreviewContent" style="min-height: 640px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="mb-6">
                    <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                        <span>Approval Progress</span>
                        <span>{{ $activity->getWorkflowProgress() }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full transition-all duration-300" style="width: {{ $activity->getWorkflowProgress() }}%"></div>
                    </div>
                </div>

                <!-- Activity Information Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Submitted by</label>
                        <p class="text-gray-900">{{ $activity->user->name }}</p>
                        <p class="text-sm text-gray-600">{{ $activity->user->email }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Organization</label>
                        <p class="text-gray-900">{{ $activity->organization }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Activity Date</label>
                        <p class="text-gray-900">{{ $activity->activity_date->format('M d, Y') }}</p>
                        @if($activity->end_date && $activity->end_date != $activity->activity_date)
                            <p class="text-sm text-gray-600">to {{ $activity->end_date->format('M d, Y') }}</p>
                        @endif
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Time</label>
                        <p class="text-gray-900">{{ $activity->start_time->format('g:i A') }} - {{ $activity->end_time->format('g:i A') }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Venue</label>
                        <p class="text-gray-900">{{ $activity->location }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Expected Participants</label>
                        <p class="text-gray-900">{{ number_format($activity->expected_participants) }}</p>
                    </div>
                </div>

                <!-- Objectives -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Objectives</label>
                    <div class="space-y-2">
                        @if($activity->objective_1)
                            <p class="text-gray-900">• {{ $activity->objective_1 }}</p>
                        @endif
                        @if($activity->objective_2)
                            <p class="text-gray-900">• {{ $activity->objective_2 }}</p>
                        @endif
                        @if($activity->objective_3)
                            <p class="text-gray-900">• {{ $activity->objective_3 }}</p>
                        @endif
                    </div>
                </div>

                <!-- Leaders and Speakers -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    @if($activity->leaders)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Leaders/Organizers</label>
                            <p class="text-gray-900 whitespace-pre-line">{{ $activity->leaders }}</p>
                        </div>
                    @endif
                    
                    @if($activity->speakers)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Speakers</label>
                            <p class="text-gray-900 whitespace-pre-line">{{ $activity->speakers }}</p>
                        </div>
                    @endif
                </div>

                <!-- Budget -->
                @if($activity->budget)
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Budget</label>
                        <p class="text-gray-900 text-lg font-semibold">₱{{ number_format($activity->budget, 2) }}</p>
                    </div>
                @endif

                <!-- Attachments -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Attachments</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @if($activity->budget_file)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center space-x-3">
                                    <i class="fas fa-file-pdf text-red-500 text-xl"></i>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Letter of Request</p>
                                        <div class="flex space-x-2 mt-1">
                                            @php
                                                $budgetExt = strtolower(pathinfo($activity->budget_file, PATHINFO_EXTENSION));
                                                $budgetUrl = route('attachments.view', ['filename' => basename($activity->budget_file)]);
                                            @endphp
                                            @if($budgetExt === 'docx')
                                                <a href="{{ route('attachments.docx-viewer', ['filename' => basename($activity->budget_file)]) }}" target="_blank" class="text-xs text-blue-600 hover:text-blue-800">View Document</a>
                                            @else
                                                <a href="#" onclick="showDocumentPreview('{{ $budgetUrl }}', '{{ $budgetExt }}'); return false;" class="text-xs text-blue-600 hover:text-blue-800">View Document</a>
                                            @endif
                                            <a href="{{ route('activity.download', ['type' => 'budget', 'filename' => basename($activity->budget_file)]) }}" class="text-xs text-green-600 hover:text-green-800">Download</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if($activity->permit_file)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center space-x-3">
                                    <i class="fas fa-file-pdf text-red-500 text-xl"></i>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Program of Activities</p>
                                        <div class="flex space-x-2 mt-1">
                                            @php
                                                $permitExt = strtolower(pathinfo($activity->permit_file, PATHINFO_EXTENSION));
                                                $permitUrl = route('attachments.view', ['filename' => basename($activity->permit_file)]);
                                            @endphp
                                            @if($permitExt === 'docx')
                                                <a href="{{ route('attachments.docx-viewer', ['filename' => basename($activity->permit_file)]) }}" target="_blank" class="text-xs text-blue-600 hover:text-blue-800">View Document</a>
                                            @else
                                                <a href="#" onclick="showDocumentPreview('{{ $permitUrl }}', '{{ $permitExt }}'); return false;" class="text-xs text-blue-600 hover:text-blue-800">View Document</a>
                                            @endif
                                            <a href="{{ route('activity.download', ['type' => 'permits', 'filename' => basename($activity->permit_file)]) }}" class="text-xs text-green-600 hover:text-green-800">Download</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if($activity->supporting_documents)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center space-x-3">
                                    <i class="fas fa-file-pdf text-red-500 text-xl"></i>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Supporting Documents</p>
                                        <div class="flex space-x-2 mt-1">
                                            @php
                                                $suppExt = strtolower(pathinfo($activity->supporting_documents, PATHINFO_EXTENSION));
                                                $suppUrl = route('attachments.view', ['filename' => basename($activity->supporting_documents)]);
                                            @endphp
                                            @if($suppExt === 'docx')
                                                <a href="{{ route('attachments.docx-viewer', ['filename' => basename($activity->supporting_documents)]) }}" target="_blank" class="text-xs text-blue-600 hover:text-blue-800">View Document</a>
                                            @else
                                                <a href="#" onclick="showDocumentPreview('{{ $suppUrl }}', '{{ $suppExt }}'); return false;" class="text-xs text-blue-600 hover:text-blue-800">View Document</a>
                                            @endif
                                            <a href="{{ route('activity.download', ['type' => 'documents', 'filename' => basename($activity->supporting_documents)]) }}" class="text-xs text-green-600 hover:text-green-800">Download</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Approval Form -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-2 border-green-500">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-clipboard-check text-green-600 mr-2"></i>
                    {{ auth()->user()->getRoleDisplayName() }} Decision
                </h3>

                <form method="POST" action="{{ route('workflow.approval.process', $activity) }}" class="space-y-6">
                    @csrf
                    
                    <!-- Comments -->
                    <div>
                        <label for="comments" class="block text-sm font-medium text-gray-700 mb-2">
                            Comments/Remarks
                        </label>
                        <textarea name="comments" id="comments" rows="4" 
                                  class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                  placeholder="Enter your comments or remarks...">{{ old('comments') }}</textarea>
                        @error('comments')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end items-center space-x-4">
                        <a href="{{ route('dashboard') }}" class="skew-button cancel-button">
                            <span>Cancel</span>
                        </a>
                        <button type="submit" name="action" value="reject" class="skew-button reject-button">
                            <span>Reject</span>
                        </button>
                        <button type="submit" name="action" value="approve" class="skew-button approve-button">
                            <span>Approve</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.skew-button {
    background: #fff;
    border: none;
    padding: 12px 24px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    font-weight: 600;
    min-width: 120px;
    text-transform: uppercase;
    cursor: pointer;
    border-radius: 50px;
    position: relative;
    text-decoration: none;
    color: #000;
    overflow: hidden;
    z-index: 1;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    white-space: nowrap;
    margin: 0 5px;
}

.skew-button span {
    display: inline-block;
    text-align: center;
}

.skew-button::before {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    right: 100%;
    left: 0;
    background: rgb(20, 20, 20);
    opacity: 0;
    z-index: -1;
    transition: all 0.5s;
}

.skew-button:hover {
    color: #fff;
}

.skew-button:hover::before {
    left: 0;
    right: 0;
    opacity: 1;
}

.cancel-button {
    background: #f3f4f6;
    color: #6b7280;
}

.cancel-button::before {
    background: #6b7280;
}

.reject-button {
    background: #fee2e2;
    color: #dc2626;
}

.reject-button::before {
    background: #dc2626;
}

.approve-button {
    background: #d1fae5;
    color: #059669;
}

.approve-button::before {
    background: #059669;
}
</style>
@endpush
