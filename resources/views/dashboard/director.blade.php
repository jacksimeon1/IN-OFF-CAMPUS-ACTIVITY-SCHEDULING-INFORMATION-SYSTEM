@extends('layouts.sidebar')

@section('title', 'Director Dashboard')
@section('page-title', 'Director of Student Affairs Dashboard')
@section('page-subtitle', 'Institutional policy compliance and student welfare oversight')

@section('content')
<div class="py-4">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Director Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Pending Endorsements -->
            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 border border-yellow-200 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-shield-alt text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-yellow-700">Pending Endorsements</p>
                            <p class="text-2xl font-bold text-yellow-800">{{ $pendingEndorsements }}</p>
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

            <!-- Endorsed Activities -->
            <div class="bg-gradient-to-br from-green-50 to-yellow-50 border border-green-200 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-stamp text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-green-700">Activities Endorsed</p>
                            <p class="text-2xl font-bold text-green-800">{{ $endorsedActivities }}</p>
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
            <!-- Activities Awaiting Director Endorsement -->
            <div class="bg-gradient-to-br from-yellow-50 to-white border border-yellow-200 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-yellow-200 bg-yellow-50">
                    <h3 class="text-lg font-semibold text-yellow-800 mb-4 flex items-center">
                        <i class="fas fa-clipboard-list mr-2 text-yellow-600"></i>
                        Activities Awaiting Your Endorsement
                    </h3>
                </div>
                <div class="p-6">
                    
                    @if($activitiesAwaitingEndorsement->count() > 0)
                        <div class="space-y-4">
                            @foreach($activitiesAwaitingEndorsement as $activity)
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
                                                <i class="fas fa-map-marker-alt mr-1"></i>
                                                {{ $activity->location }}
                                            </p>
                                            @if($activity->type === 'off-campus')
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-orange-100 text-orange-800 rounded-full mt-1">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                                    Off-Campus Activity
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex flex-col items-end space-y-2">
                                            <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">
                                                Awaiting Director Endorsement
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

                        @if($activitiesAwaitingEndorsement->count() >= 10)
                            <div class="mt-4 text-center">
                                <a href="{{ route('director.activities') }}" class="text-green-600 hover:text-green-800 font-medium">
                                    View All Pending Endorsements →
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-check-circle text-green-500 text-4xl mb-4"></i>
                            <p class="text-yellow-600">No activities awaiting your endorsement</p>
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
                        Recent Institutional Activities
                    </h3>
                    
                    @if($recentInstitutionalActivities->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentInstitutionalActivities as $activity)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900">{{ $activity->title }}</h4>
                                            <p class="text-sm text-gray-600">{{ $activity->organization }}</p>
                                            <p class="text-sm text-gray-500">
                                                <i class="fas fa-calendar mr-1"></i>
                                                {{ $activity->activity_date->format('M d, Y') }}
                                            </p>
                                            @if($activity->budget)
                                                <p class="text-sm text-gray-500">
                                                    <i class="fas fa-dollar-sign mr-1"></i>
                                                    ₱{{ number_format($activity->budget, 2) }}
                                                </p>
                                            @endif
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
                            <i class="fas fa-building text-gray-400 text-4xl mb-4"></i>
                            <p class="text-gray-600">No recent institutional activities</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Director Guidelines -->
        <div class="mt-8 bg-gradient-to-br from-green-50 to-yellow-50 border border-green-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-green-800 mb-4 flex items-center">
                <i class="fas fa-info-circle mr-2 text-green-600"></i>
                Director Endorsement Guidelines
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-medium text-green-700 mb-2">Review Criteria</h4>
                    <ul class="text-sm text-green-600 space-y-1">
                        <li>• Student safety and welfare protocols</li>
                        <li>• Institutional risk assessment</li>
                        <li>• Budget and resource allocation review</li>
                        <li>• External partnership compliance</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-medium text-green-700 mb-2">Action Items</h4>
                    <ul class="text-sm text-green-600 space-y-1">
                        <li>• Verify institutional policy compliance</li>
                        <li>• Assess student welfare considerations</li>
                        <li>• Review academic calendar alignment</li>
                        <li>• Evaluate institutional reputation impact</li>
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
