@push('scripts')
<script src="{{ asset('js/docx-viewer.js') }}"></script>
@endpush
@extends('layouts.sidebar')

@section('title', 'Activity Details')
@section('page-title', 'Activity Details')

@section('content')
<div class="pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-green-800">{{ $activity->title }}</h1>
                <p class="text-sm text-green-700">Submitted by {{ $activity->user->name }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('adviser.pending-approvals') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Approvals
                </a>
                @if($activity->workflow_status === 'draft')
                    <button onclick="openNoteModal({{ $activity->id }}, '{{ addslashes($activity->title) }}')" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200">
                        <i class="fas fa-sticky-note mr-2"></i> Note & Forward
                    </button>
                    <button onclick="openRejectModal({{ $activity->id }}, '{{ addslashes($activity->title) }}')" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition-all duration-200">
                        <i class="fas fa-times mr-2"></i> Reject
                    </button>
                @endif
            </div>
        </div>

        <!-- Activity Status -->
        <div class="bg-gradient-to-br from-green-50 to-yellow-50 border border-green-300 overflow-hidden shadow-sm sm:rounded-xl mb-6">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-green-800">Current Status</h3>
                        <p class="text-sm text-green-700">{{ $activity->getCurrentApprovalStepName() }}</p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @if($activity->workflow_status === 'approved_by_vp') bg-green-200 text-green-800
                        @elseif($activity->workflow_status === 'rejected') bg-yellow-200 text-yellow-800
                        @elseif($activity->workflow_status === 'draft') bg-green-200 text-green-800
                        @else bg-yellow-200 text-yellow-800
                        @endif">
                        {{ $activity->getCurrentApprovalStepName() }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Approval Workflow Status -->
        <x-approval-workflow-status :activity="$activity" />

        <!-- Activity Details -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Basic Information -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Basic Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Activity Title</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $activity->title }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Organization</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $activity->organization }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Activity Type</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($activity->type === 'in-campus') bg-blue-100 text-blue-800
                            @else bg-purple-100 text-purple-800
                            @endif">
                            {{ ucfirst(str_replace('-', ' ', $activity->type)) }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Leaders/Organizers</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $activity->leaders }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Expected Participants</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $activity->expected_participants ?? 'Not specified' }}</p>
                    </div>
                </div>
            </div>

            <!-- Schedule and Location -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Schedule & Location</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Activity Date</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $activity->activity_date->format('F d, Y') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">End Date</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $activity->end_date->format('F d, Y') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Time</label>
                        <p class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($activity->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($activity->end_time)->format('g:i A') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Venue</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $activity->location }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Budget</label>
                        <p class="mt-1 text-sm text-gray-900">₱{{ number_format($activity->budget ?? 0, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Objectives -->
        <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-xl">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Objectives</h3>
            </div>
            <div class="p-6">
                <div class="space-y-2">
                    @if($activity->objective_1)
                        <div class="flex items-start">
                            <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-800 rounded-full flex items-center justify-center text-xs font-medium mr-3 mt-0.5">1</span>
                            <p class="text-sm text-gray-900">{{ $activity->objective_1 }}</p>
                        </div>
                    @endif
                    @if($activity->objective_2)
                        <div class="flex items-start">
                            <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-800 rounded-full flex items-center justify-center text-xs font-medium mr-3 mt-0.5">2</span>
                            <p class="text-sm text-gray-900">{{ $activity->objective_2 }}</p>
                        </div>
                    @endif
                    @if($activity->objective_3)
                        <div class="flex items-start">
                            <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-800 rounded-full flex items-center justify-center text-xs font-medium mr-3 mt-0.5">3</span>
                            <p class="text-sm text-gray-900">{{ $activity->objective_3 }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Speakers -->
        @if($activity->speakers)
        <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-xl">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Speakers</h3>
            </div>
            <div class="p-6">
                <p class="text-sm text-gray-900">{{ $activity->speakers }}</p>
            </div>
        </div>
        @endif

        <!-- Attachments -->
        <div class="mt-6">
            <x-activity-attachments :activity="$activity" />
        </div>

        <!-- Approval History -->
        <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-xl">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Approval History</h3>
            </div>
            <div class="p-6">
                <div class="flow-root">
                    <ul class="-mb-8">
                        <li>
                            <div class="relative pb-8">
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                            <i class="fas fa-plus text-white text-xs"></i>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-sm text-gray-500">Activity submitted by <span class="font-medium text-gray-900">{{ $activity->user->name }}</span></p>
                                        </div>
                                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                            {{ $activity->created_at->format('M d, Y g:i A') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @if($activity->workflow_status !== 'draft')
                        <li>
                            <div class="relative">
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                            <i class="fas fa-sticky-note text-white text-xs"></i>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-sm text-gray-500">Noted by Adviser</p>
                                        </div>
                                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                            {{ $activity->adviser_noted_at ? $activity->adviser_noted_at->format('M d, Y g:i A') : 'Pending' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Note Modal -->
<div id="noteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100">
                <i class="fas fa-sticky-note text-blue-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-2">Note & Forward Activity</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500" id="noteActivityTitle">
                    Are you sure you want to note and forward this activity to the Dean?
                </p>
                <form id="noteForm" method="POST" class="mt-4">
                    @csrf
                    <input type="hidden" name="action" value="note">
                    <div class="mb-4">
                        <label for="noteComments" class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                        <textarea name="notes" id="noteComments" rows="3" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Add any notes for this activity..."></textarea>
                    </div>
                    <div class="flex justify-center space-x-3">
                        <button type="button" onclick="closeNoteModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Note & Forward
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <i class="fas fa-times text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-2">Reject Activity</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500" id="rejectActivityTitle">
                    Are you sure you want to reject this activity?
                </p>
                <form id="rejectForm" method="POST" class="mt-4">
                    @csrf
                    <input type="hidden" name="action" value="reject">
                    <div class="mb-4">
                        <label for="rejectComments" class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason <span class="text-red-500">*</span></label>
                        <textarea name="notes" id="rejectComments" rows="3" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" placeholder="Please provide a reason for rejection..."></textarea>
                    </div>
                    <div class="flex justify-center space-x-3">
                        <button type="button" onclick="closeRejectModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                            Reject
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openNoteModal(activityId, activityTitle) {
    document.getElementById('noteActivityTitle').textContent = `Note and forward "${activityTitle}" to the Dean?`;
    document.getElementById('noteForm').action = `/adviser/activities/${activityId}/note`;
    document.getElementById('noteModal').classList.remove('hidden');
}

function closeNoteModal() {
    document.getElementById('noteModal').classList.add('hidden');
    document.getElementById('noteComments').value = '';
}

function openRejectModal(activityId, activityTitle) {
    document.getElementById('rejectActivityTitle').textContent = `Reject "${activityTitle}"?`;
    document.getElementById('rejectForm').action = `/adviser/activities/${activityId}/note`;
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejectComments').value = '';
}

// Close modals when clicking outside
document.getElementById('noteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeNoteModal();
    }
});

document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRejectModal();
    }
});


</script>
@endsection
