@props(['activity'])

<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
            <i class="fas fa-route mr-2 text-blue-600"></i>
            Approval Workflow Status
        </h3>
    </div>
    
    <div class="p-6">
        <!-- Current Status -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <h4 class="text-sm font-medium text-gray-700">Current Status</h4>
                <span class="inline-flex items-center px-3 py-1 rounded text-sm font-medium bg-gray-100 text-gray-800">
                    {{ $activity->getCurrentApprovalStepName() }}
                </span>
            </div>
        </div>

        <!-- Deadline Requirement Notice (visible to all roles) -->
        <div class="mb-6">
            @if(!$activity->checkDeadlineRequirement())
                <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex">
                        <i class="fas fa-exclamation-triangle text-red-500 mt-0.5 mr-3"></i>
                        <div>
                            <h4 class="text-red-800 font-medium">Deadline Requirement Not Met</h4>
                            <p class="text-red-700 text-sm mt-1">
                                Activities must be submitted at least 7 days before the scheduled date.
                                This activity is scheduled for {{ $activity->activity_date->format('M d, Y') }}
                                ({{ $activity->days_before_activity }} days from now).
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex">
                        <i class="fas fa-check-circle text-green-500 mt-0.5 mr-3"></i>
                        <div>
                            <h4 class="text-green-800 font-medium">Deadline Requirement Met</h4>
                            <p class="text-green-700 text-sm mt-1">
                                This activity meets the 7-day submission requirement
                                ({{ $activity->days_before_activity }} days before scheduled date).
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Workflow Progress -->
        <div class="mb-6">
            <h4 class="text-sm font-medium text-gray-700 mb-4">Approval Progress</h4>
            <div class="space-y-4">
                
                <!-- Step 1: Adviser Note -->
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        @if($activity->adviser_noted_at)
                            <div class="w-6 h-6 bg-green-600 rounded-full flex items-center justify-center">
                                <i class="fas fa-check text-white text-xs"></i>
                            </div>
                        @elseif($activity->workflow_status === 'draft')
                            <div class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center">
                                <span class="text-white text-xs font-bold">1</span>
                            </div>
                        @else
                            <div class="w-6 h-6 bg-gray-300 rounded-full flex items-center justify-center">
                                <span class="text-gray-600 text-xs font-bold">1</span>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-900">Noted by Adviser</p>
                    </div>
                </div>

                <!-- Step 2: Dean Note -->
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        @if($activity->dean_noted_at)
                            <div class="w-6 h-6 bg-green-600 rounded-full flex items-center justify-center">
                                <i class="fas fa-check text-white text-xs"></i>
                            </div>
                        @elseif($activity->workflow_status === 'noted_by_adviser')
                            <div class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center">
                                <span class="text-white text-xs font-bold">2</span>
                            </div>
                        @else
                            <div class="w-6 h-6 bg-gray-300 rounded-full flex items-center justify-center">
                                <span class="text-gray-600 text-xs font-bold">2</span>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-900">Noted by Dean/Unit Head</p>
                    </div>
                </div>

                <!-- Step 3: PSG Review -->
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        @if($activity->psg_reviewed_at)
                            <div class="w-6 h-6 bg-green-600 rounded-full flex items-center justify-center">
                                <i class="fas fa-check text-white text-xs"></i>
                            </div>
                        @elseif($activity->workflow_status === 'noted_by_dean')
                            <div class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center">
                                <span class="text-white text-xs font-bold">3</span>
                            </div>
                        @else
                            <div class="w-6 h-6 bg-gray-300 rounded-full flex items-center justify-center">
                                <span class="text-gray-600 text-xs font-bold">3</span>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-900">Reviewed by PSG Council Adviser</p>
                    </div>
                </div>

                <!-- Step 4: Director Endorsement -->
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        @if($activity->director_endorsed_at)
                            <div class="w-6 h-6 bg-green-600 rounded-full flex items-center justify-center">
                                <i class="fas fa-check text-white text-xs"></i>
                            </div>
                        @elseif($activity->workflow_status === 'reviewed_by_psg')
                            <div class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center">
                                <span class="text-white text-xs font-bold">4</span>
                            </div>
                        @else
                            <div class="w-6 h-6 bg-gray-300 rounded-full flex items-center justify-center">
                                <span class="text-gray-600 text-xs font-bold">4</span>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-900">Endorsed by Director, Student Affairs</p>
                    </div>
                </div>

                <!-- Step 5: VP Approval (Final) -->
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        @if($activity->vp_approved_at)
                            <div class="w-6 h-6 bg-green-600 rounded-full flex items-center justify-center">
                                <i class="fas fa-check text-white text-xs"></i>
                            </div>
                        @elseif($activity->workflow_status === 'endorsed_by_director')
                            <div class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center">
                                <span class="text-white text-xs font-bold">5</span>
                            </div>
                        @else
                            <div class="w-6 h-6 bg-gray-300 rounded-full flex items-center justify-center">
                                <span class="text-gray-600 text-xs font-bold">5</span>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-900">Approved by VP for Academics</p>
                    </div>
                </div>
            </div>
        </div>



        <!-- Copy Distribution -->
        @if($activity->copies_distributed)
        <div class="p-4 bg-green-50 rounded-lg">
            <h4 class="text-sm font-medium text-green-800 mb-2">
                <i class="fas fa-copy mr-2"></i>
                Copy Distribution Completed
            </h4>
            <p class="text-sm text-green-700">Four (4) copies have been distributed to:</p>
            <ul class="text-sm text-green-700 mt-2 space-y-1">
                @foreach($activity->getCopyDistributionList() as $key => $office)
                    <li><i class="fas fa-check mr-2"></i>{{ $office }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Rejection Information -->
        @if($activity->workflow_status === 'rejected')
        <div class="p-4 bg-red-50 rounded-lg">
            <h4 class="text-sm font-medium text-red-800 mb-2">
                <i class="fas fa-times-circle mr-2"></i>
                Activity Rejected
            </h4>
            @if($activity->rejectedBy)
                <p class="text-sm text-red-700">Rejected by: {{ $activity->rejectedBy->name }}</p>
                @if($activity->rejected_at)
                    <p class="text-sm text-red-700">Date: {{ $activity->rejected_at->format('M d, Y g:i A') }}</p>
                @endif
            @endif
            @if($activity->rejection_reason)
                <p class="text-sm text-red-700 mt-2">Reason: {{ $activity->rejection_reason }}</p>
            @endif
        </div>
        @endif
    </div>
</div>
