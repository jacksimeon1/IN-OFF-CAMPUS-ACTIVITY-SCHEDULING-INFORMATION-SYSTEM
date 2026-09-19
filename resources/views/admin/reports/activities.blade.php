@extends('layouts.admin')

@section('title', 'Activities Report')

@push('styles')
<style>
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
</style>
@endpush

@section('page-title', 'Activities Report')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="p-6">
        <div class="max-w-4xl mx-auto">
            <!-- Back to Reports Button -->
            <a href="{{ route('admin.reports.index') }}" class="back-button">
                <i class="fas fa-arrow-left"></i>
                Back to Reports
            </a>

            <div class="form-card">
                <div class="form-card-header">
                    <h3 class="text-lg font-semibold flex items-center">
                        <i class="fas fa-filter mr-2"></i>
                        Report Filters & Options
                    </h3>
                </div>
                <div class="form-card-body">
                    <form action="{{ route('admin.reports.activities.generate') }}" method="POST" id="reportForm">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Date Range -->
                            <div class="form-group">
                                <label class="form-label">Date From</label>
                                <input type="date" name="date_from" class="form-input" value="{{ old('date_from') }}">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Date To</label>
                                <input type="date" name="date_to" class="form-input" value="{{ old('date_to') }}">
                            </div>

                            <!-- Status Filter -->
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-input">
                                    <option value="">All Statuses</option>
                                    <optgroup label="Pending Activities">
                                        <option value="draft">Draft</option>
                                        <option value="noted_by_adviser">Noted by Adviser</option>
                                        <option value="noted_by_dean">Noted by Dean</option>
                                        <option value="reviewed_by_psg">Reviewed by PSG</option>
                                        <option value="endorsed_by_director">Endorsed by Director</option>
                                    </optgroup>
                                    <optgroup label="Final Status">
                                        <option value="approved_by_vp">Approved</option>
                                        <option value="rejected">Rejected</option>
                                    </optgroup>
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

                            <!-- Activity Type -->
                            <div class="form-group">
                                <label class="form-label">Activity Type</label>
                                <select name="type" class="form-input" id="typeSelect">
                                    <option value="">All Types</option>
                                    <!-- Options will be loaded via JavaScript -->
                                </select>
                            </div>

                            <!-- Output Format -->
                            <div class="form-group">
                                <label class="form-label">Output Format</label>
                                <select name="format" class="form-input" required>
                                    <option value="preview">Preview (Web)</option>
                                    <option value="pdf">PDF Download</option>
                                    <option value="docx">Word/DOCX Download</option>
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
                                <i class="fas fa-chart-line mr-2"></i>
                                Generate Report
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('adminSidebar');
    sidebar.classList.toggle('open');
}

function resetForm() {
    document.getElementById('reportForm').reset();
}

// Load filter options
document.addEventListener('DOMContentLoaded', function() {
    fetch('{{ route("admin.reports.filter-options") }}')
        .then(response => response.json())
        .then(data => {
            // Populate departments
            const departmentSelect = document.getElementById('departmentSelect');
            data.departments.forEach(dept => {
                const option = document.createElement('option');
                option.value = dept;
                option.textContent = dept;
                departmentSelect.appendChild(option);
            });

            // Populate activity types
            const typeSelect = document.getElementById('typeSelect');
            data.activity_types.forEach(type => {
                if (type) {
                    const option = document.createElement('option');
                    if (typeof type === 'object') {
                        option.value = type.id;
                        option.textContent = type.name;
                    } else {
                        option.value = type;
                        option.textContent = type;
                    }
                    typeSelect.appendChild(option);
                }
            });
        })
        .catch(error => console.error('Error loading filter options:', error));
});

// Close sidebar when clicking outside
document.addEventListener('click', function(event) {
    const sidebar = document.getElementById('adminSidebar');
    const toggleButton = event.target.closest('button');
    
    if (!sidebar.contains(event.target) && !toggleButton) {
        sidebar.classList.remove('open');
    }
});
</script>
@endsection
