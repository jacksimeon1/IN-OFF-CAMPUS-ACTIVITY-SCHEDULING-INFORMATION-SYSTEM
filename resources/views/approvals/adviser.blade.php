@extends('layouts.app')

@section('title', 'Review Activity')

<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        <i class="fas fa-clipboard-check mr-2"></i> {{ __('Review Activity') }}
    </h2>
</x-slot>

<style>
.button {
    position: relative;
    width: 150px;
    height: 40px;
    cursor: pointer;
    display: flex;
    align-items: center;
    border: 1px solid #34974d;
    background-color: #3aa856;
}

.button, .button__icon, .button__text {
    transition: all 0.3s;
}

.button .button__text {
    transform: translateX(30px);
    color: #fff;
    font-weight: 600;
}

.button .button__icon {
    position: absolute;
    transform: translateX(109px);
    height: 100%;
    width: 39px;
    background-color: #34974d;
    display: flex;
    align-items: center;
    justify-content: center;
}

.button .svg {
    width: 30px;
    stroke: #fff;
}

.button:hover {
    background: #34974d;
}

.button:hover .button__text {
    color: transparent;
}

.button:hover .button__icon {
    width: 148px;
    transform: translateX(0);
}

.button:active .button__icon {
    background-color: #2e8644;
}

.button:active {
    border: 1px solid #2e8644;
}
</style>

<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Activity Summary -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Activity Summary</h3>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-700">Title</h4>
                        <p class="mt-1 text-gray-900">{{ $activity->title }}</p>
                    </div>

                    <div>
                        <h4 class="text-sm font-medium text-gray-700">Submitted By</h4>
                        <p class="mt-1 text-gray-900">{{ $activity->user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $activity->user->email }}</p>
                    </div>

                    <div>
                        <h4 class="text-sm font-medium text-gray-700">Activity Date</h4>
                        <p class="mt-1 text-gray-900">{{ $activity->activity_date->format('F d, Y') }}</p>
                        <p class="text-sm text-gray-500">
                            @if($activity->start_time && $activity->end_time)
                                {{ $activity->start_time->format('g:i A') }} - {{ $activity->end_time->format('g:i A') }}
                            @else
                                Time not specified
                            @endif
                        </p>
                    </div>

                    <div>
                        <h4 class="text-sm font-medium text-gray-700">Location</h4>
                        <p class="mt-1 text-gray-900">{{ $activity->location }}</p>
                    </div>

                    <div class="md:col-span-2">
                        <h4 class="text-sm font-medium text-gray-700">Description</h4>
                        <p class="mt-1 text-gray-900">{{ $activity->description }}</p>
                    </div>

                    <div class="md:col-span-2">
                        <h4 class="text-sm font-medium text-gray-700">Objectives</h4>
                        <p class="mt-1 text-gray-900">{{ $activity->objectives }}</p>
                    </div>

                    @if($activity->budget)
                        <div>
                            <h4 class="text-sm font-medium text-gray-700">Budget</h4>
                            <p class="mt-1 text-gray-900">₱{{ number_format($activity->budget, 2) }}</p>
                        </div>
                    @endif

                    @if($activity->expected_participants)
                        <div>
                            <h4 class="text-sm font-medium text-gray-700">Expected Participants</h4>
                            <p class="mt-1 text-gray-900">{{ $activity->expected_participants }} people</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Conflict Check -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">Conflict Check</h3>
                    <div class="mt-2 text-sm text-yellow-700" id="conflict-results">
                        <p>Checking for conflicts...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Decision Support Information -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Decision Support Information</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ul class="list-disc list-inside space-y-1">
                            <li>Activity is scheduled {{ $activity->activity_date->diffForHumans() }}</li>
                            @if($activity->budget && $activity->budget < 1000)
                                <li class="text-yellow-600">⚠️ Budget appears to be low for this type of activity</li>
                            @endif
                            @if($activity->activity_date->diffInDays(now()) < 5)
                                <li class="text-orange-600">⚠️ Activity is scheduled less than a week from now</li>
                            @endif
                            <li>Activity type: {{ ucfirst(str_replace('-', ' ', $activity->type)) }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Review Form -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Adviser Review</h3>
            </div>
            
            <div class="p-6">
                <form method="POST" action="{{ route('approval.adviser.submit', $activity) }}" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-4">Decision</label>
                        <div class="space-y-3">
                            <label class="flex items-center">
                                <input type="radio" name="action" value="recommend" class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300" required>
                                <span class="ml-2 text-sm text-gray-900">
                                    <i class="fas fa-thumbs-up text-green-500 mr-1"></i>
                                    Recommend for OSA Approval
                                </span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="action" value="reject" class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300" required>
                                <span class="ml-2 text-sm text-gray-900">
                                    <i class="fas fa-thumbs-down text-red-500 mr-1"></i>
                                    Reject Activity
                                </span>
                            </label>
                        </div>
                        @error('action')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="comments" class="block text-sm font-medium text-gray-700">Comments *</label>
                        <textarea name="comments" id="comments" rows="4" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Provide your comments and feedback...">{{ old('comments') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Please provide detailed feedback for the user and OSA.</p>
                        @error('comments')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('activities.show', $activity) }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">
                            Cancel
                        </a>
                        <button type="submit" class="button">
                            <span class="button__text">Submit Review</span>
                            <span class="button__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" viewBox="0 0 24 24" stroke-width="2" stroke-linejoin="round" stroke-linecap="round" stroke="currentColor" height="24" fill="none" class="svg">
                                    <line y2="19" y1="5" x2="12" x1="12"></line>
                                    <line y2="12" y1="12" x2="19" x1="5"></line>
                                </svg>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Check for conflicts when page loads
    document.addEventListener('DOMContentLoaded', function() {
        fetch(`{{ route('activities.conflicts', $activity) }}`)
            .then(response => response.json())
            .then(data => {
                const resultsDiv = document.getElementById('conflict-results');
                let html = '';
                
                if (data.conflicts.length > 0) {
                    html += '<div class="text-red-600 font-medium">⚠️ Conflicts Found:</div>';
                    html += '<ul class="list-disc list-inside mt-1">';
                    data.conflicts.forEach(conflict => {
                        html += `<li class="text-red-600">${conflict}</li>`;
                    });
                    html += '</ul>';
                }
                
                if (data.warnings.length > 0) {
                    if (html) html += '<div class="mt-2"></div>';
                    html += '<div class="text-yellow-600 font-medium">⚠️ Warnings:</div>';
                    html += '<ul class="list-disc list-inside mt-1">';
                    data.warnings.forEach(warning => {
                        html += `<li class="text-yellow-600">${warning}</li>`;
                    });
                    html += '</ul>';
                }
                
                if (!html) {
                    html = '<div class="text-green-600">✅ No conflicts or warnings found.</div>';
                }
                
                resultsDiv.innerHTML = html;
            })
            .catch(error => {
                document.getElementById('conflict-results').innerHTML = '<div class="text-red-600">Error checking conflicts.</div>';
            });
    });
</script>
@endpush
