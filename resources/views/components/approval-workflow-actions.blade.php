@props(['activity'])

@php
    $user = auth()->user();
    $canTakeAction = false;
    $actionType = '';
    $actionRoute = '';
    $actionTitle = '';
    $actionDescription = '';
@endphp

<!-- Determine if user can take action -->
@if($activity->workflow_status === 'noted_by_adviser' && ($user->isAdviser() || $user->isAdmin()))
    @php
        $canTakeAction = true;
        $actionType = 'note';
        $actionRoute = route('workflow.adviser-note', $activity);
        $actionTitle = 'Adviser Action Required';
        $actionDescription = 'As an adviser, you need to note this activity to proceed with the approval workflow.';
    @endphp
@elseif($activity->workflow_status === 'noted_by_adviser' && ($user->isDean() || $user->isAdmin()))
    @php
        $canTakeAction = true;
        $actionType = 'note';
        $actionRoute = route('workflow.dean-note', $activity);
        $actionTitle = 'Dean Action Required';
        $actionDescription = 'As a dean/unit head, you need to note this activity to proceed with the approval workflow.';
    @endphp
@elseif($activity->workflow_status === 'noted_by_dean' && ($user->isPsgAdviser() || $user->isAdmin()))
    @php
        $canTakeAction = true;
        $actionType = 'review';
        $actionRoute = route('workflow.psg-review', $activity);
        $actionTitle = 'PSG Review Required';
        $actionDescription = 'As PSG Council Adviser, you need to review this activity for policy alignment.';
    @endphp
@elseif($activity->workflow_status === 'reviewed_by_psg' && ($user->isDirector() || $user->isAdmin()))
    @php
        $canTakeAction = true;
        $actionType = 'endorse';
        $actionRoute = route('workflow.director-endorse', $activity);
        $actionTitle = 'Director Endorsement Required';
        $actionDescription = 'As Director of Student Affairs, you need to endorse this activity.';
    @endphp
@elseif($activity->workflow_status === 'endorsed_by_director' && ($user->isVp() || $user->isAdmin()))
    @php
        $canTakeAction = true;
        $actionType = 'approve';
        $actionRoute = route('workflow.vp-approve', $activity);
        $actionTitle = 'VP Final Approval Required';
        $actionDescription = 'As VP for Academics, you can give final approval for this activity.';
    @endphp
@endif

@if($canTakeAction)
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
            <i class="fas fa-tasks mr-2 text-green-600"></i>
            {{ $actionTitle }}
        </h3>
    </div>
    
    <div class="p-6">
        <div class="mb-4">
            <p class="text-gray-700">{{ $actionDescription }}</p>
            
            <!-- Deadline Check (Always show for information) -->
            @if(!$activity->checkDeadlineRequirement())
                <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex">
                        <i class="fas fa-exclamation-triangle text-red-500 mt-0.5 mr-3"></i>
                        <div>
                            <h4 class="text-red-800 font-medium">Deadline Requirement Not Met</h4>
                            <p class="text-red-700 text-sm mt-1">
                                Activities must be submitted at least 7 days before the scheduled date.
                                Your activity is scheduled for {{ $activity->activity_date->format('M d, Y') }}
                                ({{ $activity->days_before_activity }} days from now).
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex">
                        <i class="fas fa-check-circle text-green-500 mt-0.5 mr-3"></i>
                        <div>
                            <h4 class="text-green-800 font-medium">Deadline Requirement Met</h4>
                            <p class="text-green-700 text-sm mt-1">
                                Your activity meets the 7-day submission requirement
                                ({{ $activity->days_before_activity }} days before scheduled date).
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Action Form -->
        <form method="POST" action="{{ $actionRoute }}" class="space-y-4">
            @csrf
            
            @if($actionType !== 'submit')
                <!-- Approval Actions -->
                <div class="space-y-4">
                    <!-- Comments/Notes -->
                    <div>
                        <label for="comments" class="block text-sm font-medium text-gray-700 mb-2">
                            @if($actionType === 'note')
                                Notes (Optional)
                            @elseif($actionType === 'review')
                                Review Comments (Optional)
                            @elseif($actionType === 'endorse')
                                Endorsement Comments (Optional)
                            @elseif($actionType === 'approve')
                                Approval Comments (Optional)
                            @endif
                        </label>
                        <textarea id="comments" name="comments" rows="3" 
                                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                  placeholder="Add any comments or notes..."></textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center space-x-4">
                        <!-- Approve/Note/Review/Endorse Button -->
                        <button type="submit" name="action"
                                value="{{ $actionType }}"
                                class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            @if($actionType === 'note')
                                <i class="fas fa-sticky-note mr-2"></i>
                                Note & Forward
                            @elseif($actionType === 'review')
                                <i class="fas fa-search mr-2"></i>
                                Review & Forward
                            @elseif($actionType === 'endorse')
                                <i class="fas fa-stamp mr-2"></i>
                                Endorse & Forward
                            @elseif($actionType === 'approve')
                                <i class="fas fa-check-circle mr-2"></i>
                                Final Approval
                            @endif
                        </button>

                        <!-- Reject Button -->
                        <button type="submit" name="action" value="reject"
                                onclick="return confirm('Are you sure you want to reject this activity? This action cannot be undone.')"
                                class="inline-flex items-center px-6 py-3 bg-red-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <i class="fas fa-times-circle mr-2"></i>
                            Reject
                        </button>
                    </div>
                </div>
            @endif
        </form>

        <!-- Information Box -->
        <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <div class="flex">
                <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-3"></i>
                <div>
                    <h4 class="text-blue-800 font-medium">Workflow Information</h4>
                    <div class="text-blue-700 text-sm mt-1 space-y-1">
                        <p>• Your action will move the activity to the next approval step</p>
                        <p>• Relevant parties will be notified automatically</p>
                        <p>• All actions are logged and tracked in the system</p>
                        <p>• Four (4) copies will be automatically distributed upon final approval</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Copy Distribution Reminder -->
@if($activity->workflow_status === 'approved_by_vp' && $activity->copies_distributed)
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
    <div class="p-6">
        <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex">
                <i class="fas fa-clipboard-check text-green-500 text-xl mt-0.5 mr-3"></i>
                <div>
                    <h4 class="text-green-800 font-medium">Activity Approved & Copies Distributed</h4>
                    <p class="text-green-700 text-sm mt-1">
                        This activity has been fully approved and four (4) copies have been distributed to:
                    </p>
                    <ul class="text-green-700 text-sm mt-2 space-y-1">
                        @foreach($activity->getCopyDistributionList() as $office)
                            <li><i class="fas fa-check mr-2"></i>{{ $office }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
