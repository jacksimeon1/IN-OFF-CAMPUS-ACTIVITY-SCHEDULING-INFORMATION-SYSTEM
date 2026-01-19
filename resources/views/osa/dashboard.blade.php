@extends('layouts.sidebar')

@section('title', 'OSA Dashboard')
@section('page-title', 'Office of Student Affairs Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Activities -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-teal-500">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-teal-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-list-alt text-teal-600"></i>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <div class="text-sm font-medium text-gray-500">Total Activities</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $totalActivities }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Approved Activities -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-green-500">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600"></i>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <div class="text-sm font-medium text-gray-500">Approved Activities</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $approvedActivities }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Activities -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-yellow-500">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-clock text-yellow-600"></i>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <div class="text-sm font-medium text-gray-500">Pending Activities</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $pendingActivities }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- This Month Activities -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-blue-500">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-blue-600"></i>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <div class="text-sm font-medium text-gray-500">This Month</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $thisMonthActivities }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('osa.activities') }}" class="flex items-center p-4 bg-teal-50 border border-teal-200 rounded-lg hover:bg-teal-100 transition-colors">
                    <div class="flex-shrink-0">
                        <i class="fas fa-list text-teal-600 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <div class="text-sm font-medium text-teal-900">All Activities</div>
                        <div class="text-xs text-teal-700">View and monitor activities</div>
                    </div>
                </a>

                <a href="{{ route('osa.profile') }}" class="flex items-center p-4 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors">
                    <div class="flex-shrink-0">
                        <i class="fas fa-user-cog text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <div class="text-sm font-medium text-blue-900">Profile Settings</div>
                        <div class="text-xs text-blue-700">Update your information</div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Approved Activities and Recent Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Approved Activities -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Recent Approved Activities</h3>
            </div>
            <div class="p-6">
                @if($recentApprovedActivities->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentApprovedActivities as $activity)
                            <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-200">
                                <div class="flex-1">
                                    <div class="text-sm font-medium text-gray-900">{{ $activity->title }}</div>
                                    <div class="text-xs text-gray-500">
                                        By {{ $activity->user->name }} • Approved {{ $activity->vp_approved_at ? $activity->vp_approved_at->format('M d, Y') : 'Recently' }}
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <a href="{{ route('osa.show-activity', $activity) }}" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 hover:bg-green-200">
                                        <i class="fas fa-eye mr-1"></i>
                                        View
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if($recentApprovedActivities->count() >= 10)
                        <div class="mt-4 text-center">
                            <a href="{{ route('osa.activities') }}" class="text-green-600 hover:text-green-700 text-sm font-medium">View all approved activities</a>
                        </div>
                    @endif
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-check-circle text-green-400 text-3xl mb-3"></i>
                        <p class="text-gray-500">No recently approved activities</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Recent Activities</h3>
            </div>
            <div class="p-6">
                @if($recentActivities->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentActivities as $activity)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex-1">
                                    <div class="text-sm font-medium text-gray-900">{{ $activity->title }}</div>
                                    <div class="text-xs text-gray-500">
                                        By {{ $activity->user->name }} • {{ $activity->created_at->format('M d, Y') }}
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($activity->workflow_status === 'approved_by_vp') bg-green-100 text-green-800
                                        @elseif($activity->workflow_status === 'rejected') bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ $activity->getCurrentApprovalStepName() }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4 text-center">
                        <a href="{{ route('osa.activities') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">View all activities</a>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-inbox text-gray-400 text-3xl mb-3"></i>
                        <p class="text-gray-500">No recent activities</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Student Affairs Information -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Student Affairs Overview</h3>
        </div>
        <div class="p-6">
            <div class="bg-gradient-to-r from-teal-50 to-cyan-50 border border-teal-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-teal-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-building text-teal-600 text-lg"></i>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <h6 class="text-sm font-medium text-gray-900">Office of Student Affairs</h6>
                        <p class="text-xs text-gray-600 mt-1">Coordinating student services and monitoring activity compliance</p>
                        <div class="mt-2 grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-teal-600 font-medium">Total Activities Monitored</p>
                                <p class="text-lg font-bold text-gray-900">{{ $totalActivities }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-teal-600 font-medium">Successfully Completed</p>
                                <p class="text-lg font-bold text-gray-900">{{ $approvedActivities }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-teal-100 text-teal-800">
                            <i class="fas fa-check-circle mr-1"></i>
                            Active
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
