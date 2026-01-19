@extends('approvals.generic')

@section('page-title', 'Dean/Unit Head Approval')
@section('page-subtitle', 'Review and note activity request as Dean/Unit Head')

@push('styles')
@parent
<style>
/* Dean-specific styling */
.dean-header {
    background: linear-gradient(135deg, #1e40af, #3b82f6);
    color: white;
    padding: 1rem;
    border-radius: 0.5rem;
    margin-bottom: 1.5rem;
}

.dean-note {
    background: #f0fdf4;
    border-left: 4px solid #22c55e;
    padding: 1rem;
    margin: 1rem 0;
}

.approval-section {
    border: 2px solid #3b82f6;
}

/* Override approve button styling for dean page */
.approve-button {
    background: #22c55e !important;
    color: white !important;
}

.approve-button::before {
    background: #16a34a !important;
}

.approve-button:hover {
    background: #16a34a !important;
}
</style>
@endpush

@section('content')
<div class="pb-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Dean Header -->
        <div class="dean-header">
            <div class="flex items-center">
                <i class="fas fa-university text-2xl mr-3"></i>
                <div>
                    <h2 class="text-xl font-bold">Dean/Unit Head Review</h2>
                    <p class="text-blue-100">Academic and administrative review of activity proposal</p>
                </div>
            </div>
        </div>

        <!-- Previous Approvals -->
        @if($activity->hasPassedStep('noted_by_adviser'))
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-green-200 mb-6">
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-green-800 mb-3">
                        <i class="fas fa-check-circle mr-2"></i>
                        Adviser Approval
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Approved by</label>
                            <p class="text-gray-900">{{ $activity->adviserNoted->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date</label>
                            <p class="text-gray-900">{{ $activity->adviser_noted_at?->format('M d, Y g:i A') ?? 'N/A' }}</p>
                        </div>
                        @if($activity->adviser_notes)
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Adviser Notes</label>
                                <p class="text-gray-900 bg-gray-50 p-3 rounded">{{ $activity->adviser_notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Activity Details Card -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-2 border-yellow-500 mb-6">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">{{ $activity->title }}</h2>
                    <div class="flex items-center space-x-3">
                        <span class="px-3 py-1 text-sm font-medium rounded-full {{ $activity->type === 'in-campus' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ ucfirst(str_replace('-', ' ', $activity->type)) }}
                        </span>
                        <span class="px-3 py-1 text-sm font-medium rounded-full bg-blue-100 text-blue-800">
                            Awaiting Dean Approval
                        </span>
                    </div>
                </div>

                <!-- Dean-specific considerations -->
                <div class="dean-note">
                    <h4 class="font-semibold text-blue-900 mb-2">
                        <i class="fas fa-info-circle mr-2"></i>
                        Dean Review Considerations
                    </h4>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li>• Academic alignment with department/unit objectives</li>
                        <li>• Resource allocation and budget appropriateness</li>
                        <li>• Faculty and staff involvement requirements</li>
                        <li>• Compliance with institutional policies</li>
                        <li>• Impact on academic schedule and operations</li>
                    </ul>
                </div>

                <!-- Activity Information Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Submitted by</label>
                        <p class="text-gray-900">{{ $activity->user->name }}</p>
                        <p class="text-sm text-gray-600">{{ $activity->user->department ?? 'N/A' }}</p>
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
                        <p class="text-gray-900">
                            @if($activity->start_time && $activity->end_time)
                                {{ $activity->start_time->format('g:i A') }} - {{ $activity->end_time->format('g:i A') }}
                            @else
                                Time not specified
                            @endif
                        </p>
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

                <!-- Budget Analysis -->
                @if($activity->budget)
                    <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Budget Analysis</label>
                        <p class="text-gray-900 text-lg font-semibold">₱{{ number_format($activity->budget, 2) }}</p>
                        <p class="text-sm text-yellow-800 mt-2">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Please review budget allocation and ensure compliance with department spending guidelines.
                        </p>
                    </div>
                @endif

                <!-- Attachments -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Required Documents</label>
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
                                            <a href="#" onclick="showDocumentPreview('{{ $budgetUrl }}', '{{ $budgetExt }}'); return false;" class="text-xs text-blue-600 hover:text-blue-800">View Document</a>
                                            <a href="{{ $activity->budget_file_download_url }}" class="text-xs text-green-600 hover:text-green-800">Download</a>
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
                                            <a href="#" onclick="showDocumentPreview('{{ $permitUrl }}', '{{ $permitExt }}'); return false;" class="text-xs text-blue-600 hover:text-blue-800">View Document</a>
                                            <a href="{{ $activity->permit_file_download_url }}" class="text-xs text-green-600 hover:text-green-800">Download</a>
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
                                            <a href="#" onclick="showDocumentPreview('{{ $suppUrl }}', '{{ $suppExt }}'); return false;" class="text-xs text-blue-600 hover:text-blue-800">View Document</a>
                                            <a href="{{ $activity->supporting_documents_download_url }}" class="text-xs text-green-600 hover:text-green-800">Download</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Dean Approval Form -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg approval-section">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-blue-900 mb-4">
                    <i class="fas fa-university text-blue-600 mr-2"></i>
                    Dean/Unit Head Decision
                </h3>

                <form method="POST" action="{{ route('workflow.approval.process', $activity) }}" class="space-y-6">
                    @csrf
                    
                    <!-- Dean Notes -->
                    <div>
                        <label for="comments" class="block text-sm font-medium text-gray-700 mb-2">
                            Dean's Notes and Recommendations
                            <span class="text-red-500">*</span>
                        </label>
                        <textarea name="comments" id="comments" rows="4" required
                                  class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                  placeholder="Provide your assessment of the activity's academic merit, resource requirements, and alignment with department objectives...">{{ old('comments') }}</textarea>
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
                            <span>Return for Revision</span>
                        </button>
                        <button type="submit" name="action" value="approve" class="skew-button approve-button">
                            <span>Note & Forward</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
