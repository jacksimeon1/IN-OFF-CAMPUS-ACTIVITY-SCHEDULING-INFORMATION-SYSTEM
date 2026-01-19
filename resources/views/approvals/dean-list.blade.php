@extends('layouts.sidebar')

@section('title', 'Dean Activities')
@section('page-title', 'Dean/Unit Head Activities')
@section('page-subtitle', 'Activities awaiting your academic review and noting')

@section('content')
<div class="pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Dean Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-6 rounded-lg mb-6">
            <div class="flex items-center">
                <i class="fas fa-university text-3xl mr-4"></i>
                <div>
                    <h2 class="text-2xl font-bold">Dean/Unit Head Review Center</h2>
                    <p class="text-blue-100">Academic and administrative review of activity proposals</p>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-blue-500">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clipboard-list text-blue-500 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Pending Reviews</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $activities->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-green-500">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-500 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Your Role</p>
                            <p class="text-lg font-bold text-gray-900">{{ $user->getRoleDisplayName() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-yellow-500">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-building text-yellow-500 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Department</p>
                            <p class="text-sm font-bold text-gray-900">{{ $user->department ?? 'All Departments' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activities List -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-list mr-2"></i>
                    Activities Awaiting Dean Review
                </h3>

                @if($activities->count() > 0)
                    <div class="space-y-4">
                        @foreach($activities as $activity)
                            <div class="border border-gray-200 rounded-lg p-6 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-4 mb-3">
                                            <h4 class="text-lg font-semibold text-gray-900">{{ $activity->title }}</h4>
                                            <span class="px-3 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                                {{ $activity->getCurrentApprovalStepName() }}
                                            </span>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-sm text-gray-600">
                                            <div>
                                                <i class="fas fa-building mr-1"></i>
                                                <strong>Organization:</strong> {{ $activity->organization }}
                                            </div>
                                            <div>
                                                <i class="fas fa-calendar mr-1"></i>
                                                <strong>Date:</strong> {{ $activity->activity_date->format('M d, Y') }}
                                            </div>
                                            <div>
                                                <i class="fas fa-map-marker-alt mr-1"></i>
                                                <strong>Location:</strong> {{ $activity->location }}
                                            </div>
                                            <div>
                                                <i class="fas fa-user mr-1"></i>
                                                <strong>Submitted by:</strong> {{ $activity->user->name }}
                                            </div>
                                        </div>

                                        @if($activity->budget)
                                            <div class="mt-2 text-sm text-gray-600">
                                                <i class="fas fa-dollar-sign mr-1"></i>
                                                <strong>Budget:</strong> ₱{{ number_format($activity->budget, 2) }}
                                            </div>
                                        @endif

                                        <!-- Approval Progress -->
                                        <div class="mt-3">
                                            <div class="flex items-center space-x-2 text-xs">
                                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded">✓ Submitted</span>
                                                @if($activity->workflow_status !== 'submitted')
                                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded">✓ Adviser</span>
                                                @endif
                                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded">⏳ Dean Review</span>
                                                <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded">PSG</span>
                                                <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded">Director</span>
                                                <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded">VP</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex flex-col space-y-2 ml-6">
                                        <a href="{{ route('workflow.approval.form', $activity) }}" 
                                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                                            <i class="fas fa-eye mr-2"></i>
                                            Review Activity
                                        </a>
                                        
                                        <a href="{{ route('workflow.activity.details', $activity) }}" 
                                           class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                            <i class="fas fa-info-circle mr-2"></i>
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination if needed -->
                    @if($activities instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="mt-6">
                            {{ $activities->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-check-circle text-green-500 text-6xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Activities Awaiting Review</h3>
                        <p class="text-gray-600">All activities have been processed or there are no submissions requiring dean review at this time.</p>
                        
                        <div class="mt-6">
                            <a href="{{ route('dashboard') }}" 
                               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Back to Dashboard
                            </a>
                        </div>
                    </div>
                @endif
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
