@extends('layouts.sidebar')

@section('title', 'Overview')

@section('page-title', 'Overview')

@section('content')
<!-- Calendar Section Content -->
<div id="calendar-content" class="dashboard-section hidden mb-4">
    <div class="content-card">


        <div class="p-6">
            <div class="calendar-container">
                <div id="activity-calendar" class="w-full" style="min-height: 600px;">
                    <!-- Traditional calendar will be rendered here -->
                </div>
            </div>

            <!-- Enhanced Calendar Legend -->
            <div class="calendar-legend mt-4 p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs text-gray-500"></span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="legend-item flex items-center gap-2">
                        <div class="legend-color bg-green-500 w-4 h-4 rounded"></div>
                        <span class="text-sm text-gray-700">Approved Activities</span>
                    </div>
                    <div class="legend-item flex items-center gap-2">
                        <div class="legend-color bg-blue-500 w-4 h-4 rounded"></div>
                        <span class="text-sm text-gray-700">Today</span>
                    </div>
                </div>
                <div class="mt-3 p-3 bg-green-100 border border-green-300 rounded-lg text-green-800 text-sm">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong>All Approved Activities:</strong> This calendar displays all approved activities from all users across the university. Only activities that have been approved by administrators are shown here.
                </div>
            </div>

        </div>
    </div>
</div>

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
                                <p class="stat-number">{{ $stats['total_activities'] }}</p>
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
                                <p class="stat-number">{{ $stats['pending_activities'] }}</p>
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
                                <p class="stat-number">{{ $stats['approved_activities'] }}</p>
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
                                <p class="stat-number">{{ $stats['rejected_activities'] }}</p>
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
            <div class="bg-white overflow-hidden shadow-green sm:rounded-xl">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="dashboard-card-title flex items-center">
                            <i class="fas fa-list-alt text-green-600 mr-2"></i>
                            My Submitted Activities
                        </h3>
                        <a href="{{ route('activities.create') }}" class="btn-primary inline-flex items-center px-4 py-2 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200">
                            <i class="fas fa-plus mr-2"></i> Submit New
                        </a>
                    </div>
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
                                            @if($activity->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($activity->status === 'recommended') bg-blue-100 text-blue-800
                                            @elseif($activity->status === 'approved') bg-green-100 text-green-800
                                            @elseif($activity->status === 'rejected') bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($activity->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('activities.show', $activity) }}" class="text-green-600 hover:text-green-900">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($activity->status === 'pending')
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
                                        <p class="text-gray-500 mb-4">No activities submitted yet.</p>
                                        <a href="{{ route('activities.create') }}" class="btn-primary inline-flex items-center px-4 py-2 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200">
                                            <i class="fas fa-plus mr-2"></i> Submit Your First Activity
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($userActivities->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $userActivities->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Upcoming Activities -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="dashboard-card-title">Upcoming Activities</h3>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @forelse($upcomingActivities as $activity)
                            <div class="p-4">
                                <h4 class="text-sm font-medium text-gray-900">{{ $activity->title }}</h4>
                                <p class="text-sm text-gray-500">{{ $activity->activity_date->format('M d, Y') }}</p>
                                <p class="text-xs text-gray-400">{{ $activity->activity_date->diffForHumans() }}</p>
                            </div>
                        @empty
                            <div class="p-4 text-center text-gray-500">
                                No upcoming activities.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Notifications -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="dashboard-card-title">Recent Notifications</h3>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @forelse($notifications as $notification)
                            <div class="p-4">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-{{ $notification->getIcon() }} text-{{ $notification->type === 'success' ? 'green' : ($notification->type === 'error' ? 'red' : 'blue') }}-500"></i>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="text-sm font-medium text-gray-900">{{ $notification->title }}</p>
                                        <p class="text-sm text-gray-500">{{ $notification->message }}</p>
                                        <p class="text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-4 text-center text-gray-500">
                                No new notifications.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        </div> <!-- End Dashboard Tab Content -->

        <!-- My Activities Tab Content -->
        <div id="activities-content" class="tab-content hidden">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl">
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
                            @forelse($userActivities as $activity)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $activity->title }}</div>
                                            <div class="text-sm text-gray-500">{{ Str::limit($activity->description, 50) }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ ucfirst($activity->type) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $activity->activity_date->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($activity->status === 'pending')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-clock mr-1"></i> Pending
                                            </span>
                                        @elseif($activity->status === 'approved')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check mr-1"></i> Approved
                                            </span>
                                        @elseif($activity->status === 'rejected')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-times mr-1"></i> Rejected
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                        <a href="{{ route('activities.show', $activity) }}" class="text-green-600 hover:text-green-900" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($activity->status === 'pending')
                                            <a href="{{ route('activities.edit', $activity) }}" class="text-blue-600 hover:text-blue-900" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        <i class="fas fa-calendar-times text-3xl text-gray-300 mb-2"></i>
                                        <p class="mb-4">No activities submitted yet.</p>
                                        <button onclick="showTab('submit')" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                            <i class="fas fa-plus mr-2"></i> Submit Your First Activity
                                        </button>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Submit Activity Tab Content -->
        <div id="submit-content" class="tab-content hidden">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl">
                <div class="p-8">
                    <div class="text-center mb-8">
                        <i class="fas fa-plus-circle text-4xl text-green-600 mb-4"></i>
                        <h3 class="dashboard-section-title mb-2">Submit New Activity</h3>
                        <p class="text-gray-600">Ready to submit a new activity? Click the button below to get started.</p>
                    </div>
                    <div class="text-center">
                        <a href="{{ route('activities.create') }}" class="animated-button">
                            <p><i class="fas fa-plus mr-2"></i> Submit Activity</p>
                        </a>
                    </div>
                    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <i class="fas fa-file-alt text-2xl text-green-600 mb-2"></i>
                            <h4 class="font-semibold text-gray-900">Fill Details</h4>
                            <p class="text-sm text-gray-600">Provide activity information and upload documents</p>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <i class="fas fa-eye text-2xl text-green-600 mb-2"></i>
                            <h4 class="font-semibold text-gray-900">Review</h4>
                            <p class="text-sm text-gray-600">Your adviser will review the submission</p>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <i class="fas fa-check-circle text-2xl text-green-600 mb-2"></i>
                            <h4 class="font-semibold text-gray-900">Approval</h4>
                            <p class="text-sm text-gray-600">OSA will approve your activity</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Profile Section Content -->
    <div id="profile-content" class="dashboard-section hidden">
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-user text-green-600 mr-2"></i>
                    My Profile
                </h3>
                <p class="text-sm text-gray-600 mt-1">View and manage your profile information</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Profile Information -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <p class="mt-1 text-sm text-gray-900">{{ auth()->user()->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <p class="mt-1 text-sm text-gray-900">{{ auth()->user()->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Role</label>
                            <p class="mt-1 text-sm text-gray-900 capitalize">{{ auth()->user()->role }}</p>
                        </div>
                        @if(auth()->user()->organization)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Organization</label>
                            <p class="mt-1 text-sm text-gray-900">{{ auth()->user()->organization->name }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- Quick Actions -->
                    <div class="space-y-4">
                        <h4 class="text-lg font-medium text-gray-900">Quick Actions</h4>
                        <div class="space-y-3">
                            <a href="{{ route('profile.edit') }}"
                               class="block w-full bg-green-600 hover:bg-green-700 text-white text-center py-2 px-4 rounded-lg transition-colors">
                                <i class="fas fa-edit mr-2"></i>
                                Edit Profile
                            </a>
                            <button onclick="showTab('submit')"
                                    class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-lg transition-colors">
                                <i class="fas fa-plus mr-2"></i>
                                Submit New Activity
                            </button>
                            <button onclick="showTab('activities')"
                                    class="block w-full bg-gray-600 hover:bg-gray-700 text-white text-center py-2 px-4 rounded-lg transition-colors">
                                <i class="fas fa-list mr-2"></i>
                                View My Activities
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Beautiful Header Fonts */
.dashboard-main-title {
    font-family: 'Playfair Display', serif;
    font-weight: 700;
    font-size: 2.5rem;
    letter-spacing: -0.025em;
    background: linear-gradient(135deg, #059669, #047857);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.dashboard-section-title {
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
    font-size: 1.5rem;
    letter-spacing: -0.025em;
    color: #1f2937;
}

.dashboard-card-title {
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
    font-size: 1.125rem;
    letter-spacing: -0.025em;
    color: #374151;
}

.dashboard-stat-title {
    font-family: 'Inter', sans-serif;
    font-weight: 600;
    font-size: 0.875rem;
    letter-spacing: 0.025em;
    text-transform: uppercase;
}

.dashboard-calendar-title {
    font-family: 'Poppins', sans-serif;
    font-weight: 700;
    font-size: 1.75rem;
    letter-spacing: -0.025em;
    color: #ffffff;
}

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

.tab-button {
    position: relative;
    overflow: hidden;
    transition: all 0.2s ease-in-out;
}

.tab-button::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    transition: left 0.4s ease-in-out;
}

.tab-button:hover::before {
    left: 100%;
}

.tab-button.active {
    background-color: #059669 !important;
    color: #ffffff !important;
    border-color: #059669 !important;
    box-shadow: 0 6px 20px rgba(5, 150, 105, 0.3) !important;
    transform: translateY(-1px) !important;
}

.tab-button:not(.active):hover {
    background-color: #059669 !important;
    color: #ffffff !important;
    border-color: #059669 !important;
    box-shadow: 0 4px 15px rgba(5, 150, 105, 0.4) !important;
    transform: translateY(-2px) scale(1.02) !important;
}

.tab-button:not(.active) {
    background-color: #ffffff !important;
    color: #4b5563 !important;
    border-color: #d1d5db !important;
}

@keyframes activeGlow {
    0% { box-shadow: 0 6px 20px rgba(5, 150, 105, 0.3); }
    50% { box-shadow: 0 6px 25px rgba(5, 150, 105, 0.5); }
    100% { box-shadow: 0 6px 20px rgba(5, 150, 105, 0.3); }
}

.tab-button.active {
    animation: activeGlow 3s ease-in-out infinite;
}

.tab-button i {
    transition: transform 0.2s ease-in-out;
}

.tab-button:hover i {
    transform: scale(1.1);
}

/* 3D Submit Button Styling */
.animated-button {
    font-size: 18px;
    background-color: #008542;
    color: #fff;
    text-shadow: 0 2px 0 rgb(0 0 0 / 25%);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    position: relative;
    border: 0;
    z-index: 1;
    user-select: none;
    cursor: pointer;
    text-transform: uppercase;
    letter-spacing: 1px;
    white-space: unset;
    padding: 0.8rem 1.5rem;
    text-decoration: none;
    font-weight: 900;
    transition: all 0.7s cubic-bezier(0, 0.8, 0.26, 0.99);
}

.animated-button p {
    margin: 0;
    display: flex;
    align-items: center;
}

.animated-button:before {
    position: absolute;
    pointer-events: none;
    top: 0;
    left: 0;
    display: block;
    width: 100%;
    height: 100%;
    content: "";
    transition: 0.7s cubic-bezier(0, 0.8, 0.26, 0.99);
    z-index: -1;
    background-color: #008542 !important;
    box-shadow: 0 -4px rgb(21 108 0 / 50%) inset,
        0 4px rgb(100 253 31 / 99%) inset, -4px 0 rgb(100 253 31 / 50%) inset,
        4px 0 rgb(21 108 0 / 50%) inset;
}

.animated-button:after {
    position: absolute;
    pointer-events: none;
    top: 0;
    left: 0;
    display: block;
    width: 100%;
    height: 100%;
    content: "";
    box-shadow: 0 4px 0 0 rgb(0 0 0 / 15%);
    transition: 0.7s cubic-bezier(0, 0.8, 0.26, 0.99);
}

.animated-button:hover:before {
    box-shadow: 0 -4px rgb(0 0 0 / 50%) inset, 0 4px rgb(255 255 255 / 20%) inset,
        -4px 0 rgb(255 255 255 / 20%) inset, 4px 0 rgb(0 0 0 / 50%) inset;
}

.animated-button:hover:after {
    box-shadow: 0 4px 0 0 rgb(0 0 0 / 15%);
}

.animated-button:active {
    transform: translateY(4px);
}

.animated-button:active:after {
    box-shadow: 0 0px 0 0 rgb(0 0 0 / 15%);
}

/* Traditional Calendar Styles */
.calendar-container {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    max-width: 100%;
    margin: 0 auto;
}

/* Make calendar bigger and more spacious */
#activity-calendar {
    font-size: 0.9rem;
    min-height: 600px;
}

#activity-calendar .calendar-header {
    padding: 1rem;
    font-size: 1.25rem;
}

#activity-calendar .calendar-day {
    min-height: 80px !important;
    padding: 8px !important;
    font-size: 0.8rem;
}

/* Calendar positioning and scroll fixes */
#calendar-content {
    max-height: calc(100vh - 150px);
    overflow-y: auto;
    scroll-behavior: smooth;
    padding-top: 0;
    margin-top: 0;
}

.dashboard-section {
    scroll-margin-top: 10px;
    margin-top: 0;
    padding-top: 0;
}

/* Reduce spacing in calendar container */
.calendar-container {
    margin-top: 0 !important;
    padding-top: 0 !important;
}

/* Admin-style content card */
.content-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    border: 1px solid #e5e7eb;
    overflow: hidden;
    transition: all 0.3s ease;
}

.content-card:hover {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

/* Icon wrapper styling */
.icon-wrapper {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #059669, #047857);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 6px -1px rgba(5, 150, 105, 0.3);
}

.calendar-header {
    background: linear-gradient(135deg, #059669, #047857);
    color: white;
    padding: 1.5rem;
    text-align: center;
}

.calendar-nav-btn {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
}

.calendar-nav-btn:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: scale(1.1);
}

.calendar-month-year {
    font-size: 1.5rem;
    font-weight: 600;
    margin: 0;
}

.calendar-weekdays {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
}

.calendar-weekday {
    padding: 1rem 0.5rem;
    text-align: center;
    font-weight: 600;
    font-size: 0.875rem;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.calendar-days {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    background: white;
}

.calendar-day {
    min-height: 120px;
    border-right: 1px solid #e2e8f0;
    border-bottom: 1px solid #e2e8f0;
    padding: 0.75rem;
    cursor: pointer;
    transition: all 0.2s ease;
    position: relative;
    display: flex;
    flex-direction: column;
}

.calendar-day:hover {
    background: #f0fdf4;
}

.calendar-day.other-month {
    background: #f8fafc;
    color: #cbd5e1;
}

.calendar-day.today {
    background: linear-gradient(135deg, #dcfce7, #bbf7d0);
    border: 2px solid #059669;
}

.calendar-day.has-activities {
    background: #fefce8;
}

.calendar-day.has-activities.today {
    background: linear-gradient(135deg, #dcfce7, #bbf7d0);
}

.day-number {
    font-weight: 600;
    font-size: 1rem;
    margin-bottom: 0.25rem;
    color: #1f2937;
}

.calendar-day.other-month .day-number {
    color: #cbd5e1;
}

.calendar-day.today .day-number {
    color: #059669;
    font-weight: 700;
    font-size: 1.1rem;
}

.day-activities {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 2px;
    overflow: hidden;
}

.activity-item {
    background: #059669;
    color: white;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 500;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    cursor: pointer;
    transition: all 0.2s ease;
}

.activity-item:hover {
    transform: scale(1.02);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.activity-item.status-approved {
    background: #059669;
    border-left: 3px solid #047857;
    font-weight: 600;
    box-shadow: 0 2px 4px rgba(5, 150, 105, 0.2);
}

.more-activities {
    background: #6b7280;
    color: white;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 0.7rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s ease;
}

.more-activities:hover {
    background: #4b5563;
}

.calendar-legend {
    padding: 1rem 1.5rem;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: #374151;
}

.legend-color {
    width: 12px;
    height: 12px;
    border-radius: 2px;
}

/* Comprehensive Mobile Responsive Styles - Make Everything Smaller */
@media (max-width: 768px) {
    /* Header Section */
    .max-w-7xl.mx-auto.py-6 {
        padding: 8px !important;
    }

    .bg-white.rounded-lg.p-4.shadow-sm {
        padding: 8px !important;
        margin-bottom: 8px !important;
    }

    .text-xl.font-semibold {
        font-size: 0.875rem !important;
    }

    .text-sm.text-gray-600 {
        font-size: 0.625rem !important;
    }

    /* Tab Navigation - Make Much Smaller */
    .bg-white.rounded-xl.p-6.shadow-xl {
        padding: 8px !important;
        margin-bottom: 8px !important;
    }

    .flex.space-x-3.bg-gray-100.rounded-xl.p-3 {
        padding: 4px !important;
        gap: 2px !important;
    }

    .tab-button {
        padding: 4px 8px !important;
        font-size: 0.5rem !important;
        border-radius: 4px !important;
    }

    .tab-button i {
        font-size: 0.5rem !important;
        margin-right: 2px !important;
    }

    /* Statistics Cards - Ultra Compact */
    .grid.grid-cols-1.md\\:grid-cols-2.lg\\:grid-cols-4.gap-6 {
        gap: 4px !important;
        margin-bottom: 8px !important;
    }

    .profile-card.rounded-2xl.p-6 {
        padding: 8px !important;
        border-radius: 6px !important;
    }

    .w-12.h-12.rounded-lg {
        width: 20px !important;
        height: 20px !important;
        border-radius: 3px !important;
    }

    .text-xl {
        font-size: 0.625rem !important;
    }

    .text-sm.font-medium {
        font-size: 0.5rem !important;
    }

    .text-2xl.font-bold {
        font-size: 0.75rem !important;
    }

    .ml-4 {
        margin-left: 6px !important;
    }

    /* Calendar Styles */
    .calendar-day {
        min-height: 60px !important;
        padding: 4px !important;
    }

    .day-number {
        font-size: 0.5rem !important;
    }

    .activity-item {
        font-size: 0.4rem !important;
        padding: 1px 2px !important;
    }

    .calendar-month-year {
        font-size: 0.75rem !important;
    }

    /* Content Sections */
    .bg-white.overflow-hidden.shadow-lg {
        margin-bottom: 8px !important;
    }

    .p-6.border-b {
        padding: 8px !important;
    }

    .text-lg.font-semibold {
        font-size: 0.75rem !important;
    }

    /* Tables */
    .px-6.py-3 {
        padding: 4px 8px !important;
    }

    .text-xs.font-medium {
        font-size: 0.5rem !important;
    }

    /* Process Steps */
    .text-center.p-4 {
        padding: 6px !important;
    }

    .text-2xl.text-green-600 {
        font-size: 0.75rem !important;
    }

    .font-semibold.text-gray-900 {
        font-size: 0.625rem !important;
    }

    /* Additional Mobile Optimizations */

    /* Buttons and Links */
    .btn, button {
        padding: 4px 8px !important;
        font-size: 0.5rem !important;
    }

    /* Icons */
    .fas, .far, .fab {
        font-size: 0.5rem !important;
    }

    /* Spacing Utilities */
    .mb-8 {
        margin-bottom: 8px !important;
    }

    .mb-6 {
        margin-bottom: 6px !important;
    }

    .mb-4 {
        margin-bottom: 4px !important;
    }

    .p-6 {
        padding: 8px !important;
    }

    .p-4 {
        padding: 6px !important;
    }

    .px-8 {
        padding-left: 6px !important;
        padding-right: 6px !important;
    }

    .py-4 {
        padding-top: 4px !important;
        padding-bottom: 4px !important;
    }

    /* Rounded corners */
    .rounded-xl {
        border-radius: 6px !important;
    }

    .rounded-2xl {
        border-radius: 8px !important;
    }

    /* Shadow adjustments */
    .shadow-xl {
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
    }

    .shadow-lg {
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
    }
}

/* Extra Small Mobile Devices */
@media (max-width: 480px) {
    /* Even smaller for very small screens */
    .tab-button {
        padding: 2px 4px !important;
        font-size: 0.4rem !important;
    }

    .profile-card.rounded-2xl.p-6 {
        padding: 4px !important;
    }

    .text-2xl.font-bold {
        font-size: 0.625rem !important;
    }

    .text-sm.font-medium {
        font-size: 0.4rem !important;
    }

    .w-12.h-12.rounded-lg {
        width: 16px !important;
        height: 16px !important;
    }

    .ml-4 {
        margin-left: 4px !important;
    }

    /* Hide some text on very small screens */
    .hidden-xs {
        display: none !important;
    }
}
</style>

<script>
function setActiveNav(clickedElement) {
    // Remove active class from all student nav links
    document.querySelectorAll('.student-nav-link').forEach(link => {
        link.classList.remove('active');
    });

    // Add active class to clicked element
    clickedElement.classList.add('active');
}

function showDashboardSection(section) {
    // Hide all dashboard sections
    document.querySelectorAll('.dashboard-section').forEach(section => {
        section.classList.add('hidden');
    });

    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });

    // Show the requested section and update page title
    if (section === 'overview') {
        document.getElementById('dashboard-content').classList.remove('hidden');
        setActiveNavItem('nav-overview');
        // Update page title
        document.querySelector('.top-bar h1').textContent = 'Overview';
    } else if (section === 'calendar') {
        document.getElementById('calendar-content').classList.remove('hidden');
        setActiveNavItem('nav-calendar');
        initializeCalendar(); // Initialize calendar when shown
        // Update page title
        document.querySelector('.top-bar h1').textContent = 'Activity Calendar';
    } else if (section === 'profile') {
        document.getElementById('profile-content').classList.remove('hidden');
        // Update page title
        document.querySelector('.top-bar h1').textContent = 'Profile Settings';
    }
}

function showTab(tabName) {
    // Hide all tab contents instantly
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });

    // Remove active class from all tab buttons and reset styles
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active');
        // Reset to inactive state
        button.classList.remove('text-white', 'bg-green-600', 'bg-green-700');
        button.classList.add('text-gray-600', 'bg-white', 'border-gray-200');
        button.style.backgroundColor = '';
        button.style.color = '';
        button.style.borderColor = '';
    });

    // Update sidebar navigation active states
    if (tabName === 'activities') {
        setActiveNavItem('nav-activities');
    } else if (tabName === 'submit') {
        setActiveNavItem('nav-submit');
    } else if (tabName === 'dashboard') {
        setActiveNavItem('nav-overview');
    }

    // Show selected tab content instantly
    const targetContent = document.getElementById(tabName + '-content');
    targetContent.classList.remove('hidden');

    // Add active class to selected tab button
    const activeButton = document.getElementById(tabName + '-tab');
    activeButton.classList.add('active');
    activeButton.classList.remove('text-gray-600', 'bg-white', 'border-gray-200');
    activeButton.classList.add('text-white', 'bg-green-600');
    activeButton.style.backgroundColor = '#059669';
    activeButton.style.color = '#ffffff';
    activeButton.style.borderColor = '#059669';
}

// Initialize tab states on page load
document.addEventListener('DOMContentLoaded', function() {
    // Set initial active state
    const dashboardTab = document.getElementById('dashboard-tab');
    if (dashboardTab) {
        dashboardTab.style.backgroundColor = '#059669';
        dashboardTab.style.color = '#ffffff';
        dashboardTab.style.borderColor = '#059669';
    }

    // Handle URL fragments for navigation from other pages
    const hash = window.location.hash.substring(1); // Remove the # symbol
    if (hash) {
        if (hash === 'calendar') {
            // Set calendar as active and show calendar section
            document.querySelectorAll('.student-nav-link').forEach(link => {
                link.classList.remove('active');
            });
            const calendarLink = document.querySelector('.student-nav-link[onclick*="calendar"]');
            if (calendarLink) {
                calendarLink.classList.add('active');
                showDashboardSection('calendar');
            }
        } else if (hash === 'activities') {
            // Set activities as active and show activities tab
            document.querySelectorAll('.student-nav-link').forEach(link => {
                link.classList.remove('active');
            });
            const activitiesLink = document.querySelector('.student-nav-link[onclick*="activities"]');
            if (activitiesLink) {
                activitiesLink.classList.add('active');
                showTab('activities');
            }
        } else if (hash === 'submit') {
            // Set submit as active and show submit tab
            document.querySelectorAll('.student-nav-link').forEach(link => {
                link.classList.remove('active');
            });
            const submitLink = document.querySelector('.student-nav-link[onclick*="submit"]');
            if (submitLink) {
                submitLink.classList.add('active');
                showTab('submit');
            }
        }
        // Clear the hash from URL after processing
        history.replaceState(null, null, window.location.pathname);
    }
});

// Calendar functionality - Traditional Calendar Class
let calendarActivities = [];

class TraditionalCalendar {
    constructor(containerId, activities) {
        this.container = document.getElementById(containerId);
        this.activities = activities;
        this.currentDate = new Date();
        this.currentMonth = this.currentDate.getMonth();
        this.currentYear = this.currentDate.getFullYear();
        this.render();
    }

    render() {
        const monthNames = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        this.container.innerHTML = `
            <div class="calendar-header">
                <div class="flex justify-between items-center">
                    <button onclick="calendar.previousMonth()" class="calendar-nav-btn">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <h2 class="calendar-month-year dashboard-calendar-title">
                        ${monthNames[this.currentMonth]} ${this.currentYear}
                    </h2>
                    <button onclick="calendar.nextMonth()" class="calendar-nav-btn">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
            <div class="calendar-weekdays">
                <div class="calendar-weekday">Sunday</div>
                <div class="calendar-weekday">Monday</div>
                <div class="calendar-weekday">Tuesday</div>
                <div class="calendar-weekday">Wednesday</div>
                <div class="calendar-weekday">Thursday</div>
                <div class="calendar-weekday">Friday</div>
                <div class="calendar-weekday">Saturday</div>
            </div>
            <div class="calendar-days">
                ${this.renderCalendarDays()}
            </div>
        `;
    }

    renderCalendarDays() {
        const daysInMonth = new Date(this.currentYear, this.currentMonth + 1, 0).getDate();
        const firstDayOfMonth = new Date(this.currentYear, this.currentMonth, 1).getDay();
        const daysInPrevMonth = new Date(this.currentYear, this.currentMonth, 0).getDate();

        let html = '';
        let dayCount = 1;
        let nextMonthDay = 1;

        // Calculate total cells needed (6 rows × 7 days = 42 cells)
        for (let i = 0; i < 42; i++) {
            let dayNumber, dateStr, isCurrentMonth = true, isToday = false;
            let dayClass = 'calendar-day';

            if (i < firstDayOfMonth) {
                // Previous month days
                dayNumber = daysInPrevMonth - firstDayOfMonth + i + 1;
                const prevMonth = this.currentMonth === 0 ? 11 : this.currentMonth - 1;
                const prevYear = this.currentMonth === 0 ? this.currentYear - 1 : this.currentYear;
                dateStr = `${prevYear}-${String(prevMonth + 1).padStart(2, '0')}-${String(dayNumber).padStart(2, '0')}`;
                dayClass += ' other-month';
                isCurrentMonth = false;
            } else if (dayCount <= daysInMonth) {
                // Current month days
                dayNumber = dayCount;
                dateStr = `${this.currentYear}-${String(this.currentMonth + 1).padStart(2, '0')}-${String(dayNumber).padStart(2, '0')}`;

                // Check if today
                const today = new Date();
                if (this.currentYear === today.getFullYear() &&
                    this.currentMonth === today.getMonth() &&
                    dayNumber === today.getDate()) {
                    isToday = true;
                }
                dayCount++;
            } else {
                // Next month days
                dayNumber = nextMonthDay;
                const nextMonth = this.currentMonth === 11 ? 0 : this.currentMonth + 1;
                const nextYear = this.currentMonth === 11 ? this.currentYear + 1 : this.currentYear;
                dateStr = `${nextYear}-${String(nextMonth + 1).padStart(2, '0')}-${String(dayNumber).padStart(2, '0')}`;
                dayClass += ' other-month';
                isCurrentMonth = false;
                nextMonthDay++;
            }

            // Get only approved activities for this date (including multi-day activities)
            const dayActivities = this.activities.filter(activity => {
                if (activity.status !== 'approved') return false;
                // Use string comparison to avoid timezone issues
                return dateStr >= activity.activity_date && dateStr <= activity.end_date;
            });

            if (isToday) {
                dayClass += ' today';
            }

            // Only mark days that have approved activities
            if (dayActivities.length > 0) {
                dayClass += ' has-activities';
            }

            html += `<div class="${dayClass}" onclick="calendar.showDayActivities('${dateStr}', '${dayNumber}', ${isCurrentMonth})">
                <div class="day-number">${dayNumber}</div>
                <div class="day-activities">
                    ${this.renderDayActivities(dayActivities)}
                </div>
            </div>`;
        }

        return html;
    }

    renderDayActivities(activities) {
        // Only show approved activities on the calendar
        const approvedActivities = activities.filter(activity => activity.status === 'approved');

        if (approvedActivities.length === 0) return '';

        let html = '';
        const maxVisible = 3;

        approvedActivities.slice(0, maxVisible).forEach(activity => {
            const title = activity.title.length > 12 ? activity.title.substring(0, 12) + '...' : activity.title;
            const timeInfo = `${activity.start_time} - ${activity.end_time}`;

            // Debug log
            console.log('Rendering activity:', activity.title, 'for date:', activity.activity_date);

            html += `<div class="activity-item status-approved"
                        title="${activity.title} (${timeInfo}) - Approved Activity"
                        onclick="event.stopPropagation(); calendar.showActivityDetails('${activity.id}')">
                ${title}
            </div>`;
        });

        if (approvedActivities.length > maxVisible) {
            const remaining = approvedActivities.length - maxVisible;
            html += `<div class="more-activities"
                        onclick="event.stopPropagation(); calendar.showAllDayActivities('${approvedActivities[0].activity_date}')"
                        title="Click to see all ${approvedActivities.length} activities">
                +${remaining} more
            </div>`;
        }

        return html;
    }

    previousMonth() {
        if (this.currentMonth === 0) {
            this.currentMonth = 11;
            this.currentYear--;
        } else {
            this.currentMonth--;
        }
        this.render();
    }

    nextMonth() {
        if (this.currentMonth === 11) {
            this.currentMonth = 0;
            this.currentYear++;
        } else {
            this.currentMonth++;
        }
        this.render();
    }

    showDayActivities(dateStr, dayNumber, isCurrentMonth) {
        // Only show approved activities in calendar modal (including multi-day activities)
        const approvedActivities = this.activities.filter(activity => {
            if (activity.status !== 'approved') return false;
            // Use string comparison to avoid timezone issues
            return dateStr >= activity.activity_date && dateStr <= activity.end_date;
        });
        if (approvedActivities.length === 0) return;

        this.showActivitiesModal(dateStr, approvedActivities, true);
    }

    showNoActivitiesMessage(dateStr) {
        const formattedDate = new Date(dateStr).toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        const modal = document.createElement('div');
        modal.className = 'activity-modal';
        modal.innerHTML = `
            <div class="activity-modal-content">
                <div class="activity-modal-header">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-semibold">${formattedDate}</h3>
                        <button onclick="this.closest('.activity-modal').remove()" class="text-white hover:text-gray-200">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    <p class="text-green-100 mt-1">No approved activities scheduled</p>
                </div>
                <div class="activity-modal-body">
                    <div class="text-center py-8">
                        <i class="fas fa-calendar-times text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-600 mb-2">No approved activities on this date</p>
                        <p class="text-sm text-gray-500">Only approved activities are displayed on the calendar</p>
                    </div>
                </div>
            </div>
        `;

        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.remove();
            }
        });

        document.body.appendChild(modal);
    }

    showAllDayActivities(dateStr) {
        // Only show approved activities in calendar modal (including multi-day activities)
        const approvedActivities = this.activities.filter(activity => {
            if (activity.status !== 'approved') return false;
            // Use string comparison to avoid timezone issues
            return dateStr >= activity.activity_date && dateStr <= activity.end_date;
        });
        this.showActivitiesModal(dateStr, approvedActivities, true);
    }

    showActivitiesModal(dateStr, activities, isCalendarView = false) {
        const date = new Date(dateStr);
        const formattedDate = date.toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4';
        modal.innerHTML = `
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-96 overflow-hidden">
                <div class="bg-gradient-to-r from-green-600 to-green-700 text-white p-6">
                    <h3 class="text-xl font-bold">Activities on ${formattedDate}</h3>
                    <p class="text-green-100 mt-1">${activities.length} approved ${activities.length === 1 ? 'activity' : 'activities'}</p>
                </div>
                <div class="p-6 overflow-y-auto max-h-80">
                    ${activities.map(activity => `
                        <div class="border-l-4 border-green-500 bg-green-50 p-4 mb-4 rounded-r-lg">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-semibold text-gray-900">${activity.title}</h4>
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                    Approved
                                </span>
                            </div>
                            <div class="text-sm text-gray-600 space-y-1">
                                <p><i class="fas fa-clock mr-2"></i>${activity.start_time} - ${activity.end_time}</p>
                            </div>
                        </div>
                    `).join('')}
                </div>
                <div class="bg-gray-50 px-6 py-4 flex justify-end">
                    <button onclick="this.closest('.fixed').remove()"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                        Close
                    </button>
                </div>
            </div>
        `;

        // Close modal when clicking outside
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.remove();
            }
        });

        document.body.appendChild(modal);
    }

    showActivityDetails(activityId) {
        // This could be expanded to show full activity details
        console.log('Show activity details for ID:', activityId);
    }
}

function initializeCalendar() {
    // Debug: Check if we have activities
    console.log('Calendar Activities:', calendarActivities);

    // Filter only approved activities for calendar display
    const approvedActivities = calendarActivities.filter(activity => activity.status === 'approved');
    console.log('Number of approved activities for calendar:', approvedActivities.length);

    // Initialize traditional calendar - starts with current month
    window.calendar = new TraditionalCalendar('activity-calendar', approvedActivities);
}

// Initialize Statistics Cards Animations
document.addEventListener('DOMContentLoaded', function() {
    // Trigger animations for statistics cards
    const statsCards = document.querySelectorAll('.animated-stat-card');

    // Add intersection observer for scroll-triggered animations
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animationPlayState = 'running';
            }
        });
    }, {
        threshold: 0.1
    });

    statsCards.forEach(card => {
        observer.observe(card);
    });

    // Add click ripple effect
    statsCards.forEach(card => {
        card.addEventListener('click', function(e) {
            const ripple = document.createElement('div');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;

            ripple.style.cssText = `
                position: absolute;
                width: ${size}px;
                height: ${size}px;
                left: ${x}px;
                top: ${y}px;
                background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, transparent 70%);
                border-radius: 50%;
                transform: scale(0);
                animation: ripple 0.6s ease-out;
                pointer-events: none;
                z-index: 10;
            `;

            this.querySelector('.card-inner').appendChild(ripple);

            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });

    // Add CSS for ripple animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes ripple {
            to {
                transform: scale(2);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);
});
</script>

<script>
// Populate calendar activities from server data (ALL approved activities)
calendarActivities = [
    @foreach($calendarActivities as $activity)
    {
        id: {{ $activity->id }},
        title: "{{ addslashes($activity->title) }}",
        activity_date: "{{ $activity->activity_date->format('Y-m-d') }}",
        end_date: "{{ $activity->end_date->format('Y-m-d') }}",
        start_time: "{{ \Carbon\Carbon::parse($activity->start_time)->format('g:i A') }}",
        end_time: "{{ \Carbon\Carbon::parse($activity->end_time)->format('g:i A') }}",
        location: "{{ addslashes($activity->location) }}",
        status: "{{ $activity->status }}"
    },
    @endforeach
];

// Calendar will show ALL approved activities from all users (including past activities)
console.log('Calendar Activities loaded:', calendarActivities.length, 'approved activities from all users');
if (calendarActivities.length > 0) {
    console.log('Sample activity data:', calendarActivities[0]);
}

// Filter only approved activities for calendar display
const approvedActivities = calendarActivities.filter(activity => activity.status === 'approved');
console.log('Approved activities for calendar:', approvedActivities.length);
</script>

@endsection
