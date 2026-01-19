@extends('approvals.generic')

@section('page-title', 'PSG Council Adviser Review')
@section('page-subtitle', 'Review activity compliance with student council guidelines')

@push('styles')
@parent
<style>
/* PSG-specific styling */
.psg-header {
    background: linear-gradient(135deg, #7c3aed, #a855f7);
    color: white;
    padding: 1rem;
    border-radius: 0.5rem;
    margin-bottom: 1.5rem;
}

.psg-note {
    background: #f3f4f6;
    border-left: 4px solid #7c3aed;
    padding: 1rem;
    margin: 1rem 0;
}

.approval-section {
    border: 2px solid #7c3aed;
}

.compliance-checklist {
    background: #faf5ff;
    border: 1px solid #e9d5ff;
    border-radius: 0.5rem;
    padding: 1rem;
    margin: 1rem 0;
}
</style>
@endpush

@section('content')
<div class="pb-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- PSG Header -->
        <div class="psg-header">
            <div class="flex items-center">
                <i class="fas fa-users text-2xl mr-3"></i>
                <div>
                    <h2 class="text-xl font-bold">PSG Council Adviser Review</h2>
                    <p class="text-purple-100">Student council guidelines and policy compliance review</p>
                </div>
            </div>
        </div>

        <!-- Previous Approvals -->
        <div class="space-y-4 mb-6">
            @if($activity->hasPassedStep('noted_by_adviser'))
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-green-200">
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
                        </div>
                    </div>
                </div>
            @endif

            @if($activity->hasPassedStep('noted_by_dean'))
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-blue-200">
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-blue-800 mb-3">
                            <i class="fas fa-check-circle mr-2"></i>
                            Dean/Unit Head Approval
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Noted by</label>
                                <p class="text-gray-900">{{ $activity->deanNoted->name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Date</label>
                                <p class="text-gray-900">{{ $activity->dean_noted_at?->format('M d, Y g:i A') ?? 'N/A' }}</p>
                            </div>
                            @if($activity->dean_notes)
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Dean Notes</label>
                                    <p class="text-gray-900 bg-gray-50 p-3 rounded">{{ $activity->dean_notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Activity Details Card -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-2 border-yellow-500 mb-6">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">{{ $activity->title }}</h2>
                    <div class="flex items-center space-x-3">
                        <span class="px-3 py-1 text-sm font-medium rounded-full {{ $activity->type === 'in-campus' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ ucfirst(str_replace('-', ' ', $activity->type)) }}
                        </span>
                        <span class="px-3 py-1 text-sm font-medium rounded-full bg-purple-100 text-purple-800">
                            Awaiting PSG Review
                        </span>
                    </div>
                </div>

                <!-- PSG Compliance Checklist -->
                <div class="compliance-checklist">
                    <h4 class="font-semibold text-purple-900 mb-3">
                        <i class="fas fa-clipboard-check mr-2"></i>
                        Student Council Guidelines Compliance
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <input type="checkbox" class="mr-2 text-purple-600" disabled>
                                <span>Student leadership involvement verified</span>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" class="mr-2 text-purple-600" disabled>
                                <span>Activity aligns with student development goals</span>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" class="mr-2 text-purple-600" disabled>
                                <span>Proper student organization representation</span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <input type="checkbox" class="mr-2 text-purple-600" disabled>
                                <span>Compliance with student council policies</span>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" class="mr-2 text-purple-600" disabled>
                                <span>Appropriate student participation level</span>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" class="mr-2 text-purple-600" disabled>
                                <span>No conflicts with other student activities</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Activity Information Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Student Organization</label>
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
                        <label class="block text-sm font-medium text-gray-700 mb-1">Expected Student Participants</label>
                        <p class="text-gray-900 text-lg font-semibold text-purple-600">{{ number_format($activity->expected_participants) }}</p>
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
                        <label class="block text-sm font-medium text-gray-700 mb-1">Activity Type</label>
                        <p class="text-gray-900">{{ ucfirst(str_replace('-', ' ', $activity->type)) }}</p>
                    </div>
                </div>

                <!-- Student Leaders -->
                @if($activity->leaders)
                    <div class="mb-6 bg-purple-50 border border-purple-200 rounded-lg p-4">
                        <label class="block text-sm font-medium text-purple-700 mb-2">Student Leaders/Organizers</label>
                        <p class="text-purple-900 whitespace-pre-line">{{ $activity->leaders }}</p>
                    </div>
                @endif

                <!-- Objectives -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Activity Objectives</label>
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
            </div>
        </div>

        <!-- PSG Review Form -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg approval-section">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-purple-900 mb-4">
                    <i class="fas fa-users text-purple-600 mr-2"></i>
                    PSG Council Adviser Review
                </h3>

                <form method="POST" action="{{ route('workflow.approval.process', $activity) }}" class="space-y-6">
                    @csrf
                    
                    <!-- PSG Review Comments -->
                    <div>
                        <label for="comments" class="block text-sm font-medium text-gray-700 mb-2">
                            PSG Council Review and Recommendations
                            <span class="text-red-500">*</span>
                        </label>
                        <textarea name="comments" id="comments" rows="4" required
                                  class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                                  placeholder="Assess the activity's compliance with student council guidelines, student development value, and organizational impact...">{{ old('comments') }}</textarea>
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
                            <span>Request Revision</span>
                        </button>
                        <button type="submit" name="action" value="approve" class="skew-button approve-button">
                            <span>Review & Forward</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
