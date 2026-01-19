@extends('layouts.sidebar')

@section('title', 'Pending Approvals')
@section('page-title', 'Pending Approvals')

@section('content')
<div class="pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-green-600 to-green-700 border border-yellow-600 overflow-hidden shadow-sm sm:rounded-xl mb-6">
            <div class="p-6 border-b border-yellow-600">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-sticky-note text-yellow-300 mr-2"></i>
                    Activities Pending Adviser Approval
                </h3>
                <p class="text-sm text-yellow-200 mt-1">Activities from your department awaiting adviser review and approval</p>
            </div>
        </div>

        <!-- Activities Table -->
        <div class="bg-gradient-to-br from-green-50 to-yellow-50 border border-green-300 overflow-hidden shadow-sm sm:rounded-xl">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-green-300">
                    <thead class="bg-green-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Activity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Submitted By</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Submitted</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-green-200">
                        @forelse($activities as $activity)
                            <tr class="hover:bg-green-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-green-900">{{ $activity->title }}</div>
                                        <div class="text-sm text-green-700">{{ Str::limit($activity->description ?? 'No description', 50) }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-green-900">{{ $activity->user->name }}</div>
                                    <div class="text-sm text-green-700">{{ $activity->user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($activity->type === 'in-campus') bg-green-200 text-green-800
                                        @else bg-yellow-200 text-yellow-800
                                        @endif">
                                        {{ ucfirst(str_replace('-', ' ', $activity->type)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-green-700">
                                    {{ $activity->activity_date->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-green-700">
                                    {{ $activity->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('adviser.show-activity', $activity) }}" 
                                           class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-600 text-white hover:bg-green-700 shadow-sm hover:shadow-md transition-all duration-200">
                                            <i class="fas fa-check mr-1"></i>
                                            Review Activity
                                        </a>
                                        <button onclick="openNoteModal({{ $activity->id }}, '{{ addslashes($activity->title) }}')" class="text-green-600 hover:text-green-900">
                                            <i class="fas fa-sticky-note"></i>
                                        </button>
                                        <button onclick="openRejectModal({{ $activity->id }}, '{{ addslashes($activity->title) }}')" class="text-yellow-600 hover:text-yellow-900">
                                            <i class="fas fa-times-circle"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-green-600">
                                    <i class="fas fa-inbox text-4xl text-green-400 mb-4"></i>
                                    <p class="text-green-600">No activities pending adviser approval.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($activities->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $activities->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Note Modal -->
<div id="noteModal" class="fixed inset-0 bg-green-900 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border border-green-300 w-96 shadow-lg rounded-md bg-gradient-to-br from-green-50 to-yellow-50">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-200">
                <i class="fas fa-sticky-note text-green-700 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-green-800 mt-2">Note & Forward Activity</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-green-700" id="noteActivityTitle">
                    Are you sure you want to note and forward this activity to the Dean?
                </p>
                <form id="noteForm" method="POST" class="mt-4">
                    @csrf
                    <input type="hidden" name="action" value="note">
                    <div class="mb-4">
                        <label for="noteComments" class="block text-sm font-medium text-green-800 mb-2">Notes (Optional)</label>
                        <textarea name="notes" id="noteComments" rows="3" class="block w-full rounded-lg border-green-300 shadow-sm focus:border-green-600 focus:ring-green-600" placeholder="Add any notes for this activity..."></textarea>
                    </div>
                    <div class="flex justify-center space-x-3">
                        <button type="button" onclick="closeNoteModal()" class="px-4 py-2 bg-yellow-300 text-yellow-800 rounded-lg hover:bg-yellow-400">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            Note & Forward
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-yellow-900 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border border-yellow-300 w-96 shadow-lg rounded-md bg-gradient-to-br from-yellow-50 to-green-50">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-200">
                <i class="fas fa-times text-yellow-700 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-yellow-800 mt-2">Reject Activity</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-yellow-700" id="rejectActivityTitle">
                    Are you sure you want to reject this activity?
                </p>
                <form id="rejectForm" method="POST" class="mt-4">
                    @csrf
                    <input type="hidden" name="action" value="reject">
                    <div class="mb-4">
                        <label for="rejectComments" class="block text-sm font-medium text-yellow-800 mb-2">Rejection Reason <span class="text-yellow-600">*</span></label>
                        <textarea name="notes" id="rejectComments" rows="3" required class="block w-full rounded-lg border-yellow-300 shadow-sm focus:border-yellow-600 focus:ring-yellow-600" placeholder="Please provide a reason for rejection..."></textarea>
                    </div>
                    <div class="flex justify-center space-x-3">
                        <button type="button" onclick="closeRejectModal()" class="px-4 py-2 bg-green-300 text-green-800 rounded-lg hover:bg-green-400">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">
                            Reject Activity
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
