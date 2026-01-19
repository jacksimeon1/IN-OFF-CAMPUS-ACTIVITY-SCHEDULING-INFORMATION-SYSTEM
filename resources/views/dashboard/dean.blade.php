@extends('layouts.sidebar')

@section('title', 'Dean Dashboard')
@section('page-title', 'Dean/Unit Head Dashboard')
@section('page-subtitle', 'Academic review and departmental activity oversight')

@push('scripts')
<script>
// Real-time dashboard updates
let updateInterval;

function updateDashboard() {
    fetch('{{ route("dean.dashboard") }}', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        // Update statistics
        if (data.pendingDeanNotes !== undefined) {
            document.querySelector('#pending-dean-notes').textContent = data.pendingDeanNotes;
        }
        if (data.departmentActivities !== undefined) {
            document.querySelector('#department-activities').textContent = data.departmentActivities;
        }
        if (data.notedActivities !== undefined) {
            document.querySelector('#noted-activities').textContent = data.notedActivities;
        }
        if (data.thisMonthActivities !== undefined) {
            document.querySelector('#this-month-activities').textContent = data.thisMonthActivities;
        }

        // Update last refresh time
        document.querySelector('#last-updated').textContent = new Date().toLocaleTimeString();

        // Update activities lists
        updateActivitiesLists();
    })
    .catch(error => {
        console.log('Dashboard update failed:', error);
    });
}

function updateActivitiesLists() {
    fetch('{{ route("dean.dashboard.activities") }}', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        // Update activities awaiting note
        const awaitingNoteContainer = document.querySelector('#activities-awaiting-note');
        if (awaitingNoteContainer && data.activitiesAwaitingNote) {
            if (data.activitiesAwaitingNote.length > 0) {
                let html = '<div class="space-y-4">';
                data.activitiesAwaitingNote.forEach(activity => {
                    html += `
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900">${activity.title}</h4>
                                    <p class="text-sm text-gray-600">${activity.organization || ''}</p>
                                    <p class="text-sm text-gray-500">
                                        <i class="fas fa-calendar mr-1"></i>
                                        ${activity.activity_date}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        <i class="fas fa-user mr-1"></i>
                                        ${activity.user_name}
                                    </p>
                                </div>
                                <div class="flex flex-col items-end space-y-2">
                                    <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                        Awaiting Dean Review
                                    </span>
                                    <a href="${activity.review_url}"
                                       class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                                        <i class="fas fa-eye mr-1"></i>
                                        Review
                                    </a>
                                </div>
                            </div>
                        </div>
                    `;
                });
                html += '</div>';

                if (data.activitiesAwaitingNote.length >= 10) {
                    html += `
                        <div class="mt-4 text-center">
                            <a href="{{ route('dean.pending-reviews') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                View All Pending Reviews →
                            </a>
                        </div>
                    `;
                }

                awaitingNoteContainer.innerHTML = html;
            } else {
                awaitingNoteContainer.innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-check-circle text-green-500 text-4xl mb-4"></i>
                        <p class="text-gray-600">No activities awaiting your review</p>
                        <p class="text-sm text-gray-500">All activities have been processed</p>
                    </div>
                `;
            }
        }

        // Update recent department activities
        const recentActivitiesContainer = document.querySelector('#recent-department-activities');
        if (recentActivitiesContainer && data.recentDepartmentActivities) {
            if (data.recentDepartmentActivities.length > 0) {
                let html = '<div class="space-y-4">';
                data.recentDepartmentActivities.forEach(activity => {
                    html += `
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900">${activity.title}</h4>
                                    <p class="text-sm text-gray-600">${activity.organization || ''}</p>
                                    <p class="text-sm text-gray-500">
                                        <i class="fas fa-calendar mr-1"></i>
                                        ${activity.activity_date}
                                    </p>
                                </div>
                                <div class="flex flex-col items-end">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full ${activity.status_class}">
                                        ${activity.approval_step_name}
                                    </span>
                                </div>
                            </div>
                        </div>
                    `;
                });
                html += '</div>';
                recentActivitiesContainer.innerHTML = html;
            } else {
                recentActivitiesContainer.innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-university text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-600">No recent department activities</p>
                    </div>
                `;
            }
        }
    })
    .catch(error => {
        console.log('Activities update failed:', error);
    });
}

function startRealTimeUpdates() {
    // Update every 30 seconds
    updateInterval = setInterval(updateDashboard, 30000);

    // Add visual indicator
    const indicator = document.querySelector('#real-time-indicator');
    if (indicator) {
        indicator.classList.add('animate-pulse');
        indicator.style.display = 'inline-flex';
    }
}

function stopRealTimeUpdates() {
    if (updateInterval) {
        clearInterval(updateInterval);
        updateInterval = null;
    }

    const indicator = document.querySelector('#real-time-indicator');
    if (indicator) {
        indicator.classList.remove('animate-pulse');
        indicator.style.display = 'none';
    }
}

// Start real-time updates when page loads
document.addEventListener('DOMContentLoaded', function() {
    startRealTimeUpdates();

    // Add toggle button functionality
    const toggleBtn = document.querySelector('#toggle-realtime');
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            if (updateInterval) {
                stopRealTimeUpdates();
                this.textContent = 'Enable Real-time';
                this.classList.remove('bg-green-600', 'hover:bg-green-700');
                this.classList.add('bg-gray-600', 'hover:bg-gray-700');
            } else {
                startRealTimeUpdates();
                this.textContent = 'Disable Real-time';
                this.classList.remove('bg-gray-600', 'hover:bg-gray-700');
                this.classList.add('bg-green-600', 'hover:bg-green-700');
            }
        });
    }
});

// Clean up when leaving page
window.addEventListener('beforeunload', function() {
    stopRealTimeUpdates();
});
</script>
@endpush

@section('content')
<div class="py-4">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Real-time Status Bar -->
        <div class="bg-gradient-to-r from-green-50 to-yellow-50 border border-green-200 overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-4 border-b border-green-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-university text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-green-800">Dean Dashboard</h3>
                            <div id="real-time-indicator" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <div class="w-2 h-2 bg-green-400 rounded-full mr-1.5 animate-pulse"></div>
                                Live Updates
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-500">
                            Last updated: <span id="last-updated">{{ now()->format('g:i:s A') }}</span>
                        </span>
                        <button id="toggle-realtime" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Disable Real-time
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Dean Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Pending Dean Notes -->
            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 border border-yellow-200 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-clipboard-check text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-yellow-700">Pending Reviews</p>
                            <p class="text-2xl font-bold text-yellow-800" id="pending-dean-notes">{{ $pendingDeanNotes }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Department Activities -->
            <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-university text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-green-700">Department Activities</p>
                            <p class="text-2xl font-bold text-green-800" id="department-activities">{{ $departmentActivities }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Noted Activities -->
            <div class="bg-gradient-to-br from-green-50 to-yellow-50 border border-green-200 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-check-circle text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-green-700">Activities Noted</p>
                            <p class="text-2xl font-bold text-green-800" id="noted-activities">{{ $notedActivities }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- This Month -->
            <div class="bg-gradient-to-br from-yellow-50 to-green-50 border border-yellow-200 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-calendar-alt text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-yellow-700">This Month</p>
                            <p class="text-2xl font-bold text-yellow-800" id="this-month-activities">{{ $thisMonthActivities }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Activities Awaiting Dean Note -->
            <div class="bg-gradient-to-br from-yellow-50 to-white border border-yellow-200 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-yellow-200 bg-yellow-50">
                    <h3 class="text-lg font-semibold text-yellow-800 mb-4 flex items-center">
                        <i class="fas fa-clipboard-list mr-2 text-yellow-600"></i>
                        Activities Awaiting Your Review
                    </h3>
                </div>
                <div class="p-6">
                    
                    <div id="activities-awaiting-note">
                    @if($activitiesAwaitingNote->count() > 0)
                        <div class="space-y-4">
                            @foreach($activitiesAwaitingNote as $activity)
                                <div class="border border-yellow-200 rounded-lg p-4 hover:bg-yellow-50 transition-colors">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <h4 class="font-medium text-green-900">{{ $activity->title }}</h4>
                                            <p class="text-sm text-green-700">{{ $activity->organization }}</p>
                                            <p class="text-sm text-green-600">
                                                <i class="fas fa-calendar mr-1"></i>
                                                {{ $activity->activity_date->format('M d, Y') }}
                                            </p>
                                            <p class="text-sm text-green-600">
                                                <i class="fas fa-user mr-1"></i>
                                                {{ $activity->user->name }}
                                            </p>
                                        </div>
                                        <div class="flex flex-col items-end space-y-2">
                                            <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">
                                                Awaiting Dean Review
                                            </span>
                                            <a href="{{ route('workflow.approval.form', $activity) }}"
                                               class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 transition-colors">
                                                <i class="fas fa-eye mr-1"></i>
                                                Review
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if($activitiesAwaitingNote->count() >= 10)
                            <div class="mt-4 text-center">
                                <a href="{{ route('dean.pending-reviews') }}" class="text-green-600 hover:text-green-800 font-medium">
                                    View All Pending Reviews →
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-check-circle text-green-500 text-4xl mb-4"></i>
                            <p class="text-yellow-600">No activities awaiting your review</p>
                            <p class="text-sm text-yellow-500">All activities have been processed</p>
                        </div>
                    @endif
                    </div>
                </div>
                </div>
            </div>

            <!-- Recent Department Activities -->
            <div class="bg-gradient-to-br from-green-50 to-white border border-green-200 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-green-200 bg-green-50">
                    <h3 class="text-lg font-semibold text-green-800 mb-4 flex items-center">
                        <i class="fas fa-history mr-2 text-green-600"></i>
                        Recent Department Activities
                    </h3>
                </div>
                <div class="p-6">
                    
                    <div id="recent-department-activities">
                    @if($recentDepartmentActivities->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentDepartmentActivities as $activity)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900">{{ $activity->title }}</h4>
                                            <p class="text-sm text-gray-600">{{ $activity->organization }}</p>
                                            <p class="text-sm text-gray-500">
                                                <i class="fas fa-calendar mr-1"></i>
                                                {{ $activity->activity_date->format('M d, Y') }}
                                            </p>
                                        </div>
                                        <div class="flex flex-col items-end">
                                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                                {{ $activity->workflow_status === 'approved_by_vp' ? 'bg-green-100 text-green-800' : 
                                                   ($activity->workflow_status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                {{ $activity->getCurrentApprovalStepName() }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-university text-gray-400 text-4xl mb-4"></i>
                            <p class="text-gray-600">No recent department activities</p>
                        </div>
                    @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Dean Guidelines -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-4">
                <i class="fas fa-info-circle mr-2"></i>
                Dean Review Guidelines
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-medium text-blue-800 mb-2">Review Criteria</h4>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li>• Academic alignment with department objectives</li>
                        <li>• Resource allocation and budget appropriateness</li>
                        <li>• Faculty and staff involvement requirements</li>
                        <li>• Compliance with institutional policies</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-medium text-blue-800 mb-2">Action Items</h4>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li>• Review activity objectives and outcomes</li>
                        <li>• Assess budget and resource needs</li>
                        <li>• Verify academic calendar alignment</li>
                        <li>• Provide constructive feedback</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .border-l-4 {
        border-left-width: 4px;
    }
</style>
@endpush
