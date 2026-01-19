@extends('layouts.sidebar')

@section('title', 'Pending Endorsements')
@section('page-title', 'Pending Endorsements')

@section('content')
<div class="pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-green-600 to-green-700 border border-yellow-600 overflow-hidden shadow-sm sm:rounded-xl mb-6">
            <div class="p-6 border-b border-yellow-600">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-stamp text-yellow-300 mr-2"></i>
                    Activities Pending Director Endorsement
                </h3>
                <p class="text-sm text-yellow-200 mt-1">Activities that have been reviewed by PSG and are awaiting Director endorsement</p>
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">PSG Reviewed</th>
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
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $activity->activity_date->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $activity->psg_reviewed_at ? $activity->psg_reviewed_at->format('M d, Y') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('director.show-activity', $activity) }}" class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button onclick="openEndorseModal({{ $activity->id }}, '{{ $activity->title }}')" class="text-green-600 hover:text-green-900">
                                            <i class="fas fa-stamp"></i>
                                        </button>
                                        <button onclick="openRejectModal({{ $activity->id }}, '{{ $activity->title }}')" class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-times-circle"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                                    <p class="text-gray-500">No activities pending Director endorsement.</p>
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

<!-- Endorse Modal -->
<div id="endorseModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100">
                <i class="fas fa-stamp text-blue-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-2">Endorse Activity</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500" id="endorseActivityTitle">
                    Are you sure you want to endorse this activity to the Vice President?
                </p>
                <form id="endorseForm" method="POST" class="mt-4">
                    @csrf
                    <input type="hidden" name="action" value="endorse">
                    <div class="mb-4">
                        <label for="endorseNotes" class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                        <textarea name="notes" id="endorseNotes" rows="3" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Add any notes for this endorsement..."></textarea>
                    </div>
                    <div class="flex justify-center space-x-3">
                        <button type="button" onclick="closeEndorseModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Endorse to VP
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
                        <label for="rejectNotes" class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason <span class="text-red-500">*</span></label>
                        <textarea name="notes" id="rejectNotes" rows="3" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" placeholder="Please provide a reason for rejection..."></textarea>
                    </div>
                    <div class="flex justify-center space-x-3">
                        <button type="button" onclick="closeRejectModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                            Reject Activity
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openEndorseModal(activityId, activityTitle) {
    document.getElementById('endorseActivityTitle').textContent = `Endorse "${activityTitle}" to the Vice President?`;
    document.getElementById('endorseForm').action = `/director/activities/${activityId}/endorse`;
    document.getElementById('endorseModal').classList.remove('hidden');
}

function closeEndorseModal() {
    document.getElementById('endorseModal').classList.add('hidden');
    document.getElementById('endorseNotes').value = '';
}

function openRejectModal(activityId, activityTitle) {
    document.getElementById('rejectActivityTitle').textContent = `Reject "${activityTitle}"?`;
    document.getElementById('rejectForm').action = `/director/activities/${activityId}/endorse`;
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejectNotes').value = '';
}

// Close modals when clicking outside
document.getElementById('endorseModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEndorseModal();
    }
});

document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRejectModal();
    }
});
</script>
@endsection
