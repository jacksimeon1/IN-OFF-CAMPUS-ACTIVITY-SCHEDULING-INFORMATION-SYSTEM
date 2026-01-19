<x-dashboard-layout>
    <div class="space-y-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Welcome Message -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">
                                Welcome, {{ Auth::user()->name }}!
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">
                                You are logged in as: <span class="font-medium">{{ Auth::user()->getRoleDisplayName() }}</span>
                            </p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>
                                Active
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Activities -->
                <a href="{{ route('activities.index') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-green-500 hover:shadow-lg transition-all duration-200 transform hover:scale-105">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-clipboard-list text-green-600 text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Activities</p>
                                <p class="text-2xl font-bold text-green-600">
                                    {{ \App\Models\Activity::count() }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">All submitted activities</p>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Pending Approvals -->
                <a href="{{ route('activities.index') }}?status=pending" class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-green-500 hover:shadow-lg transition-all duration-200 transform hover:scale-105">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-clock text-green-600 text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Pending Approvals</p>
                                <p class="text-2xl font-bold text-green-600">
                                    {{ \App\Models\Activity::whereNotIn('workflow_status', ['approved_by_vp', 'rejected'])->count() }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">Awaiting review</p>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Approved Activities -->
                <a href="{{ route('activities.index') }}?status=approved" class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-green-500 hover:shadow-lg transition-all duration-200 transform hover:scale-105">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Approved Activities</p>
                                <p class="text-2xl font-bold text-green-600">
                                    {{ \App\Models\Activity::where('workflow_status', 'approved_by_vp')->count() }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">Fully approved</p>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- This Month -->
                <a href="{{ route('activities.index') }}?month={{ now()->month }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-green-500 hover:shadow-lg transition-all duration-200 transform hover:scale-105">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-calendar-alt text-green-600 text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">This Month</p>
                                <p class="text-2xl font-bold text-green-600">
                                    {{ \App\Models\Activity::whereMonth('created_at', now()->month)->count() }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">{{ now()->format('F Y') }}</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>



            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-bolt text-green-600 mr-2"></i>
                        Quick Actions
                    </h3>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                        <!-- Manage Activities -->
                        <a href="{{ route('activities.index') }}" 
                           class="block bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow duration-200">
                            <div class="flex flex-col items-center text-center">
                                <div class="relative mb-4">
                                    <div class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center">
                                        <i class="fas fa-th-list text-white text-xl"></i>
                                    </div>
                                    <div class="absolute -top-1 -right-1 w-6 h-6 bg-green-600 text-white rounded-full flex items-center justify-center text-xs font-bold">
                                        {{ \App\Models\Activity::count() }}
                                    </div>
                                </div>
                                <h4 class="font-semibold text-gray-900 mb-2">Manage Activities</h4>
                                <p class="text-sm text-gray-600 leading-relaxed">Review, approve, or reject submitted activities.</p>
                                <div class="mt-4">
                                    <span class="inline-flex items-center text-green-600 text-sm font-medium">
                                        View All Activities
                                        <i class="fas fa-arrow-right ml-2"></i>
                                    </span>
                                </div>
                            </div>
                        </a>

                        <!-- Activity Calendar -->
                        <a href="{{ route('activities.index') }}?view=calendar" 
                           class="block bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow duration-200">
                            <div class="flex flex-col items-center text-center">
                                <div class="relative mb-4">
                                    <div class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center">
                                        <i class="fas fa-calendar-alt text-white text-xl"></i>
                                    </div>
                                    <div class="absolute -top-1 -right-1 w-6 h-6 bg-green-600 text-white rounded-full flex items-center justify-center text-xs font-bold">
                                        {{ \App\Models\Activity::whereMonth('activity_date', now()->month)->count() }}
                                    </div>
                                </div>
                                <h4 class="font-semibold text-gray-900 mb-2">Activity Calendar</h4>
                                <p class="text-sm text-gray-600 leading-relaxed">View scheduled and approved activities</p>
                                <div class="mt-4">
                                    <span class="inline-flex items-center text-green-600 text-sm font-medium">
                                        Open Calendar
                                        <i class="fas fa-arrow-right ml-2"></i>
                                    </span>
                                </div>
                            </div>
                        </a>

                        <!-- Edit Accounts -->
                        <a href="{{ route('activities.index') }}?view=users" 
                           class="block bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow duration-200">
                            <div class="flex flex-col items-center text-center">
                                <div class="relative mb-4">
                                    <div class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center">
                                        <i class="fas fa-users text-white text-xl"></i>
                                    </div>
                                    <div class="absolute -top-1 -right-1 w-6 h-6 bg-green-600 text-white rounded-full flex items-center justify-center text-xs font-bold">
                                        {{ \App\Models\User::count() }}
                                    </div>
                                </div>
                                <h4 class="font-semibold text-gray-900 mb-2">Edit Accounts</h4>
                                <p class="text-sm text-gray-600 leading-relaxed">Manage user accounts and permissions</p>
                                <div class="mt-4">
                                    <span class="inline-flex items-center text-green-600 text-sm font-medium">
                                        Manage Users
                                        <i class="fas fa-arrow-right ml-2"></i>
                                    </span>
                                </div>
                            </div>
                        </a>

                        <!-- Generate Reports -->
                        <a href="{{ route('activities.index') }}?view=reports" 
                           class="block bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow duration-200">
                            <div class="flex flex-col items-center text-center">
                                <div class="relative mb-4">
                                    <div class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center">
                                        <i class="fas fa-chart-line text-white text-xl"></i>
                                    </div>
                                    <div class="absolute -top-1 -right-1 w-6 h-6 bg-green-600 text-white rounded-full flex items-center justify-center text-xs font-bold">
                                        {{ \App\Models\Activity::where('workflow_status', 'approved_by_vp')->count() }}
                                    </div>
                                </div>
                                <h4 class="font-semibold text-gray-900 mb-2">Generate Reports</h4>
                                <p class="text-sm text-gray-600 leading-relaxed">Create comprehensive reports on activities, users, and statistics</p>
                                <div class="mt-4">
                                    <span class="inline-flex items-center text-green-600 text-sm font-medium">
                                        Generate Reports
                                        <i class="fas fa-arrow-right ml-2"></i>
                                    </span>
                                </div>
                            </div>
                        </a>

                    </div>
                </div>
            </div>

            <!-- Quick Search Modal -->
            <div id="quick-search-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Quick Search</h3>
                            <button onclick="document.getElementById('quick-search-modal').classList.add('hidden')" 
                                    class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="relative">
                            <input type="text" 
                                   placeholder="Search activities, users, or anything..." 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   onkeyup="if(event.key === 'Enter') { window.location.href = '{{ route('activities.index') }}?search=' + this.value; }">
                            <div class="absolute right-3 top-3 text-gray-400">
                                <i class="fas fa-search"></i>
                            </div>
                        </div>
                        <div class="mt-4 text-sm text-gray-500">
                            <p>Press <kbd class="px-2 py-1 bg-gray-100 rounded">Enter</kbd> to search or <kbd class="px-2 py-1 bg-gray-100 rounded">Esc</kbd> to close</p>
                        </div>
                    </div>
                </div>
            </div>

    </div>
</x-dashboard-layout>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quick Search Modal functionality
    const modal = document.getElementById('quick-search-modal');
    const searchInput = modal.querySelector('input[type="text"]');
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl+K or Cmd+K to open quick search
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            openQuickSearch();
        }
        
        // Escape to close modal
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeQuickSearch();
        }
    });
    
    // Click outside modal to close
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeQuickSearch();
        }
    });
    
    function openQuickSearch() {
        modal.classList.remove('hidden');
        searchInput.focus();
        // Add animation class
        modal.querySelector('.bg-white').style.transform = 'scale(0.95)';
        modal.querySelector('.bg-white').style.opacity = '0';
        setTimeout(() => {
            modal.querySelector('.bg-white').style.transform = 'scale(1)';
            modal.querySelector('.bg-white').style.opacity = '1';
            modal.querySelector('.bg-white').style.transition = 'all 0.2s ease-out';
        }, 10);
    }
    
    function closeQuickSearch() {
        modal.querySelector('.bg-white').style.transform = 'scale(0.95)';
        modal.querySelector('.bg-white').style.opacity = '0';
        setTimeout(() => {
            modal.classList.add('hidden');
            searchInput.value = '';
        }, 200);
    }
    
    // Quick search suggestions (you can expand this)
    const quickActions = [
        { name: 'Submit New Activity', url: '{{ route("activities.create") }}', icon: 'fas fa-plus' },
        { name: 'View All Activities', url: '{{ route("activities.index") }}', icon: 'fas fa-list' },
        { name: 'Profile Settings', url: '{{ route("profile.edit") }}', icon: 'fas fa-user-cog' },
        @if(Auth::user()->role === 'student')
        { name: 'My Activities', url: '{{ route("activities.index") }}?user={{ Auth::id() }}', icon: 'fas fa-user-check' },
        @endif
        @if(in_array(Auth::user()->role, ['adviser', 'dean', 'psg_adviser', 'director', 'vp']))
        { name: 'Pending Reviews', url: '{{ route(Auth::user()->role . ".activities") }}', icon: 'fas fa-tasks' },
        { name: 'Analytics', url: '{{ route("activities.index") }}?view=analytics', icon: 'fas fa-chart-bar' },
        @endif
    ];
    
    // Add hover effects to quick action cards
    document.querySelectorAll('.group').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-4px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Add loading states for action cards
    document.querySelectorAll('a[href]').forEach(link => {
        link.addEventListener('click', function(e) {
            const icon = this.querySelector('i');
            if (icon && !icon.classList.contains('fa-spinner')) {
                const originalClass = icon.className;
                icon.className = 'fas fa-spinner fa-spin text-white text-lg';
                
                // Restore original icon after a short delay if navigation fails
                setTimeout(() => {
                    icon.className = originalClass;
                }, 3000);
            }
        });
    });
});

// Add some CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .quick-action-card {
        animation: fadeInUp 0.3s ease-out forwards;
    }
    
    .quick-action-card:nth-child(1) { animation-delay: 0.1s; }
    .quick-action-card:nth-child(2) { animation-delay: 0.2s; }
    .quick-action-card:nth-child(3) { animation-delay: 0.3s; }
    .quick-action-card:nth-child(4) { animation-delay: 0.4s; }
    .quick-action-card:nth-child(5) { animation-delay: 0.5s; }
    .quick-action-card:nth-child(6) { animation-delay: 0.6s; }
    .quick-action-card:nth-child(7) { animation-delay: 0.7s; }
    .quick-action-card:nth-child(8) { animation-delay: 0.8s; }
    
    kbd {
        font-family: ui-monospace, SFMono-Regular, "SF Mono", Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        font-size: 0.75rem;
        font-weight: 600;
        line-height: 1;
        color: #374151;
        background-color: #f3f4f6;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        padding: 0.25rem 0.5rem;
    }
`;
document.head.appendChild(style);
</script>
@endpush
