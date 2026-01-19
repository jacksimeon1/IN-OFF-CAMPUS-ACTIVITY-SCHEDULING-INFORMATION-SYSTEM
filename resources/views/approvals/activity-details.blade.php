@extends('layouts.sidebar')

@section('title', 'Activity Details')
@section('page-title', 'Activity Details')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div class="flex items-center justify-between">
        <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to List
        </a>
        
        @if($activity->user_id === auth()->id() && $activity->workflow_status === 'draft')
            <a href="{{ route('activities.edit', $activity) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                <i class="fas fa-edit mr-2"></i>
                Edit Activity
            </a>
        @endif
    </div>

    <!-- Activity Header -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $activity->title }}</h1>
                    <p class="text-sm text-gray-600 mt-1">
                        Submitted by {{ $activity->user->name }} on {{ $activity->created_at->format('M d, Y g:i A') }}
                    </p>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @if($activity->workflow_status === 'approved_by_vp') bg-green-100 text-green-800
                        @elseif($activity->workflow_status === 'rejected') bg-red-100 text-red-800
                        @else bg-yellow-100 text-yellow-800 @endif">
                        {{ $activity->getCurrentApprovalStepName() }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Approval Workflow Actions -->
    @if($activity->canUserTakeAction(auth()->user()))
        <x-approval-workflow-actions :activity="$activity" />
    @endif

    <!-- Approval Workflow Status -->
    <x-approval-workflow-status :activity="$activity" />

    <!-- Activity Details -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Activity Information</h3>
        </div>
        
        <div class="p-6 space-y-6">
            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Activity Title</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $activity->title }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Activity Type</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $activity->type ?? 'Not specified' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Activity Date</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $activity->activity_date ? $activity->activity_date->format('M d, Y') : 'Not specified' }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Start Time</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $activity->start_time ?? 'Not specified' }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">End Date</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $activity->end_date ? $activity->end_date->format('M d, Y') : 'Not specified' }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">End Time</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $activity->end_time ?? 'Not specified' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Venue</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $activity->venue ?? 'Not specified' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Expected Participants</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $activity->expected_participants ?? 'Not specified' }}</p>
                </div>
            </div>

            <!-- Description -->
            @if($activity->description)
            <div>
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <div class="mt-1 text-sm text-gray-900 bg-gray-50 p-4 rounded-lg">
                    {!! nl2br(e($activity->description)) !!}
                </div>
            </div>
            @endif

            <!-- Objectives -->
            @if($activity->objectives)
            <div>
                <label class="block text-sm font-medium text-gray-700">Objectives</label>
                <div class="mt-1 text-sm text-gray-900 bg-gray-50 p-4 rounded-lg">
                    {!! nl2br(e($activity->objectives)) !!}
                </div>
            </div>
            @endif

            <!-- Budget -->
            @if($activity->budget)
            <div>
                <label class="block text-sm font-medium text-gray-700">Budget</label>
                <p class="mt-1 text-sm text-gray-900">₱{{ number_format($activity->budget, 2) }}</p>
            </div>
            @endif

            <!-- Budget Breakdown -->
            @if($activity->budget_breakdown)
            <div>
                <label class="block text-sm font-medium text-gray-700">Budget Breakdown</label>
                <div class="mt-1 text-sm text-gray-900 bg-gray-50 p-4 rounded-lg">
                    {!! nl2br(e($activity->budget_breakdown)) !!}
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Student Information -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Student Information</h3>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Student Officer Name</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $activity->user->name }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Student Officer ID</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $activity->user->student_id ?? 'Not specified' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">School</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $activity->user->department ?? 'Not specified' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Course</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $activity->user->course ?? 'Not specified' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Year Level</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $activity->user->year_level ?? 'Not specified' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $activity->user->email }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity History -->
    @if($activity->activityLogs && $activity->activityLogs->count() > 0)
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Activity History</h3>
        </div>
        
        <div class="p-6">
            <div class="flow-root">
                <ul role="list" class="-mb-8">
                    @foreach($activity->activityLogs->sortByDesc('created_at') as $log)
                        <li>
                            <div class="relative pb-8">
                                @if(!$loop->last)
                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                @endif
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                            <i class="fas fa-check text-white text-xs"></i>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-sm text-gray-500">
                                                {{ $log->comments ?? $log->action }}
                                                @if($log->user)
                                                    <span class="font-medium text-gray-900">by {{ $log->user->name }}</span>
                                                @endif
                                            </p>
                                        </div>
                                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                            {{ $log->created_at->format('M d, Y g:i A') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
