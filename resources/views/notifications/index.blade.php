@extends('layouts.sidebar')

@section('title', 'Notifications')
@section('page-title', 'Notifications')

@section('content')
<div class="mb-6">
    <!-- Mark All Read Button -->
    @if($notifications->where('is_read', false)->count() > 0)
        <div class="flex justify-end mb-4">
            <!-- AJAX Button -->
            <button onclick="markAllAsRead()"
                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed mr-2"
                    id="markAllReadBtn">
                <i class="fas fa-check-double mr-2"></i> Mark All Read
            </button>

            <!-- Fallback Form (hidden by default) -->
            <form method="POST" action="{{ route('notifications.read-all') }}" class="inline" id="markAllReadForm" style="display: none;">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200">
                    <i class="fas fa-check-double mr-2"></i> Mark All Read (Fallback)
                </button>
            </form>
        </div>
    @endif
</div>

    <!-- Notifications List -->
    <div class="bg-white overflow-hidden shadow-green sm:rounded-xl">
            @forelse($notifications as $notification)
                <div class="border-b border-gray-200 {{ !$notification->is_read ? 'bg-green-50' : '' }}">
                    <div class="p-6 @if(!auth()->user()->isAdmin()) border-2 border-yellow-400 rounded-lg @endif">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start space-x-4">
                                <!-- Icon -->
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center
                                        @if($notification->type === 'success') bg-green-100
                                        @elseif($notification->type === 'error') bg-red-100
                                        @elseif($notification->type === 'warning') bg-yellow-100
                                        @else bg-blue-100
                                        @endif">
                                        <i class="fas fa-{{ $notification->getIcon() }} 
                                            @if($notification->type === 'success') text-green-600
                                            @elseif($notification->type === 'error') text-red-600
                                            @elseif($notification->type === 'warning') text-yellow-600
                                            @else text-blue-600
                                            @endif"></i>
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $notification->title }}</h3>
                                        @if(!$notification->is_read)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                New
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-gray-700 mt-1 whitespace-pre-line">{{ $notification->message }}</p>
                                    <div class="flex items-center space-x-4 mt-3">
                                        <span class="text-sm text-gray-500">
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ $notification->created_at->diffForHumans() }}
                                        </span>
                                        @if($notification->activity)
                                            @php
                                                $role = auth()->user()->role;
                                                $routePrefix = '';
                                                
                                                if ($role === 'student_officer') {
                                                    $routePrefix = 'student.';
                                                } elseif ($role === 'psg_adviser') {
                                                    $routePrefix = 'psg.';
                                                } elseif ($role === 'admin') {
                                                    $routePrefix = '';
                                                } else {
                                                    $routePrefix = $role . '.';
                                                }
                                                
                                                if ($role === 'admin') {
                                                    $actionUrl = route('activities.show', $notification->activity);
                                                } else {
                                                    $actionUrl = route($routePrefix . 'show-activity', $notification->activity);
                                                }
                                            @endphp
                                            <a href="{{ $actionUrl }}" 
                                               class="text-sm text-green-600 hover:text-green-700 font-medium">
                                                <i class="fas fa-external-link-alt mr-1"></i>
                                                View Activity
                                            </a>
                                            @if(auth()->user()->isStudent() 
                                                && $notification->activity->user_id === auth()->id() 
                                                && $notification->activity->workflow_status === 'rejected')
                                                <a href="{{ route('activities.edit', $notification->activity) }}"
                                                   class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium bg-yellow-100 text-yellow-800 hover:bg-yellow-200 transition-colors">
                                                    <i class="fas fa-edit mr-1"></i>
                                                    Revise & Resubmit
                                                </a>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center space-x-2">
                                @if(!$notification->is_read)
                                    <button onclick="markAsRead({{ $notification->id }})"
                                            class="text-green-600 hover:text-green-700 p-2 rounded-lg hover:bg-green-50 disabled:opacity-50 disabled:cursor-not-allowed"
                                            title="Mark as read"
                                            id="markReadBtn-{{ $notification->id }}">
                                        <i class="fas fa-check"></i>
                                    </button>
                                @endif
                                <button onclick="deleteNotification({{ $notification->id }})"
                                        class="text-red-600 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 disabled:opacity-50 disabled:cursor-not-allowed"
                                        title="Delete"
                                        id="deleteBtn-{{ $notification->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-bell-slash text-2xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No notifications yet</h3>
                    <p class="text-gray-600">You'll receive notifications here when there are updates to your activities.</p>
                    <div class="mt-6">
                        <a href="{{ route('activities.create') }}" class="btn-primary inline-flex items-center px-4 py-2 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200">
                            <i class="fas fa-plus mr-2"></i> Submit Your First Activity
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

    <!-- Pagination -->
    @if($notifications->hasPages())
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @endif
@endsection

@push('scripts')
<script>
// Mark all notifications as read
function markAllAsRead() {
    const button = document.getElementById('markAllReadBtn');
    if (button) {
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Marking...';
    }

    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error('CSRF token not found');
        alert('Security token not found. Please refresh the page and try again.');
        if (button) {
            button.disabled = false;
            button.innerHTML = '<i class="fas fa-check-double mr-2"></i> Mark All Read';
        }
        return;
    }

    console.log('Sending mark all as read request...');

    fetch('{{ route("notifications.read-all") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', [...response.headers.entries()]);

        if (!response.ok) {
            return response.text().then(text => {
                console.error('Response text:', text);
                throw new Error(`HTTP error! status: ${response.status}, response: ${text}`);
            });
        }

        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);

        if (data.success) {
            // Show success message briefly before reload
            if (button) {
                button.innerHTML = '<i class="fas fa-check mr-2"></i> Success!';
            }

            // Reload the page after a short delay
            setTimeout(() => {
                window.location.reload();
            }, 500);
        } else {
            throw new Error(data.message || 'Failed to mark all as read');
        }
    })
    .catch(error => {
        console.error('Error marking all as read:', error);

        // More specific error messages
        let errorMessage = 'Failed to mark all notifications as read. Please try again.';
        if (error.message.includes('403')) {
            errorMessage = 'You do not have permission to perform this action.';
        } else if (error.message.includes('419')) {
            errorMessage = 'Session expired. Please refresh the page and try again.';
        } else if (error.message.includes('500')) {
            errorMessage = 'Server error occurred. Please try again later.';
        }

        alert(errorMessage + '\n\nA fallback form will be shown for you to try again.');

        if (button) {
            button.disabled = false;
            button.innerHTML = '<i class="fas fa-check-double mr-2"></i> Mark All Read';
        }

        // Show fallback form
        const fallbackForm = document.getElementById('markAllReadForm');
        if (fallbackForm) {
            fallbackForm.style.display = 'inline';
        }
    });
}

// Mark single notification as read
function markAsRead(notificationId) {
    const button = document.getElementById(`markReadBtn-${notificationId}`);
    if (button) {
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    }

    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error('CSRF token not found');
        alert('Security token not found. Please refresh the page and try again.');
        if (button) {
            button.disabled = false;
            button.innerHTML = '<i class="fas fa-check"></i>';
        }
        return;
    }

    fetch(`/notifications/${notificationId}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => {
                console.error('Response text:', text);
                throw new Error(`HTTP error! status: ${response.status}, response: ${text}`);
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Show success briefly before reload
            if (button) {
                button.innerHTML = '<i class="fas fa-check"></i>';
            }

            setTimeout(() => {
                window.location.reload();
            }, 300);
        } else {
            throw new Error(data.message || 'Failed to mark as read');
        }
    })
    .catch(error => {
        console.error('Error marking notification as read:', error);
        alert('Failed to mark notification as read. Please try again.');
        if (button) {
            button.disabled = false;
            button.innerHTML = '<i class="fas fa-check"></i>';
        }
    });
}

// Delete notification
function deleteNotification(notificationId) {
    if (!confirm('Are you sure you want to delete this notification?')) {
        return;
    }

    const button = document.getElementById(`deleteBtn-${notificationId}`);
    if (button) {
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    }

    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error('CSRF token not found');
        alert('Security token not found. Please refresh the page and try again.');
        if (button) {
            button.disabled = false;
            button.innerHTML = '<i class="fas fa-trash"></i>';
        }
        return;
    }

    fetch(`/notifications/${notificationId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('Delete response status:', response.status);
        console.log('Delete response headers:', [...response.headers.entries()]);

        if (!response.ok) {
            return response.text().then(text => {
                console.error('Delete response text:', text);
                throw new Error(`HTTP error! status: ${response.status}, response: ${text}`);
            });
        }

        return response.json();
    })
    .then(data => {
        console.log('Delete response data:', data);

        if (data.success) {
            // Show success briefly before reload
            if (button) {
                button.innerHTML = '<i class="fas fa-check"></i>';
            }

            setTimeout(() => {
                window.location.reload();
            }, 300);
        } else {
            throw new Error(data.message || 'Failed to delete notification');
        }
    })
    .catch(error => {
        console.error('Error deleting notification:', error);

        // More specific error message
        let errorMessage = 'Failed to delete notification. Please try again.';
        if (error.message.includes('403')) {
            errorMessage = 'You do not have permission to delete this notification.';
        } else if (error.message.includes('404')) {
            errorMessage = 'Notification not found. It may have already been deleted.';
        } else if (error.message.includes('419')) {
            errorMessage = 'Session expired. Please refresh the page and try again.';
        } else if (error.message.includes('500')) {
            errorMessage = 'Server error occurred. Please try again later.';
        }

        alert(errorMessage);

        if (button) {
            button.disabled = false;
            button.innerHTML = '<i class="fas fa-trash"></i>';
        }
    });
}

// Auto-refresh notification count every 30 seconds
setInterval(function() {
    fetch('{{ route("notifications.unread-count") }}')
        .then(response => response.json())
        .then(data => {
            // Update notification badge in navigation
            const badges = document.querySelectorAll('.notification-badge, [x-text="unreadCount"]');
            badges.forEach(badge => {
                if (data.unread_count > 0) {
                    badge.textContent = data.unread_count;
                    badge.style.display = 'flex';
                } else {
                    badge.style.display = 'none';
                }
            });

            // Update Alpine.js data if available
            if (window.Alpine && window.Alpine.store) {
                // Try to update Alpine.js reactive data
                const notificationElements = document.querySelectorAll('[x-data*="unreadCount"]');
                notificationElements.forEach(el => {
                    if (el._x_dataStack && el._x_dataStack[0]) {
                        el._x_dataStack[0].unreadCount = data.unread_count;
                    }
                });
            }
        })
        .catch(error => console.error('Error updating notification count:', error));
}, 30000);
</script>
@endpush
