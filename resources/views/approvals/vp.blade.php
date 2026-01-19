@extends('approvals.generic')

@section('page-title', 'Vice President for Academics - Final Approval')
@section('page-subtitle', 'Final institutional approval and academic quality assurance')

@push('styles')
@parent
<style>
/* VP-specific styling */
.vp-header {
    background: linear-gradient(135deg, #1f2937, #374151);
    color: white;
    padding: 1rem;
    border-radius: 0.5rem;
    margin-bottom: 1.5rem;
}

.vp-note {
    background: #f9fafb;
    border-left: 4px solid #1f2937;
    padding: 1rem;
    margin: 1rem 0;
}

.approval-section {
    border: 2px solid #1f2937;
}

.final-review {
    background: #f9fafb;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    padding: 1rem;
    margin: 1rem 0;
}

.approval-timeline {
    background: #f0f9ff;
    border: 1px solid #bae6fd;
    border-radius: 0.5rem;
    padding: 1rem;
    margin: 1rem 0;
}
</style>
@endpush

@section('content')
<div class="pb-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- VP Header -->
        <div class="vp-header">
            <div class="flex items-center">
                <i class="fas fa-crown text-2xl mr-3"></i>
                <div>
                    <h2 class="text-xl font-bold">Vice President for Academics - Final Approval</h2>
                    <p class="text-gray-100">Final institutional approval and academic quality assurance review</p>
                </div>
            </div>
        </div>

        <!-- Complete Approval Timeline -->
        <div class="approval-timeline mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-timeline mr-2"></i>
                Complete Approval Timeline
            </h3>
            <div class="space-y-3">
                @if($activity->hasPassedStep('noted_by_adviser'))
                    <div class="flex items-center text-green-600">
                        <i class="fas fa-check-circle mr-3"></i>
                        <div>
                            <span class="font-medium">Adviser Approval</span>
                            <span class="text-sm text-gray-600 ml-2">{{ $activity->adviser_noted_at?->format('M d, Y g:i A') }}</span>
                        </div>
                    </div>
                @endif

                @if($activity->hasPassedStep('noted_by_dean'))
                    <div class="flex items-center text-green-600">
                        <i class="fas fa-check-circle mr-3"></i>
                        <div>
                            <span class="font-medium">Dean/Unit Head Approval</span>
                            <span class="text-sm text-gray-600 ml-2">{{ $activity->dean_noted_at?->format('M d, Y g:i A') }}</span>
                        </div>
                    </div>
                @endif

                @if($activity->hasPassedStep('reviewed_by_psg'))
                    <div class="flex items-center text-green-600">
                        <i class="fas fa-check-circle mr-3"></i>
                        <div>
                            <span class="font-medium">PSG Council Review</span>
                            <span class="text-sm text-gray-600 ml-2">{{ $activity->psg_reviewed_at?->format('M d, Y g:i A') }}</span>
                        </div>
                    </div>
                @endif

                @if($activity->hasPassedStep('endorsed_by_director'))
                    <div class="flex items-center text-green-600">
                        <i class="fas fa-check-circle mr-3"></i>
                        <div>
                            <span class="font-medium">Director of Student Affairs Endorsement</span>
                            <span class="text-sm text-gray-600 ml-2">{{ $activity->director_endorsed_at?->format('M d, Y g:i A') }}</span>
                        </div>
                    </div>
                @endif

                <div class="flex items-center text-yellow-600">
                    <i class="fas fa-clock mr-3"></i>
                    <div>
                        <span class="font-medium">Vice President for Academics Approval</span>
                        <span class="text-sm text-gray-600 ml-2">Pending</span>
                    </div>
                </div>
            </div>
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
                        <span class="px-3 py-1 text-sm font-medium rounded-full bg-gray-100 text-gray-800">
                            Final Approval Stage
                        </span>
                    </div>
                </div>

                <!-- Academic Quality Review -->
                <div class="final-review">
                    <h4 class="font-semibold text-gray-900 mb-3">
                        <i class="fas fa-graduation-cap mr-2"></i>
                        Academic Quality Assurance Review
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-gray-600 mr-2"></i>
                                <span>Academic merit and educational value</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-gray-600 mr-2"></i>
                                <span>Alignment with institutional mission</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-gray-600 mr-2"></i>
                                <span>Student learning outcomes</span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-gray-600 mr-2"></i>
                                <span>Resource optimization</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-gray-600 mr-2"></i>
                                <span>Strategic institutional impact</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-gray-600 mr-2"></i>
                                <span>Quality assurance standards</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Activity Summary -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <div class="text-2xl font-bold text-gray-900">{{ $activity->activity_date->format('d') }}</div>
                        <div class="text-sm text-gray-600">{{ $activity->activity_date->format('M Y') }}</div>
                        <div class="text-xs text-gray-500">Activity Date</div>
                    </div>
                    
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <div class="text-2xl font-bold text-gray-900">{{ number_format($activity->expected_participants) }}</div>
                        <div class="text-sm text-gray-600">Participants</div>
                        <div class="text-xs text-gray-500">Expected Attendance</div>
                    </div>
                    
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <div class="text-2xl font-bold text-gray-900">{{ $activity->budget ? '₱' . number_format($activity->budget, 0) : 'N/A' }}</div>
                        <div class="text-sm text-gray-600">Budget</div>
                        <div class="text-xs text-gray-500">Total Allocation</div>
                    </div>
                    
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <div class="text-2xl font-bold text-gray-900">{{ $activity->activity_date->diffInDays(now()) }}</div>
                        <div class="text-sm text-gray-600">Days</div>
                        <div class="text-xs text-gray-500">Until Activity</div>
                    </div>
                </div>

                <!-- Key Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Organization</label>
                        <p class="text-gray-900">{{ $activity->organization }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Venue</label>
                        <p class="text-gray-900">{{ $activity->location }}</p>
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
                        <label class="block text-sm font-medium text-gray-700 mb-1">Activity Type</label>
                        <p class="text-gray-900">{{ ucfirst(str_replace('-', ' ', $activity->type)) }}</p>
                    </div>
                </div>

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

                <!-- Previous Comments Summary -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Previous Approval Comments</label>
                    <div class="space-y-3">
                        @if($activity->dean_notes)
                            <div class="bg-blue-50 border border-blue-200 rounded p-3">
                                <div class="font-medium text-blue-900">Dean/Unit Head:</div>
                                <p class="text-blue-800 text-sm">{{ $activity->dean_notes }}</p>
                            </div>
                        @endif
                        
                        @if($activity->psg_review_comments)
                            <div class="bg-purple-50 border border-purple-200 rounded p-3">
                                <div class="font-medium text-purple-900">PSG Council Adviser:</div>
                                <p class="text-purple-800 text-sm">{{ $activity->psg_review_comments }}</p>
                            </div>
                        @endif
                        
                        @if($activity->director_endorsement_comments)
                            <div class="bg-red-50 border border-red-200 rounded p-3">
                                <div class="font-medium text-red-900">Director of Student Affairs:</div>
                                <p class="text-red-800 text-sm">{{ $activity->director_endorsement_comments }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- VP Final Approval Form -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg approval-section">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-crown text-gray-600 mr-2"></i>
                    Vice President for Academics - Final Decision
                </h3>

                <form method="POST" action="{{ route('workflow.approval.process', $activity) }}" class="space-y-6">
                    @csrf
                    
                    <!-- VP Final Comments -->
                    <div>
                        <label for="comments" class="block text-sm font-medium text-gray-700 mb-2">
                            Vice President's Final Assessment and Decision
                            <span class="text-red-500">*</span>
                        </label>
                        <textarea name="comments" id="comments" rows="4" required
                                  class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500"
                                  placeholder="Provide your final assessment of the activity's academic merit, institutional alignment, and overall approval decision...">{{ old('comments') }}</textarea>
                        @error('comments')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Final Decision Notice -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle text-yellow-600 mr-2"></i>
                            <div class="text-sm text-yellow-800">
                                <strong>Important:</strong> This is the final approval step. Your decision will determine whether this activity is approved or rejected.
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end items-center space-x-4">
                        <a href="{{ route('dashboard') }}" class="skew-button cancel-button">
                            <span>Cancel</span>
                        </a>
                        <button type="submit" name="action" value="reject" class="skew-button reject-button">
                            <span>Final Rejection</span>
                        </button>
                        <button type="submit" name="action" value="approve" class="skew-button approve-button">
                            <span>Final Approval</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
