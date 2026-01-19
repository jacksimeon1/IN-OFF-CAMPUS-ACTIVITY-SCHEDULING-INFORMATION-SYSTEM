@extends('layouts.sidebar')

@section('title', 'Overview')
@section('page-title', 'Overview')

@section('content')
<!-- Dashboard Overview Content -->
<div id="dashboard-content" class="tab-content dashboard-section">

        <!-- Enhanced Statistics Cards with Animations -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6" id="statsContainer">
            <!-- Total Activities Card -->
            <div class="animated-stat-card group" data-delay="0">
                <div class="card-inner">
                    <div class="card-glow"></div>
                    <div class="card-content">
                        <div class="flex items-center justify-between">
                            <div class="stat-info">
                                <div class="stat-icon-wrapper">
                                    <i class="fas fa-calendar-alt stat-icon"></i>
                                </div>
                                <h3 class="stat-title">Total Activities</h3>
                                <p class="stat-number">{{ $totalActivities }}</p>
                                <p class="stat-subtitle">All submissions</p>
                            </div>
                            <div class="stat-visual">
                                <div class="pulse-ring"></div>
                                <div class="pulse-ring-2"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Activities Card -->
            <div class="animated-stat-card group" data-delay="100">
                <div class="card-inner">
                    <div class="card-glow"></div>
                    <div class="card-content">
                        <div class="flex items-center justify-between">
                            <div class="stat-info">
                                <div class="stat-icon-wrapper">
                                    <i class="fas fa-clock stat-icon"></i>
                                </div>
                                <h3 class="stat-title">Pending</h3>
                                <p class="stat-number">{{ $pendingActivities }}</p>
                                <p class="stat-subtitle">Awaiting review</p>
                            </div>
                            <div class="stat-visual">
                                <div class="pulse-ring"></div>
                                <div class="pulse-ring-2"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Approved Activities Card -->
            <div class="animated-stat-card group" data-delay="200">
                <div class="card-inner">
                    <div class="card-glow"></div>
                    <div class="card-content">
                        <div class="flex items-center justify-between">
                            <div class="stat-info">
                                <div class="stat-icon-wrapper">
                                    <i class="fas fa-check-circle stat-icon"></i>
                                </div>
                                <h3 class="stat-title">Approved</h3>
                                <p class="stat-number">{{ $approvedActivities }}</p>
                                <p class="stat-subtitle">Ready to proceed</p>
                            </div>
                            <div class="stat-visual">
                                <div class="pulse-ring"></div>
                                <div class="pulse-ring-2"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rejected Activities Card -->
            <div class="animated-stat-card group" data-delay="300">
                <div class="card-inner">
                    <div class="card-glow"></div>
                    <div class="card-content">
                        <div class="flex items-center justify-between">
                            <div class="stat-info">
                                <div class="stat-icon-wrapper">
                                    <i class="fas fa-times-circle stat-icon"></i>
                                </div>
                                <h3 class="stat-title">Rejected</h3>
                                <p class="stat-number">{{ $rejectedActivities }}</p>
                                <p class="stat-subtitle">Need revision</p>
                            </div>
                            <div class="stat-visual">
                                <div class="pulse-ring"></div>
                                <div class="pulse-ring-2"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- My Activities Section -->
        <div class="mb-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-list-alt text-green-600 mr-2"></i>
                        My Submitted Activities
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php
                                $userActivities = \App\Models\Activity::where('user_id', auth()->id())->orderBy('created_at', 'desc')->limit(5)->get();
                            @endphp
                            @forelse($userActivities as $activity)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $activity->title }}</div>
                                            <div class="text-sm text-gray-500">{{ Str::limit($activity->description, 50) }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($activity->type === 'in-campus') bg-blue-100 text-blue-800
                                            @else bg-purple-100 text-purple-800
                                            @endif">
                                            {{ ucfirst(str_replace('-', ' ', $activity->type)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $activity->activity_date->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($activity->workflow_status === 'approved_by_vp') bg-green-100 text-green-800
                                            @elseif($activity->workflow_status === 'rejected') bg-red-100 text-red-800
                                            @else bg-yellow-100 text-yellow-800 @endif">
                                            {{ $activity->getCurrentApprovalStepName() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('activities.show', $activity) }}" class="text-green-600 hover:text-green-900">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($activity->workflow_status === 'draft')
                                                <a href="{{ route('activities.edit', $activity) }}" class="text-blue-600 hover:text-blue-900">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        <i class="fas fa-calendar-plus text-4xl text-gray-300 mb-4"></i>
                                        <p class="text-gray-500">No activities submitted yet.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 text-center">
                    <a href="{{ route('student.activities') }}" class="text-green-600 hover:text-green-700 text-sm font-medium">View all activities</a>
                </div>
            </div>
        </div>




</div>

<style>
/* Enhanced Statistics Cards with Animations */
.animated-stat-card {
    position: relative;
    opacity: 0;
    transform: translateY(30px) scale(0.95);
    animation: slideInUp 0.8s ease-out forwards;
    animation-delay: var(--delay);
    perspective: 1000px;
}

.animated-stat-card[data-delay="0"] { --delay: 0s; }
.animated-stat-card[data-delay="100"] { --delay: 0.1s; }
.animated-stat-card[data-delay="200"] { --delay: 0.2s; }
.animated-stat-card[data-delay="300"] { --delay: 0.3s; }

@keyframes slideInUp {
    0% {
        opacity: 0;
        transform: translateY(30px) scale(0.95);
    }
    50% {
        opacity: 0.7;
        transform: translateY(-5px) scale(1.02);
    }
    100% {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.card-inner {
    position: relative;
    background: linear-gradient(135deg, #059669 0%, #047857 50%, #065f46 100%);
    border-radius: 20px;
    padding: 24px;
    height: 140px;
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    box-shadow:
        0 10px 30px rgba(5, 150, 105, 0.3),
        0 5px 15px rgba(0, 0, 0, 0.1),
        inset 0 1px 0 rgba(255, 255, 255, 0.1);
}

.animated-stat-card:hover .card-inner {
    transform: translateY(-8px) rotateX(5deg);
    box-shadow:
        0 20px 40px rgba(5, 150, 105, 0.4),
        0 15px 25px rgba(5, 150, 105, 0.3),
        0 10px 15px rgba(0, 0, 0, 0.2),
        inset 0 1px 0 rgba(255, 255, 255, 0.2);
}

.card-glow {
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(16, 185, 129, 0.3) 0%, transparent 70%);
    opacity: 0;
    transition: all 0.6s ease;
    animation: rotateGlow 4s linear infinite;
}

.animated-stat-card:hover .card-glow {
    opacity: 1;
    animation-duration: 2s;
}

@keyframes rotateGlow {
    0% { transform: rotate(0deg) scale(0.8); }
    50% { transform: rotate(180deg) scale(1.2); }
    100% { transform: rotate(360deg) scale(0.8); }
}

.card-content {
    position: relative;
    z-index: 2;
    height: 100%;
}

.stat-info {
    color: white;
    flex: 1;
}

.stat-icon-wrapper {
    width: 48px;
    height: 48px;
    background: rgba(255, 255, 255, 0.15);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 12px;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.animated-stat-card:hover .stat-icon-wrapper {
    background: rgba(255, 255, 255, 0.25);
    transform: scale(1.1) rotate(5deg);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}

.stat-icon {
    font-size: 20px;
    color: white;
    transition: all 0.3s ease;
}

.animated-stat-card:hover .stat-icon {
    color: #d1fae5;
    transform: scale(1.1);
}

.stat-title {
    font-size: 14px;
    font-weight: 600;
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 4px;
    transition: all 0.3s ease;
}

.animated-stat-card:hover .stat-title {
    color: #d1fae5;
}

.stat-number {
    font-size: 28px;
    font-weight: 800;
    color: white;
    line-height: 1;
    margin-bottom: 4px;
    transition: all 0.3s ease;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

.animated-stat-card:hover .stat-number {
    color: #d1fae5;
    transform: scale(1.05);
    text-shadow: 0 4px 8px rgba(0, 0, 0, 0.4);
}

.stat-subtitle {
    font-size: 12px;
    color: rgba(255, 255, 255, 0.7);
    transition: all 0.3s ease;
}

.animated-stat-card:hover .stat-subtitle {
    color: rgba(209, 250, 229, 0.9);
}

.stat-visual {
    position: relative;
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.pulse-ring {
    position: absolute;
    width: 40px;
    height: 40px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    animation: pulse 2s ease-in-out infinite;
}

.pulse-ring-2 {
    position: absolute;
    width: 60px;
    height: 60px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    animation: pulse 2s ease-in-out infinite 0.5s;
}

@keyframes pulse {
    0% {
        transform: scale(0.8);
        opacity: 1;
    }
    50% {
        transform: scale(1.2);
        opacity: 0.5;
    }
    100% {
        transform: scale(0.8);
        opacity: 1;
    }
}

.animated-stat-card:hover .pulse-ring {
    animation-duration: 1s;
    border-color: rgba(209, 250, 229, 0.6);
}

.animated-stat-card:hover .pulse-ring-2 {
    animation-duration: 1s;
    border-color: rgba(209, 250, 229, 0.4);
}

/* Floating animation for cards */
.animated-stat-card {
    animation: slideInUp 0.8s ease-out forwards, float 6s ease-in-out infinite;
    animation-delay: var(--delay), calc(var(--delay) + 1s);
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-5px); }
}
</style>
@endsection
