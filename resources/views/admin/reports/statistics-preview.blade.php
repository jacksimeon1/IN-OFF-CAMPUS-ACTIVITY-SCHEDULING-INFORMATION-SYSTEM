@extends('layouts.admin')

@section('title', 'Statistics Report Preview')

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
        padding: 2rem 1.5rem;
        border-bottom: 1px solid #334155;
        background: linear-gradient(135deg, #059669, #047857);
    }

    .admin-sidebar-title {
        color: white;
        font-size: 1.25rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .admin-sidebar-subtitle {
        color: #dcfce7;
        font-size: 0.75rem;
        margin-top: 0.25rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .admin-nav-section {
        padding: 1.5rem 0;
        border-bottom: 1px solid #334155;
    }

    .admin-nav-section:last-child {
        border-bottom: none;
    }

    .admin-nav-section-title {
        color: #64748b;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0 1.5rem 0.75rem;
        margin-bottom: 0.5rem;
    }

    .admin-nav-item {
        display: flex;
        align-items: center;
        padding: 0.875rem 1.5rem;
        color: #cbd5e1;
        text-decoration: none;
        transition: all 0.2s ease;
        border-left: 3px solid transparent;
        font-weight: 500;
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
        width: 20px;
        text-align: center;
        margin-right: 12px;
        font-size: 1rem;
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

    .stats-section {
        padding: 1.5rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .stats-section:last-child {
        border-bottom: none;
    }

    .stats-section h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 1rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .stat-item {
        background: linear-gradient(135deg, #f0fdf4, #dcfce7);
        border: 1px solid #bbf7d0;
        border-radius: 8px;
        padding: 1rem;
        text-align: center;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #059669;
    }

    .stat-label {
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

@section('page-title', 'Statistics Report Preview')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" style="padding-top: 1rem; padding-bottom: 2rem;">
    <!-- Enhanced Back to Reports Button - Above Report -->
    <div class="no-print mb-4">
        <a href="{{ route('admin.reports.index') }}" class="back-to-reports-btn">
            <i class="fas fa-arrow-left"></i>
            Back to Reports
        </a>
    </div>

    <!-- Report Content -->
    <div class="space-y-6">
        <div class="max-w-7xl mx-auto">
            <!-- Report Header -->
            <div class="report-header">
                <h1 class="text-3xl font-bold mb-2">{{ $title }}</h1>
                <p class="text-green-100">Statistical analysis and trends for {{ ucfirst($period) }}</p>
                <p class="text-green-100 text-sm mt-2">Period: From {{ $start_date->format('F d, Y') }}</p>
            </div>

            <div class="report-content">
                <!-- Activities by Status -->
                <div class="stats-section">
                    <h3>Activities by Status</h3>
                    @if($statistics['activities_by_status']->count() > 0)
                        <div class="stats-grid">
                            @php $total = $statistics['activities_by_status']->sum('count'); @endphp
                            @foreach($statistics['activities_by_status'] as $item)
                                <div class="stat-item">
                                    <div class="stat-value">{{ $item->count }}</div>
                                    <div class="stat-label">
                                        @if($item->workflow_status === 'approved_by_vp')
                                            Approved
                                        @else
                                            {{ ucfirst(str_replace('_', ' ', $item->workflow_status)) }}
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ $total > 0 ? round(($item->count / $total) * 100, 1) : 0 }}%
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 italic">No data available for this period.</p>
                    @endif
                </div>

                <!-- Activities by Department -->
                <div class="stats-section">
                    <h3>Activities by Department</h3>
                    @if($statistics['activities_by_department']->count() > 0)
                        <div class="stats-grid">
                            @php $total = $statistics['activities_by_department']->sum('count'); @endphp
                            @foreach($statistics['activities_by_department'] as $item)
                                <div class="stat-item">
                                    <div class="stat-value">{{ $item->count }}</div>
                                    <div class="stat-label">{{ $item->department ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ $total > 0 ? round(($item->count / $total) * 100, 1) : 0 }}%
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 italic">No data available for this period.</p>
                    @endif
                </div>

                <!-- Activities by Type -->
                <div class="stats-section">
                    <h3>Activities by Type</h3>
                    @if($statistics['activities_by_type']->count() > 0)
                        <div class="stats-grid">
                            @php $total = $statistics['activities_by_type']->sum('count'); @endphp
                            @foreach($statistics['activities_by_type'] as $item)
                                <div class="stat-item">
                                    <div class="stat-value">{{ $item->count }}</div>
                                    <div class="stat-label">{{ $item->type ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ $total > 0 ? round(($item->count / $total) * 100, 1) : 0 }}%
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 italic">No data available for this period.</p>
                    @endif
                </div>

                <!-- Monthly Trends -->
                <div class="stats-section">
                    <h3>Monthly Trends</h3>
                    @if($statistics['monthly_trends']->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Year</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Month</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activities</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($statistics['monthly_trends'] as $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->year }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ date('F', mktime(0, 0, 0, $item->month, 1)) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    {{ $item->count }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 italic">No data available for this period.</p>
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
    form.action = '{{ route("admin.reports.statistics.generate") }}';
    form.target = '_blank';
    
    // Add CSRF token
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = '{{ csrf_token() }}';
    form.appendChild(csrfInput);
    
    // Add current period
    const periodInput = document.createElement('input');
    periodInput.type = 'hidden';
    periodInput.name = 'period';
    periodInput.value = '{{ $period }}';
    form.appendChild(periodInput);
    
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
    form.action = '{{ route("admin.reports.statistics.generate") }}';
    
    // Add CSRF token
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = '{{ csrf_token() }}';
    form.appendChild(csrfInput);
    
    // Add current period
    const periodInput = document.createElement('input');
    periodInput.type = 'hidden';
    periodInput.name = 'period';
    periodInput.value = '{{ $period }}';
    form.appendChild(periodInput);
    
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
