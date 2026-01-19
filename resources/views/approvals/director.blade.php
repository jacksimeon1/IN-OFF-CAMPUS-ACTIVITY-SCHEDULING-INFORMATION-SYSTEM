@extends('approvals.generic')

@section('page-title', 'Director of Student Officer Affairs Endorsement')
@section('page-subtitle', 'Review and endorse activity for institutional policy compliance')

@push('styles')
@parent
<style>
/* Director-specific styling */
.director-header {
    background: linear-gradient(135deg, #dc2626, #ef4444);
    color: white;
    padding: 1rem;
    border-radius: 0.5rem;
    margin-bottom: 1.5rem;
}

.director-note {
    background: #fef2f2;
    border-left: 4px solid #dc2626;
    padding: 1rem;
    margin: 1rem 0;
}

.approval-section {
    border: 2px solid #dc2626;
}

.policy-review {
    background: #fef2f2;
    border: 1px solid #fecaca;
    border-radius: 0.5rem;
    padding: 1rem;
    margin: 1rem 0;
}
</style>
@endpush

@section('content')
<div class="pb-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Director Header -->
        <div class="director-header">
            <div class="flex items-center">
                <i class="fas fa-shield-alt text-2xl mr-3"></i>
                <div>
                    <h2 class="text-xl font-bold">Director of Student Affairs Endorsement</h2>
                    <p class="text-red-100">Institutional policy compliance and student welfare review</p>
                </div>
            </div>
        </div>

        <!-- Approval History -->
        <div class="space-y-4 mb-6">
            @if($activity->hasPassedStep('noted_by_adviser'))
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-green-200">
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-green-800 mb-3">
                            <i class="fas fa-check-circle mr-2"></i>
                            Adviser Approval
                        </h3>
                        <p class="text-sm text-gray-600">{{ $activity->adviser_noted_at?->format('M d, Y g:i A') ?? 'N/A' }}</p>
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
                        <p class="text-sm text-gray-600">{{ $activity->dean_noted_at?->format('M d, Y g:i A') ?? 'N/A' }}</p>
                    </div>
                </div>
            @endif

            @if($activity->hasPassedStep('reviewed_by_psg'))
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-purple-200">
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-purple-800 mb-3">
                            <i class="fas fa-check-circle mr-2"></i>
                            PSG Council Review
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Reviewed by</label>
                                <p class="text-gray-900">{{ $activity->psgReviewed->name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Date</label>
                                <p class="text-gray-900">{{ $activity->psg_reviewed_at?->format('M d, Y g:i A') ?? 'N/A' }}</p>
                            </div>
                            @if($activity->psg_review_comments)
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">PSG Comments</label>
                                    <p class="text-gray-900 bg-gray-50 p-3 rounded">{{ $activity->psg_review_comments }}</p>
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
                        <span class="px-3 py-1 text-sm font-medium rounded-full bg-red-100 text-red-800">
                            Awaiting Director Endorsement
                        </span>
                    </div>
                </div>

                <!-- Policy Compliance Review -->
                <div class="policy-review">
                    <h4 class="font-semibold text-red-900 mb-3">
                        <i class="fas fa-gavel mr-2"></i>
                        Institutional Policy Review Points
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-red-600 mr-2"></i>
                                <span>Student safety and welfare protocols</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-red-600 mr-2"></i>
                                <span>Institutional risk assessment</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-red-600 mr-2"></i>
                                <span>Budget and resource allocation</span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-red-600 mr-2"></i>
                                <span>External partnership compliance</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-red-600 mr-2"></i>
                                <span>Academic calendar alignment</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-red-600 mr-2"></i>
                                <span>Institutional reputation impact</span>
                            </div>
                        </div>
                    </div>
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

                <!-- Risk Assessment -->
                @if($activity->type === 'off-campus')
                    <div class="mb-6 bg-orange-50 border border-orange-200 rounded-lg p-4">
                        <h4 class="font-semibold text-orange-900 mb-2">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Off-Campus Activity Risk Assessment Required
                        </h4>
                        <p class="text-sm text-orange-800">
                            This off-campus activity requires additional safety protocols and risk mitigation measures.
                            Please ensure all safety guidelines and emergency procedures are in place.
                        </p>
                    </div>
                @endif

                <!-- Budget Review -->
                @if($activity->budget)
                    <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Budget Allocation</label>
                        <p class="text-gray-900 text-lg font-semibold">₱{{ number_format($activity->budget, 2) }}</p>
                        <p class="text-sm text-yellow-800 mt-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            Budget has been reviewed and approved by previous approvers.
                        </p>
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

        <!-- Director Endorsement Form -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg approval-section">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-red-900 mb-4">
                    <i class="fas fa-shield-alt text-red-600 mr-2"></i>
                    Director of Student Affairs Endorsement
                </h3>

                <form method="POST" action="{{ route('workflow.approval.process', $activity) }}" class="space-y-6">
                    @csrf
                    
                    <!-- Director Endorsement Comments -->
                    <div>
                        <label for="comments" class="block text-sm font-medium text-gray-700 mb-2">
                            Director's Endorsement and Policy Compliance Assessment
                            <span class="text-red-500">*</span>
                        </label>
                        <textarea name="comments" id="comments" rows="4" required
                                  class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                                  placeholder="Provide your assessment of institutional policy compliance, student welfare considerations, and endorsement for final approval...">{{ old('comments') }}</textarea>
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
                            <span>Return for Compliance</span>
                        </button>
                        <button type="submit" name="action" value="approve" class="skew-button approve-button">
                            <span>Endorse to VP</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
