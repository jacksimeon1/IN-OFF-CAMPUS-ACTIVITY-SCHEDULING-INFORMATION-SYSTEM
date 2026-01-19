@extends('layouts.sidebar')

@section('title', 'Adviser Dashboard')
@section('page-title', 'Adviser Dashboard')
@section('page-subtitle', 'Review and manage student activity submissions')

@section('content')
<div class="py-4">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 border border-yellow-200 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-clock text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-yellow-700">Pending Reviews</p>
                            <p class="text-2xl font-bold text-yellow-800">{{ $stats['pending_reviews'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-thumbs-up text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-green-700">Recommended</p>
                            <p class="text-2xl font-bold text-green-800">{{ $stats['recommended_activities'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-yellow-50 border border-green-200 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-check-circle text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-green-700">Total Reviewed</p>
                            <p class="text-2xl font-bold text-green-800">{{ $stats['total_reviewed'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Activities and Recent Reviews -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Pending Activities -->
                <div class="bg-gradient-to-br from-yellow-50 to-white border border-yellow-200 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-yellow-200 bg-yellow-50">
                        <h3 class="text-lg font-semibold text-yellow-800 flex items-center">
                            <i class="fas fa-clock mr-2 text-yellow-600"></i>
                            Pending Activities
                        </h3>
                    </div>
                    <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                        @forelse($pendingActivities as $activity)
                            <div class="p-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">{{ $activity->title }}</p>
                                        <p class="text-sm text-gray-500">by {{ $activity->user->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $activity->created_at ? $activity->created_at->diffForHumans() : 'Unknown date' }}</p>
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('adviser.show-activity', $activity) }}"
                                           class="inline-flex items-center px-3 py-1 border border-transparent text-xs leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                            <i class="fas fa-check mr-1"></i>
                                            Review Activity
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-4 text-center text-yellow-600">
                                <i class="fas fa-inbox text-3xl mb-2 text-yellow-500"></i>
                                <p>No pending activities to review</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Reviews -->
                <div class="bg-gradient-to-br from-green-50 to-white border border-green-200 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-green-200 bg-green-50">
                        <h3 class="text-lg font-semibold text-green-800 flex items-center">
                            <i class="fas fa-history mr-2 text-green-600"></i>
                            Recent Reviews
                        </h3>
                    </div>
                    <div class="divide-y divide-green-200 max-h-96 overflow-y-auto">
                        @forelse($recentReviews as $activity)
                            <div class="p-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-green-900">{{ $activity->title }}</p>
                                        <p class="text-sm text-green-600">by {{ $activity->user->name }}</p>
                                        <div class="flex items-center mt-1">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($activity->status === 'recommended') bg-green-100 text-green-800
                                                @elseif($activity->status === 'rejected') bg-yellow-100 text-yellow-800
                                                @endif">
                                                {{ ucfirst($activity->status) }}
                                            </span>
                                            <span class="text-xs text-green-500 ml-2">{{ $activity->adviser_noted_at ? $activity->adviser_noted_at->diffForHumans() : 'Not reviewed yet' }}</span>
                                        </div>
                                    </div>
                                    <a href="{{ route('adviser.show-activity', $activity) }}"
                                       class="inline-flex items-center px-3 py-1 border border-transparent text-xs leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                        <i class="fas fa-check mr-1"></i>
                                        Review Activity
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="p-4 text-center text-green-600">
                                <i class="fas fa-history text-3xl mb-2 text-green-500"></i>
                                <p>No recent reviews</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Review Statistics Chart -->
            @if($reviewStats->count() > 0)
            <div class="mt-8">
                <div class="bg-gradient-to-br from-green-50 to-yellow-50 border border-green-200 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-green-800 mb-4 flex items-center">
                        <i class="fas fa-chart-bar mr-2 text-green-600"></i>
                        Review Statistics
                    </h3>
                    <canvas id="reviewChart" width="400" height="200"></canvas>
                </div>
            </div>
            @endif
        </div>
    </div>

    @if($reviewStats->count() > 0)
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Review Statistics Chart
        const reviewCtx = document.getElementById('reviewChart').getContext('2d');
        const reviewData = {!! json_encode($reviewStats) !!};
        
        new Chart(reviewCtx, {
            type: 'bar',
            data: {
                labels: reviewData.map(item => item.status.charAt(0).toUpperCase() + item.status.slice(1)),
                datasets: [{
                    label: 'Activities',
                    data: reviewData.map(item => item.count),
                    backgroundColor: [
                        '#22C55E', // recommended - green
                        '#EAB308'  // rejected - yellow
                    ]
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
    @endpush
    @endif
    </div>
</div>
@endsection
