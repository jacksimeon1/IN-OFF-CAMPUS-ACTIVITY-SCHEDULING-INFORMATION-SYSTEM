@extends('layouts.admin')

@section('title', 'Edit Activity Status')
@section('page-title', 'Edit Activity Status')
@section('page-subtitle', 'Review and update activity approval status')

@push('styles')
<style>
.button {
    position: relative;
    width: 150px;
    height: 40px;
    cursor: pointer;
    display: flex;
    align-items: center;
    border: 1px solid #34974d;
    background-color: #3aa856;
}

.button, .button__icon, .button__text {
    transition: all 0.3s;
}

.button .button__text {
    transform: translateX(30px);
    color: #fff;
    font-weight: 600;
}

.button .button__icon {
    position: absolute;
    transform: translateX(109px);
    height: 100%;
    width: 39px;
    background-color: #34974d;
    display: flex;
    align-items: center;
    justify-content: center;
}

.button .svg {
    width: 30px;
    stroke: #fff;
}

.button:hover {
    background: #34974d;
}

.button:hover .button__text {
    color: transparent;
}

.button:hover .button__icon {
    width: 148px;
    transform: translateX(0);
}

.button:active .button__icon {
    background-color: #2e8644;
}

.button:active {
    border: 1px solid #2e8644;
}
</style>
@endpush

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Activity Details (Read-only) -->
        <div class="bg-white overflow-hidden shadow-green sm:rounded-xl mb-6">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-info-circle text-green-600 mr-2"></i>
                    Activity Details (Read-only)
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                        <div class="p-3 bg-gray-50 rounded-lg text-sm text-gray-900">{{ $activity->title }}</div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">User</label>
                        <div class="p-3 bg-gray-50 rounded-lg text-sm text-gray-900">{{ $activity->user->name }}</div>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <div class="p-3 bg-gray-50 rounded-lg text-sm text-gray-900">{{ $activity->description }}</div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                        <div class="p-3 bg-gray-50 rounded-lg text-sm text-gray-900">{{ ucfirst(str_replace('-', ' ', $activity->type)) }}</div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Activity Date</label>
                        <div class="p-3 bg-gray-50 rounded-lg text-sm text-gray-900">{{ $activity->activity_date->format('M d, Y') }}</div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                        <div class="p-3 bg-gray-50 rounded-lg text-sm text-gray-900">{{ $activity->location }}</div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Status</label>
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($activity->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($activity->status === 'recommended') bg-blue-100 text-blue-800
                                @elseif($activity->status === 'approved') bg-green-100 text-green-800
                                @elseif($activity->status === 'rejected') bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($activity->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Update Form -->
        <div class="bg-white overflow-hidden shadow-green sm:rounded-xl">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-cog text-green-600 mr-2"></i>
                    Update Status
                </h3>
            </div>
            <div class="p-6">
                <form id="update-status-form" method="POST" action="{{ route('admin.activities.update-status', $activity) }}" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <!-- Status Selection -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">New Status *</label>
                        <select name="status" id="status" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                            <option value="">Select Status</option>
                            <option value="pending" {{ old('status', $activity->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ old('status', $activity->status) === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ old('status', $activity->status) === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                        @error('status')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Admin Notes -->
                    <div>
                        <label for="admin_notes" class="block text-sm font-medium text-gray-700 mb-2">Admin Notes</label>
                        <textarea name="admin_notes" id="admin_notes" rows="4" 
                                  class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                  placeholder="Add any notes or comments about this decision...">{{ old('admin_notes') }}</textarea>
                        @error('admin_notes')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Rejection Reason (shown only when rejected is selected) -->
                    <div id="rejection_reason_field" style="display: none;">
                        <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason *</label>
                        <textarea name="rejection_reason" id="rejection_reason" rows="3" 
                                  class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                  placeholder="Please provide a reason for rejection...">{{ old('rejection_reason') }}</textarea>
                        @error('rejection_reason')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <style>
                    .skew-button {
                        background: #fff;
                        border: none;
                        padding: 10px 20px;
                        display: inline-block;
                        font-size: 15px;
                        font-weight: 600;
                        width: 140px;
                        text-transform: uppercase;
                        cursor: pointer;
                        transform: skew(-21deg);
                        position: relative;
                        text-decoration: none;
                        color: #000;
                        overflow: hidden;
                        z-index: 1;
                        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                    }

                    .skew-button span {
                        display: inline-block;
                        transform: skew(21deg);
                    }

                    .skew-button::before {
                        content: '';
                        position: absolute;
                        top: 0;
                        bottom: 0;
                        right: 100%;
                        left: 0;
                        background: rgb(20, 20, 20);
                        opacity: 0;
                        z-index: -1;
                        transition: all 0.5s;
                    }

                    .skew-button:hover {
                        color: #fff;
                    }

                    .skew-button:hover::before {
                        left: 0;
                        right: 0;
                        opacity: 1;
                    }

                    .cancel-button {
                        background: #fee2e2;
                        color: #dc2626;
                        margin-right: 20px;
                    }

                    .cancel-button::before {
                        background: #dc2626;
                    }

                    .update-button {
                        background: #d1fae5;
                        color: #059669;
                        margin-left: 20px;
                    }

                    .update-button::before {
                        background: #059669;
                    }

                    </style>
                </form>
                <!-- Unified Action Bar: Cancel + Delete on left, Update on right -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <div class="flex items-center">
                        <a href="{{ route('admin.activities') }}" class="skew-button cancel-button">
                            <span>Cancel</span>
                        </a>
                        <form action="{{ route('admin.activities.delete', $activity) }}" method="POST" class="ml-4"
                              onsubmit="return confirm('Delete activity \"{{ addslashes($activity->title) }}\"? This cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="skew-button delete-button">
                                <span>Delete</span>
                            </button>
                        </form>
                    </div>
                    <div>
                        <button type="submit" form="update-status-form" class="skew-button update-button">
                            <span>Update Status</span>
                        </button>
                    </div>
                </div>
                <style>
                    .delete-button { background: #fee2e2; color: #b91c1c; }
                    .delete-button::before { background: #b91c1c; }
                </style>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Initialize rejection reason field on page load
    const statusSelect = document.getElementById('status');
    if (statusSelect.value === 'rejected') {
        document.getElementById('rejection_reason_field').style.display = 'block';
        document.getElementById('rejection_reason').required = true;
    }

    // Show/hide rejection reason field based on status selection
    document.getElementById('status').addEventListener('change', function() {
        const rejectionField = document.getElementById('rejection_reason_field');
        const rejectionTextarea = document.getElementById('rejection_reason');

        if (this.value === 'rejected') {
            rejectionField.style.display = 'block';
            rejectionTextarea.required = true;
        } else {
            rejectionField.style.display = 'none';
            rejectionTextarea.required = false;
            rejectionTextarea.value = '';
        }
    });
</script>
@endpush
@endsection
