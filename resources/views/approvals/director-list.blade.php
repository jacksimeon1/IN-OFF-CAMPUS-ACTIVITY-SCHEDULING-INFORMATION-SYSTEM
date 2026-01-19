@extends('layouts.sidebar')

@section('title', 'Director Activities')
@section('page-title', 'Director of Student Officer Affairs Activities')
@section('page-subtitle', 'Activities awaiting your institutional policy endorsement')

@section('content')
<div class="pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Director Header -->
        <div class="bg-gradient-to-r from-red-600 to-red-700 text-white p-6 rounded-lg mb-6">
            <div class="flex items-center">
                <i class="fas fa-shield-alt text-3xl mr-4"></i>
                <div>
                    <h2 class="text-2xl font-bold">Director Endorsement Center</h2>
                    <p class="text-red-100">Institutional policy compliance and student officer welfare oversight</p>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-red-500">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clipboard-list text-red-500 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Pending Endorsements</p>
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
                            <p class="text-sm font-medium text-gray-600">Focus</p>
                            <p class="text-sm font-bold text-gray-900">Policy Compliance</p>
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
                    Activities Awaiting Director Endorsement
                </h3>

                @if($activities->count() > 0)
                    <div class="space-y-4">
                        @foreach($activities as $activity)
                            <div class="border border-gray-200 rounded-lg p-6 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-4 mb-3">
                                            <h4 class="text-lg font-semibold text-gray-900">{{ $activity->title }}</h4>
                                            <span class="px-3 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                                                {{ $activity->getCurrentApprovalStepName() }}
                                            </span>
                                            @if($activity->type === 'off-campus')
                                                <span class="px-2 py-1 text-xs font-medium bg-orange-100 text-orange-800 rounded-full">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                                    Off-Campus
                                                </span>
                                            @endif
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
                                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded">✓ Adviser</span>
                                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded">✓ Dean</span>
                                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded">✓ PSG</span>
                                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded">⏳ Director</span>
                                                <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded">VP</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex flex-col space-y-2 ml-6">
                                        <a href="{{ route('workflow.approval.form', $activity) }}" 
                                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 transition-colors">
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
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-check-circle text-green-500 text-6xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Activities Awaiting Endorsement</h3>
                        <p class="text-gray-600">All activities have been processed or there are no submissions requiring director endorsement at this time.</p>
                        
                        <div class="mt-6">
                            <a href="{{ route('dashboard') }}" 
                               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Back to Dashboard
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Director Guidelines -->
        <div class="mt-8 bg-red-50 border border-red-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-red-900 mb-4">
                <i class="fas fa-info-circle mr-2"></i>
                Director Endorsement Guidelines
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-medium text-red-800 mb-2">Review Criteria</h4>
                    <ul class="text-sm text-red-700 space-y-1">
                        <li>• Student safety and welfare protocols</li>
                        <li>• Institutional risk assessment</li>
                        <li>• Budget and resource allocation review</li>
                        <li>• External partnership compliance</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-medium text-red-800 mb-2">Action Items</h4>
                    <ul class="text-sm text-red-700 space-y-1">
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
