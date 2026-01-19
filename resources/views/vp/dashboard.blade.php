@extends('layouts.sidebar')

@section('title', 'VP Dashboard')
@section('page-title', 'Vice President for Academics Dashboard')

@push('styles')
<style>
/* Student Panel Design - Green Gradient Cards */
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
    background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.animated-stat-card:hover .card-glow {
    opacity: 1;
}

.card-content {
    position: relative;
    z-index: 2;
    height: 100%;
}

.stat-info {
    color: white;
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
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.stat-icon {
    font-size: 20px;
    color: rgba(255, 255, 255, 0.9);
}

.stat-title {
    font-size: 14px;
    font-weight: 500;
    color: rgba(255, 255, 255, 0.8);
    margin-bottom: 4px;
}

.stat-number {
    font-size: 28px;
    font-weight: 700;
    color: white;
    line-height: 1;
    margin-bottom: 2px;
}

.stat-subtitle {
    font-size: 12px;
    color: rgba(255, 255, 255, 0.7);
}

.stat-visual {
    position: relative;
    width: 60px;
    height: 60px;
}

.pulse-ring, .pulse-ring-2 {
    position: absolute;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    animation: pulse 2s infinite;
}

.pulse-ring {
    width: 40px;
    height: 40px;
    top: 10px;
    left: 10px;
}

.pulse-ring-2 {
    width: 60px;
    height: 60px;
    top: 0;
    left: 0;
    animation-delay: 1s;
}

@keyframes pulse {
    0% {
        transform: scale(0.8);
        opacity: 1;
    }
    100% {
        transform: scale(1.2);
        opacity: 0;
    }
}

/* Content Cards */
.content-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

.content-card-header {
    background: linear-gradient(135deg, #059669, #047857);
    color: white;
    padding: 1.5rem;
}

.content-card-body {
    padding: 1.5rem;
}
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6" id="statsContainer">
        <!-- Pending Final Approvals -->
        <div class="animated-stat-card group" data-delay="0">
            <div class="card-inner">
                <div class="card-glow"></div>
                <div class="card-content">
                    <div class="flex items-center justify-between">
                        <div class="stat-info">
                            <div class="stat-icon-wrapper">
                                <i class="fas fa-gavel stat-icon"></i>
                            </div>
                            <h3 class="stat-title">Final Approvals</h3>
                            <p class="stat-number">{{ $pendingFinalApprovals }}</p>
                            <p class="stat-subtitle">Awaiting VP approval</p>
                        </div>
                        <div class="stat-visual">
                            <div class="pulse-ring"></div>
                            <div class="pulse-ring-2"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Activities -->
        <div class="animated-stat-card group" data-delay="100">
            <div class="card-inner">
                <div class="card-glow"></div>
                <div class="card-content">
                    <div class="flex items-center justify-between">
                        <div class="stat-info">
                            <div class="stat-icon-wrapper">
                                <i class="fas fa-list-alt stat-icon"></i>
                            </div>
                            <h3 class="stat-title">Total Activities</h3>
                            <p class="stat-number">{{ $totalActivities }}</p>
                            <p class="stat-subtitle">All activities</p>
                        </div>
                        <div class="stat-visual">
                            <div class="pulse-ring"></div>
                            <div class="pulse-ring-2"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Approved Activities -->
        <div class="animated-stat-card group" data-delay="200">
            <div class="card-inner">
                <div class="card-glow"></div>
                <div class="card-content">
                    <div class="flex items-center justify-between">
                        <div class="stat-info">
                            <div class="stat-icon-wrapper">
                                <i class="fas fa-check-circle stat-icon"></i>
                            </div>
                            <h3 class="stat-title">Approved by Me</h3>
                            <p class="stat-number">{{ $approvedActivities }}</p>
                            <p class="stat-subtitle">VP approved</p>
                        </div>
                        <div class="stat-visual">
                            <div class="pulse-ring"></div>
                            <div class="pulse-ring-2"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- This Month Activities -->
        <div class="animated-stat-card group" data-delay="300">
            <div class="card-inner">
                <div class="card-glow"></div>
                <div class="card-content">
                    <div class="flex items-center justify-between">
                        <div class="stat-info">
                            <div class="stat-icon-wrapper">
                                <i class="fas fa-calendar-alt stat-icon"></i>
                            </div>
                            <h3 class="stat-title">This Month</h3>
                            <p class="stat-number">{{ $thisMonthActivities }}</p>
                            <p class="stat-subtitle">Recent activities</p>
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

    <!-- Activities Awaiting Final Approval and Recent Approved Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Activities Awaiting Final Approval -->
        <div class="content-card">
            <div class="content-card-header">
                <h3 class="text-lg font-semibold flex items-center">
                    <i class="fas fa-gavel mr-2"></i>
                    Activities Awaiting Final Approval
                </h3>
            </div>
            <div class="content-card-body">
                @if($activitiesAwaitingFinalApproval->count() > 0)
                    <div class="space-y-4">
                        @foreach($activitiesAwaitingFinalApproval as $activity)
                            <div class="flex items-center justify-between p-3 bg-gradient-to-r from-green-50 to-green-100 rounded-lg border border-green-200 hover:from-green-100 hover:to-green-200 transition-all duration-200">
                                <div class="flex-1">
                                    <div class="text-sm font-medium text-green-900">{{ $activity->title }}</div>
                                    <div class="text-xs text-green-700">
                                        By {{ $activity->user->name }} • {{ $activity->created_at->format('M d, Y') }}
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <a href="{{ route('vp.show-activity', $activity) }}" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-600 text-white hover:bg-green-700 shadow-sm hover:shadow-md transition-all duration-200">
                                        <i class="fas fa-eye mr-1"></i>
                                        Review
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if($activitiesAwaitingFinalApproval->count() >= 10)
                        <div class="mt-4 text-center">
                            <a href="{{ route('vp.pending-approvals') }}" class="text-green-600 hover:text-green-700 text-sm font-medium">View all pending approvals</a>
                        </div>
                    @endif
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-check-circle text-green-500 text-3xl mb-3"></i>
                        <p class="text-gray-600">No activities pending final approval</p>
                        <p class="text-gray-500 text-sm">All caught up!</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Approved Activities -->
        <div class="content-card">
            <div class="content-card-header">
                <h3 class="text-lg font-semibold flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    Recent Approved Activities
                </h3>
            </div>
            <div class="content-card-body">
                @if($recentApprovedActivities->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentApprovedActivities as $activity)
                            <div class="flex items-center justify-between p-3 bg-gradient-to-r from-green-50 to-green-100 rounded-lg border border-green-200 hover:from-green-100 hover:to-green-200 transition-all duration-200">
                                <div class="flex-1">
                                    <div class="text-sm font-medium text-green-900">{{ $activity->title }}</div>
                                    <div class="text-xs text-green-700">
                                        By {{ $activity->user->name }} • Approved {{ $activity->vp_approved_at->format('M d, Y') }}
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Approved
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                @else
                    <div class="text-center py-8">
                        <i class="fas fa-inbox text-green-500 text-3xl mb-3"></i>
                        <p class="text-gray-600">No recent approvals</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
