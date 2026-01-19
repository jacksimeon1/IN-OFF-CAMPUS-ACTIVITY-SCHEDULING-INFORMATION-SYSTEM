@extends('layouts.admin')

@section('title', 'Users Report')

@push('styles')
<style>
    /* Hide sidebar and nav section on this page */
    .admin-sidebar,
    .admin-sidebar-overlay,
    .admin-toggle-btn,
    aside.admin-sidebar,
    #adminSidebar,
    #adminSidebarOverlay,
    #adminToggleBtn,
    .admin-nav-section {
        display: none !important;
        visibility: hidden !important;
    }

    /* Expand main content to full width */
    .admin-main,
    .admin-content {
        margin-left: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
    }

    /* Form Styles */
    .form-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
    }

    .form-card-header {
        background: linear-gradient(135deg, #059669, #047857);
        color: white;
        padding: 1.5rem;
        border-radius: 12px 12px 0 0;
    }

    .form-card-body {
        padding: 1.5rem;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .form-input {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 0.875rem;
        transition: border-color 0.2s ease;
    }

    .form-input:focus {
        outline: none;
        border-color: #059669;
        box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
    }

    .btn {
        display: inline-flex;
        align-items: center;
        padding: 0.75rem 1.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        border-radius: 6px;
        transition: all 0.2s ease;
        text-decoration: none;
        border: none;
        cursor: pointer;
    }

    .btn-primary {
        background: #059669;
        color: white;
    }

    .btn-primary:hover {
        background: #047857;
    }

    .btn-secondary {
        background: #6b7280;
        color: white;
    }

    .btn-secondary:hover {
        background: #4b5563;
    }

    .btn-outline {
        background: transparent;
        color: #059669;
        border: 1px solid #059669;
    }

    .btn-outline:hover {
        background: #059669;
        color: white;
    }

    /* Back Button Styles */
    .back-button {
        display: inline-flex;
        align-items: center;
        padding: 0.75rem 1.5rem;
        background: #6b7280;
        color: white;
        text-decoration: none;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s ease;
        margin-bottom: 1.5rem;
    }

    .back-button:hover {
        background: #4b5563;
        color: white;
        text-decoration: none;
    }

    .back-button i {
        margin-right: 0.5rem;
    }

</style>
@endpush

@section('content')
<!-- Content -->
<div class="max-w-4xl mx-auto">
    <!-- Back to Reports Button -->
    <a href="{{ route('admin.reports.index') }}" class="back-button">
        <i class="fas fa-arrow-left"></i>
        Back to Reports
    </a>

    <div class="form-card">
            <div class="form-card">
                <div class="form-card-header">
                    <h3 class="text-lg font-semibold flex items-center">
                        <i class="fas fa-filter mr-2"></i>
                        Report Filters & Options
                    </h3>
                </div>
                <div class="form-card-body">
                    <form action="{{ route('admin.reports.users.generate') }}" method="POST" id="reportForm">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Role Filter -->
                            <div class="form-group">
                                <label class="form-label">Role</label>
                                <select name="role" class="form-input" id="roleSelect">
                                    <option value="">All Roles</option>
                                    <!-- Options will be loaded via JavaScript -->
                                </select>
                            </div>

                            <!-- Department Filter -->
                            <div class="form-group">
                                <label class="form-label">Department</label>
                                <select name="department" class="form-input" id="departmentSelect">
                                    <option value="">All Departments</option>
                                    <!-- Options will be loaded via JavaScript -->
                                </select>
                            </div>

                            

                            <!-- Output Format -->
                            <div class="form-group">
                                <label class="form-label">Output Format</label>
                                <select name="format" class="form-input" required>
                                    <option value="preview">Preview (Web)</option>
                                    <option value="pdf">PDF Download</option>
                                    <option value="excel">Excel Download</option>
                                </select>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end space-x-4 mt-6 pt-6 border-t border-gray-200">
                            <button type="button" onclick="resetForm()" class="btn btn-secondary">
                                <i class="fas fa-undo mr-2"></i>
                                Reset
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-users mr-2"></i>
                                Generate Report
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function resetForm() {
    document.getElementById('reportForm').reset();
}

// Load filter options
document.addEventListener('DOMContentLoaded', function() {
    fetch('{{ route("admin.reports.filter-options") }}')
        .then(response => response.json())
        .then(data => {
            // Populate roles
            const roleSelect = document.getElementById('roleSelect');
            data.roles.forEach(role => {
                const option = document.createElement('option');
                option.value = role;
                option.textContent = role.charAt(0).toUpperCase() + role.slice(1);
                roleSelect.appendChild(option);
            });

            // Populate departments
            const departmentSelect = document.getElementById('departmentSelect');
            data.departments.forEach(dept => {
                const option = document.createElement('option');
                option.value = dept;
                option.textContent = dept;
                departmentSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error loading filter options:', error));
});
</script>
@endpush
@endsection
