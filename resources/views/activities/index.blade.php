@extends('layouts.sidebar')

@section('title', 'My Activities')
@section('page-title', 'My Activities')
@section('page-subtitle', 'View and manage your submitted activities')



@section('content')
<div class="py-4">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Activities List -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-2 border-yellow-500" style="border: 3px solid #eab308 !important;">
            @if($activities->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activity</th>
                                @if(!auth()->user()->isStudent())
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted By</th>
                                @endif
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($activities as $activity)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $activity->title }}</div>
                                            <div class="text-sm text-gray-500">{{ $activity->organization ?? 'No organization specified' }}</div>
                                        </div>
                                    </td>
                                    @if(!auth()->user()->isStudent())
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $activity->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $activity->user->email }}</div>
                                        </td>
                                    @endif
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($activity->activity_date->format('Y-m-d') === $activity->end_date->format('Y-m-d'))
                                            {{ $activity->activity_date->format('M d, Y') }}
                                            <div class="text-xs text-gray-500">Single day</div>
                                        @else
                                            {{ $activity->activity_date->format('M d') }} - {{ $activity->end_date->format('M d, Y') }}
                                            <div class="text-xs text-gray-500">{{ $activity->activity_date->diffInDays($activity->end_date) + 1 }} days</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $activity->type === 'in-campus' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                            {{ ucfirst(str_replace('-', ' ', $activity->type)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="space-y-1">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $activity->getStatusBadgeColor() }}-100 text-{{ $activity->getStatusBadgeColor() }}-800">
                                                {{ ucfirst($activity->status) }}
                                            </span>
                                            <div>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $activity->getWorkflowStatusBadgeColor() }}-100 text-{{ $activity->getWorkflowStatusBadgeColor() }}-800">
                                                    {{ $activity->getWorkflowStatusDisplayName() }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                        @if(auth()->user()->isStudent() && $activity->user_id === auth()->id() && $activity->status === 'pending')
                                            <a href="{{ route('activities.edit', $activity) }}" class="text-indigo-600 hover:text-indigo-900">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('activities.destroy', $activity) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this activity?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if(auth()->user()->isAdviser() && $activity->status === 'pending')
                                            <a href="{{ route('adviser.show-activity', $activity) }}" class="text-green-600 hover:text-green-900">
                                                <i class="fas fa-check"></i>
                                            </a>
                                        @endif

                                        @if(auth()->user()->isOsa() && $activity->status === 'recommended')
                                            <a href="{{ route('approval.osa.form', $activity) }}" class="text-green-600 hover:text-green-900">
                                                <i class="fas fa-gavel"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $activities->links() }}
                </div>
            @else
                <div class="p-8 text-center">
                    <i class="fas fa-calendar-times text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 mb-4">No activities found.</p>
                    @if(auth()->user()->isStudent())
                        <a href="{{ route('activities.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                            <i class="fas fa-plus mr-2"></i> Submit Your First Activity
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
