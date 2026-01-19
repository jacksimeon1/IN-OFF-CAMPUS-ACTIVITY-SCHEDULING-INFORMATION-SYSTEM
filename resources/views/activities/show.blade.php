@extends('layouts.sidebar')

@section('title', 'Activity Details')
@section('page-title', 'Activity Details')
@section('page-subtitle', 'View detailed information about this activity')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Action Buttons -->
        <div class="flex justify-end space-x-2 mb-6">
            <a href="{{ route('activities.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                <i class="fas fa-arrow-left mr-2"></i> Back to List
            </a>
            @if(auth()->user()->isStudent() && $activity->user_id === auth()->id() && $activity->status === 'pending')
                <a href="{{ route('activities.edit', $activity) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                    <i class="fas fa-edit mr-2"></i> Edit
                </a>
            @endif
            @if(auth()->user()->isStudent() && $activity->user_id === auth()->id() && $activity->workflow_status === 'rejected')
                <a href="{{ route('activities.edit', $activity) }}" class="inline-flex items-center px-4 py-2 bg-yellow-100 border border-transparent rounded-md font-semibold text-xs text-yellow-800 uppercase tracking-widest hover:bg-yellow-200">
                    <i class="fas fa-edit mr-2"></i> Revise & Resubmit
                </a>
            @endif
        </div>

        <!-- Approval Workflow Actions -->
        <x-approval-workflow-actions :activity="$activity" />

        <!-- Approval Workflow Status -->
        <x-approval-workflow-status :activity="$activity" />

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg content-card">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">{{ $activity->title }}</h3>
                                <p class="text-sm text-gray-500 mt-1">Submitted by {{ $activity->user->name }}</p>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-{{ $activity->getStatusBadgeColor() }}-100 text-{{ $activity->getStatusBadgeColor() }}-800">
                                {{ ucfirst($activity->status) }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-700">School/Unit</h4>
                            <p class="mt-1 text-gray-900">{{ $activity->organization ?? 'Not specified' }}</p>
                        </div>

                        @if($activity->leaders)
                        <div>
                            <h4 class="text-sm font-medium text-gray-700">Leaders/Organizers</h4>
                            <p class="mt-1 text-gray-900">{{ $activity->leaders }}</p>
                        </div>
                        @endif

                        <div>
                            <h4 class="text-sm font-medium text-gray-700">Objectives/Purposes</h4>
                            <div class="mt-1 space-y-2">
                                @if($activity->objective_1)
                                    <div class="flex items-start">
                                        <span class="inline-flex items-center justify-center w-6 h-6 bg-green-100 text-green-800 text-xs font-medium rounded-full mr-3 mt-0.5">1</span>
                                        <p class="text-gray-900">{{ $activity->objective_1 }}</p>
                                    </div>
                                @endif
                                @if($activity->objective_2)
                                    <div class="flex items-start">
                                        <span class="inline-flex items-center justify-center w-6 h-6 bg-green-100 text-green-800 text-xs font-medium rounded-full mr-3 mt-0.5">2</span>
                                        <p class="text-gray-900">{{ $activity->objective_2 }}</p>
                                    </div>
                                @endif
                                @if($activity->objective_3)
                                    <div class="flex items-start">
                                        <span class="inline-flex items-center justify-center w-6 h-6 bg-green-100 text-green-800 text-xs font-medium rounded-full mr-3 mt-0.5">3</span>
                                        <p class="text-gray-900">{{ $activity->objective_3 }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if($activity->speakers)
                        <div>
                            <h4 class="text-sm font-medium text-gray-700">Speakers</h4>
                            <p class="mt-1 text-gray-900">{{ $activity->speakers }}</p>
                        </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h4 class="text-sm font-medium text-gray-700">Activity Type</h4>
                                <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $activity->type === 'in-campus' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                    {{ ucfirst(str_replace('-', ' ', $activity->type)) }}
                                </span>
                            </div>

                            @if($activity->organization)
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700">Organization</h4>
                                    <p class="mt-1 text-gray-900">{{ $activity->organization }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Schedule and Location -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg content-card">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Schedule & Location</h3>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <h4 class="text-sm font-medium text-gray-700">Date(s)</h4>
                                @if($activity->end_date && $activity->activity_date->format('Y-m-d') === $activity->end_date->format('Y-m-d'))
                                    <p class="mt-1 text-gray-900">{{ $activity->activity_date->format('F d, Y') }}</p>
                                    <p class="text-xs text-gray-500">Single day event</p>
                                @elseif($activity->end_date)
                                    <p class="mt-1 text-gray-900">{{ $activity->activity_date->format('F d, Y') }} - {{ $activity->end_date->format('F d, Y') }}</p>
                                    <p class="text-xs text-gray-500">{{ $activity->activity_date->diffInDays($activity->end_date) + 1 }} day(s)</p>
                                @else
                                    <p class="mt-1 text-gray-900">{{ $activity->activity_date->format('F d, Y') }}</p>
                                    <p class="text-xs text-gray-500">Single day event</p>
                                @endif
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-700">Time</h4>
                                <p class="mt-1 text-gray-900">
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
                        </div>
                    </div>
                </div>

                <!-- Additional Details -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg content-card">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Additional Details</h3>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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

                <!-- Supporting Documents -->
                <x-activity-attachments :activity="$activity" />

                <!-- Comments -->
                @if($activity->adviser_comments || $activity->osa_comments)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg content-card">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Comments</h3>
                        </div>
                        
                        <div class="p-6 space-y-4">
                            @if($activity->adviser_comments)
                                <div class="border-l-4 border-blue-500 pl-4">
                                    <h4 class="text-sm font-medium text-gray-700">Adviser Comments</h4>
                                    <p class="mt-1 text-gray-900">{{ $activity->adviser_comments }}</p>
                                    @if($activity->adviser_noted_at)
                                        <p class="text-xs text-gray-500 mt-1">{{ $activity->adviser_noted_at->format('M d, Y g:i A') }}</p>
                                    @endif
                                </div>
                            @endif

                            @if($activity->osa_comments)
                                <div class="border-l-4 border-green-500 pl-4">
                                    <h4 class="text-sm font-medium text-gray-700">OSA Comments</h4>
                                    <p class="mt-1 text-gray-900">{{ $activity->osa_comments }}</p>
                                    @if($activity->osa_reviewed_at)
                                        <p class="text-xs text-gray-500 mt-1">{{ $activity->osa_reviewed_at->format('M d, Y g:i A') }}</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">


                <!-- Approval History -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg content-card">
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
                                @if($activity->adviser_noted_at)
                                <li>
                                    <div class="relative pb-8">
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                    <i class="fas fa-check text-white text-xs"></i>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500">Noted by Adviser</p>
                                                    @if($activity->adviserNoted)
                                                        <p class="text-xs text-gray-400">{{ $activity->adviserNoted->name }}</p>
                                                    @endif
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                    {{ $activity->adviser_noted_at->format('M d, Y g:i A') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endif
                                @if($activity->dean_noted_at)
                                <li>
                                    <div class="relative pb-8">
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                    <i class="fas fa-check text-white text-xs"></i>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500">Noted by Dean/Unit Head</p>
                                                    @if($activity->deanNoted)
                                                        <p class="text-xs text-gray-400">{{ $activity->deanNoted->name }}</p>
                                                    @endif
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                    {{ $activity->dean_noted_at->format('M d, Y g:i A') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endif
                                @if($activity->psg_reviewed_at)
                                <li>
                                    <div class="relative pb-8">
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                    <i class="fas fa-check text-white text-xs"></i>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500">Reviewed by PSG Council Adviser</p>
                                                    @if($activity->psgReviewed)
                                                        <p class="text-xs text-gray-400">{{ $activity->psgReviewed->name }}</p>
                                                    @endif
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                    {{ $activity->psg_reviewed_at->format('M d, Y g:i A') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endif
                                @if($activity->director_endorsed_at)
                                <li>
                                    <div class="relative pb-8">
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                    <i class="fas fa-check text-white text-xs"></i>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500">Endorsed by Director of Student Affairs</p>
                                                    @if($activity->directorEndorsed)
                                                        <p class="text-xs text-gray-400">{{ $activity->directorEndorsed->name }}</p>
                                                    @endif
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                    {{ $activity->director_endorsed_at->format('M d, Y g:i A') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endif
                                @if($activity->vp_approved_at)
                                <li>
                                    <div class="relative">
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                    <i class="fas fa-check text-white text-xs"></i>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500">Final Approval by VP for Academics</p>
                                                    @if($activity->vpApproved)
                                                        <p class="text-xs text-gray-400">{{ $activity->vpApproved->name }}</p>
                                                    @endif
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                    {{ $activity->vp_approved_at->format('M d, Y g:i A') }}
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
    </div>
</div>


@endsection
