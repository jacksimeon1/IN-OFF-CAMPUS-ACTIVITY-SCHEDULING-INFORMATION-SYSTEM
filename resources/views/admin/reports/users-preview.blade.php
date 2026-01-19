@extends('layouts.admin')

@section('title', 'Users Report Preview')

@push('styles')
<style>
    /* Admin Sidebar Navigation */
    .admin-sidebar {
        position: fixed;
        left: 0;
        top: 0;
        width: 280px;
        height: 100vh;
        background: #065f46;
        z-index: 1000;
        transform: translateX(-100%);
        transition: transform 0.3s ease;
        box-shadow: 2px 0 20px rgba(0, 0, 0, 0.3);
        overflow-y: auto;
    }

    .admin-sidebar.open {
        transform: translateX(0);
    }

    .admin-sidebar-header {
        padding: 1.5rem 1rem;
        border-bottom: 1px solid #334155;
        background: linear-gradient(135deg, #059669, #047857);
    }

    .admin-sidebar-title {
        color: white;
        font-size: 1rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .admin-sidebar-subtitle {
        color: #dcfce7;
        font-size: 0.65rem;
        margin-top: 0.25rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .admin-nav-section {
        padding: 1rem 0;
        border-bottom: 1px solid #334155;
    }

    .admin-nav-section:last-child {
        border-bottom: none;
    }

    .admin-nav-section-title {
        color: #64748b;
        font-size: 0.65rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0 1rem 0.5rem;
        margin-bottom: 0.25rem;
    }

    .admin-nav-item {
        display: flex;
        align-items: center;
        padding: 0.75rem 1rem;
        color: #cbd5e1;
        text-decoration: none;
        transition: all 0.2s ease;
        border-left: 3px solid transparent;
        font-weight: 500;
        font-size: 0.875rem;
    }

    .admin-nav-item:hover {
        background: #eab308;
        color: white;
        transform: translateX(4px);
    }

    .admin-nav-item.active {
        background: #eab308;
        color: white;
        font-weight: 600;
    }

    .admin-nav-item i {
        width: 16px;
        text-align: center;
        margin-right: 8px;
        font-size: 0.875rem;
    }

    /* Report Styles */
    .report-header {
        background: linear-gradient(135deg, #059669, #047857);
        color: white;
        padding: 2rem;
        border-radius: 12px 12px 0 0;
    }

    .report-content {
        background: white;
        border-radius: 0 0 12px 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .summary-card {
        background: linear-gradient(135deg, #f0fdf4, #dcfce7);
        border: 1px solid #bbf7d0;
        border-radius: 8px;
        padding: 1rem;
        text-align: center;
    }

    .summary-number {
        font-size: 1.5rem;
        font-weight: 700;
        color: #059669;
    }

    .summary-label {
        font-size: 0.875rem;
        color: #047857;
        margin-top: 0.25rem;
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

    .btn-pdf {
        background: #dc2626;
        color: white;
    }

    .btn-pdf:hover {
        background: #b91c1c;
    }

    .btn-excel {
        background: #16a34a;
        color: white;
    }

    .btn-excel:hover {
        background: #15803d;
    }

    /* Enhanced Back Button */
    .back-to-reports-btn {
        position: relative;
        display: inline-flex;
        align-items: center;
        padding: 0.875rem 1.5rem;
        background: linear-gradient(135deg, #059669, #047857);
        color: white;
        text-decoration: none;
        border-radius: 50px;
        font-size: 0.875rem;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
        border: 2px solid transparent;
        overflow: hidden;
    }

    .back-to-reports-btn:hover {
        background: linear-gradient(135deg, #047857, #065f46);
        color: white;
        text-decoration: none;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(5, 150, 105, 0.4);
        border-color: rgba(255, 255, 255, 0.2);
    }

    .back-to-reports-btn:active {
        transform: translateY(0);
        box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
    }

    .back-to-reports-btn i {
        margin-right: 0.5rem;
        transition: transform 0.3s ease;
    }

    .back-to-reports-btn:hover i {
        transform: translateX(-2px);
    }

    .back-to-reports-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
        border-radius: 50px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .back-to-reports-btn:hover::before {
        opacity: 1;
    }

    @media print {
        .admin-sidebar,
        .no-print {
            display: none !important;
        }
        
        .report-content {
            box-shadow: none;
            border-radius: 0;
        }
    }
</style>
@endpush

@section('page-title', 'Users Report Preview')

@section('content')
<div class="max-w-7xl mx-auto">
</div>

<!-- Main Content -->
<div class="min-h-screen bg-gray-50">
    <!-- Report Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" style="padding-top: 1rem; padding-bottom: 2rem;">
        <!-- Enhanced Back to Reports Button - Above Report -->
        <div class="no-print mb-4">
            <a href="{{ route('admin.reports.index') }}" class="back-to-reports-btn">
                <i class="fas fa-arrow-left"></i>
                Back to Reports
            </a>
        </div>

        <div class="max-w-7xl mx-auto">
            <!-- Report Header -->
            <div class="report-header">
                <h1 class="text-3xl font-bold mb-2">{{ $title }}</h1>
                <p class="text-green-100">Comprehensive report on system users</p>
                
                <!-- Applied Filters -->
                @if(array_filter($filters))
                    <div class="mt-4 p-4 bg-white bg-opacity-10 rounded-lg">
                        <h3 class="font-semibold mb-2">Applied Filters:</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-2 text-sm">
                            @if(!empty($filters['role']))
                                <div>Role: {{ ucfirst($filters['role']) }}</div>
                            @endif
                            @if(!empty($filters['department']))
                                <div>Department: {{ $filters['department'] }}</div>
                            @endif
                            @if(!empty($filters['status']))
                                <div>Status: {{ ucfirst($filters['status']) }}</div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <div class="report-content">
                <!-- Summary Statistics -->
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Summary</h2>
                    <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
                        <div class="summary-card">
                            <div class="summary-number">{{ $summary['total_users'] }}</div>
                            <div class="summary-label">Total Users</div>
                        </div>
                        <div class="summary-card">
                            <div class="summary-number">{{ $summary['student_officers'] }}</div>
                            <div class="summary-label">Student Officers</div>
                        </div>
                        <div class="summary-card">
                            <div class="summary-number">{{ $summary['advisers'] }}</div>
                            <div class="summary-label">Advisers</div>
                        </div>
                        <div class="summary-card">
                            <div class="summary-number">{{ $summary['deans'] }}</div>
                            <div class="summary-label">Deans</div>
                        </div>
                        <div class="summary-card">
                            <div class="summary-number">{{ $summary['vp_acads'] }}</div>
                            <div class="summary-label">VP Acads</div>
                        </div>
                        <div class="summary-card">
                            <div class="summary-number">{{ $summary['director'] }}</div>
                            <div class="summary-label">Director</div>
                        </div>
                        <div class="summary-card">
                            <div class="summary-number">{{ $summary['psg_council_adviser'] }}</div>
                            <div class="summary-label">PSG Council Adviser</div>
                        </div>
                    </div>
                </div>

                <!-- Users Table -->
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Users Details</h2>
                    
                    @if($users->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($users as $user)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $user->role === 'student' ? 'Student Officer' : ucfirst($user->role) }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $user->department ?? 'N/A' }}</div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-users text-3xl mb-2"></i>
                            <p>No users found matching the selected criteria.</p>
                        </div>
                    @endif
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

function exportToPDF() {
    // Create a form to submit with current filters
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("admin.reports.users.generate") }}';
    form.target = '_blank';
    
    // Add CSRF token
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = '{{ csrf_token() }}';
    form.appendChild(csrfInput);
    
    // Add current filters
    const filters = @json($filters);
    for (const [key, value] of Object.entries(filters)) {
        if (value) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = value;
            form.appendChild(input);
        }
    }
    
    // Set format to PDF
    const formatInput = document.createElement('input');
    formatInput.type = 'hidden';
    formatInput.name = 'format';
    formatInput.value = 'pdf';
    form.appendChild(formatInput);
    
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}

function exportToExcel() {
    // Create a form to submit with current filters
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("admin.reports.users.generate") }}';
    
    // Add CSRF token
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = '{{ csrf_token() }}';
    form.appendChild(csrfInput);
    
    // Add current filters
    const filters = @json($filters);
    for (const [key, value] of Object.entries(filters)) {
        if (value) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = value;
            form.appendChild(input);
        }
    }
    
    // Set format to Excel
    const formatInput = document.createElement('input');
    formatInput.type = 'hidden';
    formatInput.name = 'format';
    formatInput.value = 'excel';
    form.appendChild(formatInput);
    
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}

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
