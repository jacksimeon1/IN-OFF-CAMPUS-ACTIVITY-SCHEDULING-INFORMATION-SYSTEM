<x-dashboard-layout>
    <div class="space-y-6">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clock text-3xl text-yellow-500"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Pending Approval</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $stats['pending_approval'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-3xl text-green-500"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Approved</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $stats['approved_activities'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-times-circle text-3xl text-red-500"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Rejected</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $stats['rejected_activities'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-list-check text-3xl text-blue-500"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Reviewed</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_reviewed'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Approvals and Recent Decisions -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Pending Approvals -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Pending Approvals</h3>
                    </div>
                    <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                        @forelse($pendingApprovals as $activity)
                            <div class="p-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">{{ $activity->title }}</p>
                                        <p class="text-sm text-gray-500">by {{ $activity->user->name }}</p>
                                        <p class="text-xs text-gray-400">
                                            Recommended by {{ $activity->adviser->name ?? 'N/A' }} 
                                            {{ $activity->adviser_reviewed_at ? $activity->adviser_reviewed_at->diffForHumans() : '' }}
                                        </p>
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('activities.show', $activity) }}" 
                                           class="inline-flex items-center px-3 py-1 border border-transparent text-xs leading-4 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200">
                                            View
                                        </a>
                                        <a href="{{ route('approval.osa.form', $activity) }}" 
                                           class="inline-flex items-center px-3 py-1 border border-transparent text-xs leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                            Approve
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-4 text-center text-gray-500">
                                <i class="fas fa-inbox text-3xl mb-2"></i>
                                <p>No activities pending approval</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Decisions -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Decisions</h3>
                    </div>
                    <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                        @forelse($recentDecisions as $activity)
                            <div class="p-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">{{ $activity->title }}</p>
                                        <p class="text-sm text-gray-500">by {{ $activity->user->name }}</p>
                                        <div class="flex items-center mt-1">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($activity->status === 'approved') bg-green-100 text-green-800
                                                @elseif($activity->status === 'rejected') bg-red-100 text-red-800
                                                @endif">
                                                {{ ucfirst($activity->status) }}
                                            </span>
                                            <span class="text-xs text-gray-400 ml-2">{{ $activity->osa_reviewed_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                    <a href="{{ route('activities.show', $activity) }}" 
                                       class="inline-flex items-center px-3 py-1 border border-transparent text-xs leading-4 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200">
                                        View
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="p-4 text-center text-gray-500">
                                <i class="fas fa-history text-3xl mb-2"></i>
                                <p>No recent decisions</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Decision Statistics Chart -->
            @if($decisionStats->count() > 0)
            <div class="mt-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Decision Statistics</h3>
                    <canvas id="decisionChart" width="400" height="200"></canvas>
                </div>
            </div>
            @endif
        </div>
    </div>

    @if($decisionStats->count() > 0)
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Decision Statistics Chart
        const decisionCtx = document.getElementById('decisionChart').getContext('2d');
        const decisionData = {!! json_encode($decisionStats) !!};
        
        new Chart(decisionCtx, {
            type: 'pie',
            data: {
                labels: decisionData.map(item => item.status.charAt(0).toUpperCase() + item.status.slice(1)),
                datasets: [{
                    data: decisionData.map(item => item.count),
                    backgroundColor: [
                        '#34D399', // approved - green
                        '#F87171'  // rejected - red
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
    @endpush
    @endif
    </div>
</x-dashboard-layout>
