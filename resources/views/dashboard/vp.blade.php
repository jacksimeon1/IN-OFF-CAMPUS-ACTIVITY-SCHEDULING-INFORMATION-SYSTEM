@extends('layouts.sidebar')

@section('title', 'VP Dashboard')
@section('page-title', 'Vice President for Academics Dashboard')
@section('page-subtitle', 'Final approval authority and academic quality assurance oversight')

@section('content')
<div class="py-4">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- VP Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Pending Final Approvals -->
            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 border border-yellow-200 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-crown text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-yellow-700">Pending Final Approvals</p>
                            <p class="text-2xl font-bold text-yellow-800">{{ $pendingFinalApprovals }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Activities -->
            <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-clipboard-list text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-green-700">Total Activities</p>
                            <p class="text-2xl font-bold text-green-800">{{ $totalActivities }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Approved Activities -->
            <div class="bg-gradient-to-br from-green-50 to-yellow-50 border border-green-200 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-check-circle text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-green-700">Activities Approved</p>
                            <p class="text-2xl font-bold text-green-800">{{ $approvedActivities }}</p>
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
                            <p class="text-2xl font-bold text-yellow-800">{{ $thisMonthActivities }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Activities Awaiting VP Final Approval -->
            <div class="bg-gradient-to-br from-yellow-50 to-white border border-yellow-200 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-yellow-200 bg-yellow-50">
                    <h3 class="text-lg font-semibold text-yellow-800 mb-4 flex items-center">
                        <i class="fas fa-gavel mr-2 text-yellow-600"></i>
                        Activities Awaiting Your Final Approval
                    </h3>
                </div>
                <div class="p-6">
                    
                    @if($activitiesAwaitingFinalApproval->count() > 0)
                        <div class="space-y-4">
                            @foreach($activitiesAwaitingFinalApproval as $activity)
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
                                                <i class="fas fa-users mr-1"></i>
                                                {{ number_format($activity->expected_participants) }} participants
                                            </p>
                                            @if($activity->budget)
                                                <p class="text-sm text-green-600">
                                                    <i class="fas fa-dollar-sign mr-1"></i>
                                                    ₱{{ number_format($activity->budget, 2) }}
                                                </p>
                                            @endif
                                            
                                            <!-- Approval Progress -->
                                            <div class="mt-2">
                                                <div class="flex items-center space-x-1 text-xs">
                                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded">✓ Adviser</span>
                                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded">✓ Dean</span>
                                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded">✓ PSG</span>
                                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded">✓ Director</span>
                                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded">⏳ VP</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex flex-col items-end space-y-2">
                                            <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">
                                                Final Approval Stage
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

                        @if($activitiesAwaitingFinalApproval->count() >= 10)
                            <div class="mt-4 text-center">
                                <a href="{{ route('vp.pending-approvals') }}" class="text-green-600 hover:text-green-800 font-medium">
                                    View All Pending Final Approvals →
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-check-circle text-green-500 text-4xl mb-4"></i>
                            <p class="text-yellow-600">No activities awaiting your final approval</p>
                            <p class="text-sm text-yellow-500">All activities have been processed</p>
                        </div>
                    @endif
                </div>
                </div>
            </div>

            <!-- Recent Institutional Activities -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-2 border-green-500">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-green-900 mb-4">
                        <i class="fas fa-history mr-2"></i>
                        Recent Executive Decisions
                    </h3>
                    
                    @if($recentExecutiveDecisions->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentExecutiveDecisions as $activity)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900">{{ $activity->title }}</h4>
                                            <p class="text-sm text-gray-600">{{ $activity->organization }}</p>
                                            <p class="text-sm text-gray-500">
                                                <i class="fas fa-calendar mr-1"></i>
                                                {{ $activity->activity_date->format('M d, Y') }}
                                            </p>
                                            @if($activity->vp_approved_at)
                                                <p class="text-sm text-gray-500">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    Decided: {{ $activity->vp_approved_at->format('M d, Y g:i A') }}
                                                </p>
                                            @endif
                                        </div>
                                        <div class="flex flex-col items-end">
                                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                                {{ $activity->workflow_status === 'approved_by_vp' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ $activity->workflow_status === 'approved_by_vp' ? 'Approved' : 'Rejected' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-gavel text-gray-400 text-4xl mb-4"></i>
                            <p class="text-gray-600">No recent executive decisions</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- VP Guidelines -->
        <div class="mt-8 bg-gradient-to-br from-green-50 to-yellow-50 border border-green-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-green-800 mb-4 flex items-center">
                <i class="fas fa-info-circle mr-2 text-green-600"></i>
                Vice President Final Approval Guidelines
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-medium text-green-700 mb-2">Review Criteria</h4>
                    <ul class="text-sm text-green-600 space-y-1">
                        <li>• Academic merit and educational value</li>
                        <li>• Alignment with institutional mission</li>
                        <li>• Student learning outcomes assessment</li>
                        <li>• Strategic institutional impact</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-medium text-green-700 mb-2">Action Items</h4>
                    <ul class="text-sm text-green-600 space-y-1">
                        <li>• Ensure academic quality standards</li>
                        <li>• Verify resource optimization</li>
                        <li>• Assess institutional alignment</li>
                        <li>• Provide final executive decision</li>
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
