@extends('layouts.sidebar')

@section('title', 'All Activities')
@section('page-title', 'Department Activities')
@section('page-subtitle', 'All activities from your department students')

@section('content')
<div class="pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-green-600 to-green-700 text-white p-6 rounded-lg mb-6">
            <div class="flex items-center">
                <i class="fas fa-clipboard-list text-3xl mr-4"></i>
                <div>
                    <h2 class="text-2xl font-bold">Department Activities</h2>
                    <p class="text-green-100">View and manage all activities from your department students</p>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-green-500">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clipboard-list text-green-500 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Activities</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $activities->total() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-yellow-500">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clock text-yellow-500 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Pending Review</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $activities->where('workflow_status', 'draft')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-blue-500">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-blue-500 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Approved by You</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $activities->where('adviser_noted_by', auth()->id())->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activities Table -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-2 border-green-500">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-list mr-2 text-green-600"></i>
                    All Department Activities
                </h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-green-200">
                    <thead class="bg-green-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">Activity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">Student</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">Activity Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">Submitted</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-green-200">
                        @forelse($activities as $activity)
                            <tr class="hover:bg-green-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-green-900">{{ $activity->title }}</div>
                                        <div class="text-sm text-green-700">{{ Str::limit($activity->description ?? 'No description', 50) }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-green-900">{{ $activity->user->name }}</div>
                                    <div class="text-sm text-green-700">{{ $activity->user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($activity->type === 'in-campus') bg-green-200 text-green-800
                                        @else bg-yellow-200 text-yellow-800
                                        @endif">
                                        {{ ucfirst(str_replace('-', ' ', $activity->type)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-green-700">
                                    {{ $activity->activity_date->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-green-700">
                                    {{ $activity->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($activity->workflow_status === 'approved_by_vp') bg-green-200 text-green-800
                                        @elseif($activity->workflow_status === 'rejected') bg-red-200 text-red-800
                                        @elseif($activity->workflow_status === 'draft') bg-yellow-200 text-yellow-800
                                        @else bg-blue-200 text-blue-800 @endif">
                                        {{ $activity->getCurrentApprovalStepName() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        @if($activity->workflow_status === 'draft')
                                            <a href="{{ route('adviser.show-activity', $activity) }}" 
                                               class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-600 text-white hover:bg-green-700 shadow-sm hover:shadow-md transition-all duration-200">
                                                <i class="fas fa-check mr-1"></i>
                                                Review Activity
                                            </a>
                                        @else
                                            <a href="{{ route('adviser.show-activity', $activity) }}" 
                                               class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-600 text-white hover:bg-blue-700 shadow-sm hover:shadow-md transition-all duration-200">
                                                <i class="fas fa-eye mr-1"></i>
                                                View Details
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-green-600">
                                    <i class="fas fa-inbox text-4xl text-green-400 mb-4"></i>
                                    <p class="text-green-600">No activities found in your department.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($activities->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $activities->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
