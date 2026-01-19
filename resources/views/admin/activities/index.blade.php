@extends('layouts.admin')

@section('title', 'Activity Management')
@section('page-title', 'Activity Management')
@section('page-subtitle', 'Review and manage all submitted activities')

@push('styles')
<style>
.textInputWrapper {
    position: relative;
    width: 100%;
    margin: 12px 0;
    --accent-color: #059669;
}

.textInputWrapper:before {
    transition: border-bottom-color 200ms cubic-bezier(0.4, 0, 0.2, 1) 0ms;
    border-bottom: 1px solid rgba(0, 0, 0, 0.42);
}

.textInputWrapper:before,
.textInputWrapper:after {
    content: "";
    left: 0;
    right: 0;
    position: absolute;
    pointer-events: none;
    bottom: -1px;
    z-index: 4;
    width: 100%;
}

.textInputWrapper:focus-within:before {
    border-bottom: 1px solid var(--accent-color);
    transform: scaleX(1);
}

.textInputWrapper:focus-within:after {
    border-bottom: 2px solid var(--accent-color);
    transform: scaleX(1);
}

.textInputWrapper:after {
    content: "";
    transform: scaleX(0);
    transition: transform 250ms cubic-bezier(0, 0, 0.2, 1) 0ms;
    will-change: transform;
    border-bottom: 2px solid var(--accent-color);
    border-bottom-color: var(--accent-color);
}

.textInput::placeholder {
    transition: opacity 250ms cubic-bezier(0, 0, 0.2, 1) 0ms;
    opacity: 1;
    user-select: none;
    color: rgba(255, 255, 255, 0.582);
}

.textInputWrapper .textInput {
    border-radius: 5px 5px 0px 0px;
    box-shadow: 0px 2px 5px rgb(35 35 35 / 30%);
    max-height: 36px;
    background-color: #252525;
    transition-timing-function: cubic-bezier(0.25, 0.8, 0.25, 1);
    transition-duration: 200ms;
    transition-property: background-color;
    color: #e8e8e8;
    font-size: 14px;
    font-weight: 500;
    padding: 12px;
    width: 100%;
    border: none;
    outline: none;
}

.textInputWrapper .textInput:focus,
.textInputWrapper .textInput:active {
    outline: none;
}

.textInputWrapper:focus-within .textInput,
.textInputWrapper .textInput:focus,
.textInputWrapper .textInput:active {
    background-color: #353535;
}

.textInputWrapper:focus-within .textInput::placeholder {
    opacity: 0;
}
</style>
@endpush

@push('styles')
<style>
</style>
@endpush

@section('content')


<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Filters -->
        <div class="bg-white overflow-hidden shadow-green sm:rounded-xl mb-6">
            <div class="p-6">
                <form method="GET" action="{{ route('admin.activities') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <div class="textInputWrapper">
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                   class="textInput" placeholder="Search activities or students...">
                        </div>
                    </div>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" id="status" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="recommended" {{ request('status') === 'recommended' ? 'selected' : '' }}>Recommended</option>
                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                        <select name="type" id="type" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                            <option value="">All Types</option>
                            <option value="in-campus" {{ request('type') === 'in-campus' ? 'selected' : '' }}>In-Campus</option>
                            <option value="off-campus" {{ request('type') === 'off-campus' ? 'selected' : '' }}>Off-Campus</option>
                        </select>
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" class="btn-primary w-full px-4 py-2 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200">
                            <i class="fas fa-search mr-2"></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Activities Table -->
        <div class="bg-white overflow-hidden shadow-green sm:rounded-xl">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-list-alt text-green-600 mr-2"></i>
                        All Activities ({{ $activities->total() }})
                    </h3>
                    <a href="{{ route('admin.dashboard') }}" class="text-green-600 hover:text-green-700 text-sm font-medium">
                        ← Back to Dashboard
                    </a>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($activities as $activity)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $activity->title }}</div>
                                        <div class="text-sm text-gray-500">{{ $activity->organization ?? 'No organization specified' }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $activity->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $activity->user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($activity->type === 'in-campus') bg-blue-100 text-blue-800
                                        @else bg-purple-100 text-purple-800
                                        @endif">
                                        {{ ucfirst(str_replace('-', ' ', $activity->type)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="space-y-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($activity->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($activity->status === 'recommended') bg-blue-100 text-blue-800
                                            @elseif($activity->status === 'approved') bg-green-100 text-green-800
                                            @elseif($activity->status === 'rejected') bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($activity->status) }}
                                        </span>
                                        <div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $activity->getWorkflowStatusBadgeColor() }}-100 text-{{ $activity->getWorkflowStatusBadgeColor() }}-800">
                                                {{ $activity->getWorkflowStatusDisplayName() }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($activity->activity_date->format('Y-m-d') === $activity->end_date->format('Y-m-d'))
                                        {{ $activity->activity_date->format('M d, Y') }}
                                        <div class="text-xs text-gray-400">Single day</div>
                                    @else
                                        {{ $activity->activity_date->format('M d') }} - {{ $activity->end_date->format('M d, Y') }}
                                        <div class="text-xs text-gray-400">{{ $activity->activity_date->diffInDays($activity->end_date) + 1 }} days</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.activities.show', $activity) }}" class="text-green-600 hover:text-green-900" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.activities.edit-status', $activity) }}" class="text-blue-600 hover:text-blue-900" title="Edit Status">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-inbox text-3xl text-gray-300 mb-2"></i>
                                    <p>No activities found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($activities->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $activities->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection




