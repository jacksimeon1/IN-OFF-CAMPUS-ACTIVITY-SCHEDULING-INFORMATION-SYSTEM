@extends('layouts.admin')

@section('title', 'Activity Details')
@section('page-title', 'Activity Details')
@section('page-subtitle', 'View and manage activity information')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Header Actions -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $activity->title }}</h1>
                <p class="text-gray-600">Submitted by {{ $activity->user->name }}</p>
            </div>
            <div class="flex items-center">
                <a href="{{ route('admin.activities') }}" class="skew-button back-button">
                    <span>Back to List</span>
                </a>
                <a href="{{ route('admin.activities.edit-status', $activity) }}" class="skew-button edit-button">
                    <span>Edit Status</span>
                </a>
            </div>

            <style>
            .skew-button {
                background: #fff;
                border: none;
                padding: 10px 20px;
                display: inline-block;
                font-size: 15px;
                font-weight: 600;
                width: 140px;
                text-transform: uppercase;
                cursor: pointer;
                transform: skew(-21deg);
                position: relative;
                text-decoration: none;
                color: #000;
                overflow: hidden;
                z-index: 1;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .skew-button span {
                display: inline-block;
                transform: skew(21deg);
            }

            .skew-button::before {
                content: '';
                position: absolute;
                top: 0;
                bottom: 0;
                right: 100%;
                left: 0;
                background: rgb(20, 20, 20);
                opacity: 0;
                z-index: -1;
                transition: all 0.5s;
            }

            .skew-button:hover {
                color: #fff;
            }

            .skew-button:hover::before {
                left: 0;
                right: 0;
                opacity: 1;
            }

            .back-button {
                background: #fee2e2;
                color: #dc2626;
                margin-right: 20px;
            }

            .back-button::before {
                background: #dc2626;
            }

            .edit-button {
                background: #d1fae5;
                color: #059669;
                margin-left: 20px;
            }

            .edit-button::before {
                background: #059669;
            }
            </style>
        </div>

        <!-- Activity Information -->
        <div class="bg-white overflow-hidden shadow-green sm:rounded-xl mb-6 border-2 border-yellow-500" style="border: 3px solid #eab308 !important;">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-info-circle text-green-600 mr-2"></i>
                    Activity Information
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Activity Name</label>
                        <div class="p-3 bg-gray-50 rounded-lg text-sm text-gray-900">{{ $activity->title }}</div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Activity Type</label>
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($activity->type === 'in-campus') bg-blue-100 text-blue-800
                                @else bg-purple-100 text-purple-800
                                @endif">
                                {{ ucfirst(str_replace('-', ' ', $activity->type)) }}
                            </span>
                        </div>
                    </div>

                    @if($activity->organization)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">School/Unit</label>
                        <div class="p-3 bg-gray-50 rounded-lg text-sm text-gray-900">{{ $activity->organization }}</div>
                    </div>
                    @endif

                    @if($activity->leaders)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Leaders/Organizers</label>
                        <div class="p-3 bg-gray-50 rounded-lg text-sm text-gray-900">{{ $activity->leaders }}</div>
                    </div>
                    @endif
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date(s)</label>
                        <div class="p-3 bg-gray-50 rounded-lg text-sm text-gray-900">
                            @if($activity->activity_date->format('Y-m-d') === $activity->end_date->format('Y-m-d'))
                                {{ $activity->activity_date->format('F d, Y') }}
                                <span class="text-xs text-gray-500 block">Single day event</span>
                            @else
                                {{ $activity->activity_date->format('F d, Y') }} - {{ $activity->end_date->format('F d, Y') }}
                                <span class="text-xs text-gray-500 block">{{ $activity->activity_date->diffInDays($activity->end_date) + 1 }} day(s)</span>
                            @endif
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Time</label>
                        <div class="p-3 bg-gray-50 rounded-lg text-sm text-gray-900">
                            {{ \Carbon\Carbon::parse($activity->start_time)->format('g:i A') }} -
                            {{ \Carbon\Carbon::parse($activity->end_time)->format('g:i A') }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Venue</label>
                        <div class="p-3 bg-gray-50 rounded-lg text-sm text-gray-900">{{ $activity->location }}</div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($activity->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($activity->status === 'recommended') bg-blue-100 text-blue-800
                                @elseif($activity->status === 'approved') bg-green-100 text-green-800
                                @elseif($activity->status === 'rejected') bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($activity->status) }}
                            </span>
                        </div>
                    </div>

                    @if($activity->objective_1 || $activity->objective_2 || $activity->objective_3)
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Objectives/Purposes</label>
                        <div class="p-3 bg-gray-50 rounded-lg text-sm text-gray-900">
                            <div class="space-y-2">
                                @if($activity->objective_1)
                                    <div class="flex items-start">
                                        <span class="inline-flex items-center justify-center w-6 h-6 bg-green-100 text-green-800 text-xs font-medium rounded-full mr-3 mt-0.5">1</span>
                                        <span>{{ $activity->objective_1 }}</span>
                                    </div>
                                @endif
                                @if($activity->objective_2)
                                    <div class="flex items-start">
                                        <span class="inline-flex items-center justify-center w-6 h-6 bg-green-100 text-green-800 text-xs font-medium rounded-full mr-3 mt-0.5">2</span>
                                        <span>{{ $activity->objective_2 }}</span>
                                    </div>
                                @endif
                                @if($activity->objective_3)
                                    <div class="flex items-start">
                                        <span class="inline-flex items-center justify-center w-6 h-6 bg-green-100 text-green-800 text-xs font-medium rounded-full mr-3 mt-0.5">3</span>
                                        <span>{{ $activity->objective_3 }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($activity->expected_participants)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Number of Participants</label>
                        <div class="p-3 bg-gray-50 rounded-lg text-sm text-gray-900">{{ $activity->expected_participants }} people</div>
                    </div>
                    @endif

                    @if($activity->budget)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Program Budget</label>
                        <div class="p-3 bg-gray-50 rounded-lg text-sm text-gray-900">₱{{ number_format($activity->budget, 2) }}</div>
                    </div>
                    @endif

                    @if($activity->speakers)
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Speakers</label>
                        <div class="p-3 bg-gray-50 rounded-lg text-sm text-gray-900">{{ $activity->speakers }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Documents and Files Section -->
        @if($activity->budget_file || $activity->permit_file || $activity->supporting_documents)
        <div class="bg-white overflow-hidden shadow-green sm:rounded-xl mb-6 border-2 border-yellow-500" style="border: 3px solid #eab308 !important;">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-file-alt text-green-600 mr-2"></i>
                    Submitted Documents
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @if($activity->budget_file)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-file-invoice-dollar text-green-600 mr-2"></i>
                            <label class="text-sm font-medium text-gray-700">Attachment 1: Letter of Request</label>
                        </div>
                        <div class="text-xs text-gray-500 mb-2">{{ basename($activity->budget_file) }}</div>
                        <div class="flex space-x-2">
                            @php
                                $budgetExt = strtolower(pathinfo($activity->budget_file, PATHINFO_EXTENSION));
                                $filename = basename($activity->budget_file);
                            @endphp
                            @if($budgetExt === 'docx')
                                <a href="#" onclick="showDocxViewer('{{ route('attachments.streamDocx', ['filename' => $filename]) }}'); return false;" class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-eye mr-1"></i> View Document
                                </a>
                            @else
                                <a href="{{ route('attachments.view', ['filename' => $filename]) }}" target="_blank" class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-eye mr-1"></i> View Document
                                </a>
                            @endif
                            <a href="{{ route('admin.activities.download', [$activity, 'budget_file']) }}" class="inline-flex items-center px-3 py-2 bg-green-600 text-white text-xs font-medium rounded-lg hover:bg-green-700 transition-colors">
                                <i class="fas fa-download mr-1"></i>
                                Download
                            </a>
                        </div>
                    </div>
                    @endif

                    @if($activity->permit_file)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-file-contract text-green-600 mr-2"></i>
                            <label class="text-sm font-medium text-gray-700">Attachment 2: Planned Program of Activities</label>
                        </div>
                        <div class="text-xs text-gray-500 mb-2">{{ basename($activity->permit_file) }}</div>
                        <div class="flex space-x-2">
                            @php
                                $permitExt = strtolower(pathinfo($activity->permit_file, PATHINFO_EXTENSION));
                                $filename = basename($activity->permit_file);
                            @endphp
                            @if($permitExt === 'docx')
                                <a href="#" onclick="showDocxViewer('{{ route('attachments.streamDocx', ['filename' => $filename]) }}'); return false;" class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-eye mr-1"></i> View Document
                                </a>
                            @else
                                <a href="{{ route('attachments.view', ['filename' => $filename]) }}" target="_blank" class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-eye mr-1"></i> View Document
                                </a>
                            @endif
                            <a href="{{ route('admin.activities.download', [$activity, 'permit_file']) }}" class="inline-flex items-center px-3 py-2 bg-green-600 text-white text-xs font-medium rounded-lg hover:bg-green-700 transition-colors">
                                <i class="fas fa-download mr-1"></i>
                                Download
                            </a>
                        </div>
                    </div>
                    @endif

                    @if($activity->supporting_documents)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-file-archive text-green-600 mr-2"></i>
                            <label class="text-sm font-medium text-gray-700">Attachment 3: Letter for Attire/Costumes</label>
                        </div>
                        <div class="text-xs text-gray-500 mb-2">{{ basename($activity->supporting_documents) }}</div>
                        <div class="flex space-x-2">
                            @php
                                $suppExt = strtolower(pathinfo($activity->supporting_documents, PATHINFO_EXTENSION));
                                $filename = basename($activity->supporting_documents);
                            @endphp
                            @if($suppExt === 'docx')
                                <a href="#" onclick="showDocxViewer('{{ route('attachments.streamDocx', ['filename' => $filename]) }}'); return false;" class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-eye mr-1"></i> View Document
                                </a>
                            @else
                                <a href="{{ route('attachments.view', ['filename' => $filename]) }}" target="_blank" class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-eye mr-1"></i> View Document
                                </a>
                            @endif
                            <a href="{{ route('admin.activities.download', [$activity, 'supporting_documents']) }}" class="inline-flex items-center px-3 py-2 bg-green-600 text-white text-xs font-medium rounded-lg hover:bg-green-700 transition-colors">
                                <i class="fas fa-download mr-1"></i>
                                Download
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Student Information -->
        <div class="bg-white overflow-hidden shadow-green sm:rounded-xl mb-6 border-2 border-yellow-500" style="border: 3px solid #eab308 !important;">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-user text-green-600 mr-2"></i>
                    User Information
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                        <div class="p-3 bg-gray-50 rounded-lg text-sm text-gray-900">{{ $activity->user->name }}</div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <div class="p-3 bg-gray-50 rounded-lg text-sm text-gray-900">{{ $activity->user->email }}</div>
                    </div>
                    
                    @if($activity->user->student_id)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Student ID</label>
                        <div class="p-3 bg-gray-50 rounded-lg text-sm text-gray-900">{{ $activity->user->student_id }}</div>
                    </div>
                    @endif
                    
                    @if($activity->user->department)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                        <div class="p-3 bg-gray-50 rounded-lg text-sm text-gray-900">{{ $activity->user->department }}</div>
                    </div>
                    @endif
                    
                    @if($activity->user->course)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Course</label>
                        <div class="p-3 bg-gray-50 rounded-lg text-sm text-gray-900">{{ $activity->user->course }}</div>
                    </div>
                    @endif
                    
                    @if($activity->user->year_level)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Year Level</label>
                        <div class="p-3 bg-gray-50 rounded-lg text-sm text-gray-900">{{ $activity->user->year_level }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Approval Progress -->
        <div class="bg-white overflow-hidden shadow-green sm:rounded-xl mb-6 border-2 border-yellow-500" style="border: 3px solid #eab308 !important;">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-route text-green-600 mr-2"></i>
                    Approval Progress
                </h3>
                <p class="text-sm text-gray-600 mt-1">Track the activity through the 5-step approval workflow</p>
            </div>
            <div class="p-6">
                <x-approval-workflow-status :activity="$activity" />
            </div>
        </div>

        <!-- Admin Actions -->
        <div class="bg-white overflow-hidden shadow-green sm:rounded-xl mb-6 border-2 border-yellow-500" style="border: 3px solid #eab308 !important;">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-cogs text-green-600 mr-2"></i>
                    Admin Actions
                </h3>
                <p class="text-sm text-gray-600 mt-1">Administrative actions and workflow management</p>
            </div>
            <div class="p-6">
                <x-approval-workflow-actions :activity="$activity" />
            </div>
        </div>

        <!-- Activity Logs -->
        @if($activity->logs && $activity->logs->count() > 0)
        <div class="bg-white overflow-hidden shadow-green sm:rounded-xl mb-6 border-2 border-yellow-500" style="border: 3px solid #eab308 !important;">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-clipboard-check text-green-600 mr-2"></i>
                    Activity History
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($activity->logs->sortByDesc('created_at') as $log)
                    <div class="border-l-4 border-green-500 pl-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    Status changed from {{ ucfirst($log->previous_status) }} to {{ ucfirst($log->new_status) }}
                                </p>
                                <p class="text-sm text-gray-600">by {{ $log->user->name }}</p>
                                @if($log->comments)
                                    <p class="text-sm text-gray-700 mt-1">{{ $log->comments }}</p>
                                @endif
                            </div>
                            <span class="text-xs text-gray-500">{{ $log->created_at->format('M d, Y g:i A') }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Submission Information -->
        <div class="bg-white overflow-hidden shadow-green sm:rounded-xl border-2 border-yellow-500" style="border: 3px solid #eab308 !important;">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-calendar text-green-600 mr-2"></i>
                    Submission Information
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Submitted On</label>
                        <div class="p-3 bg-gray-50 rounded-lg text-sm text-gray-900">{{ $activity->created_at->format('F d, Y g:i A') }}</div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Last Updated</label>
                        <div class="p-3 bg-gray-50 rounded-lg text-sm text-gray-900">{{ $activity->updated_at->format('F d, Y g:i A') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
