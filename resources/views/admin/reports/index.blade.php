@extends('layouts.admin')

@section('title', 'Generate Reports')

@push('styles')
<style>


    /* Report Cards */
    .report-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
    }

    .report-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1);
    }

    .report-card-header {
        background: linear-gradient(135deg, #059669, #047857);
        color: white;
        padding: 1.5rem;
        border-radius: 12px 12px 0 0;
    }

    .report-card-body {
        padding: 1.5rem;
    }


</style>
@endpush

@section('content')


<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Report Generation Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Activities Report -->
            <div class="report-card">
                <div class="report-card-header">
                    <h3 class="text-lg font-semibold flex items-center">
                        <i class="fas fa-list-alt mr-2"></i>
                        Activities Report
                    </h3>
                </div>
                <div class="report-card-body">
                    <p class="text-gray-600 mb-4">Generate comprehensive reports on student activities with filtering options.</p>
                    <div class="space-y-2 text-sm text-gray-500 mb-4">
                        <div>• Filter by date range, status, department</div>
                        <div>• Export to PDF or Excel</div>
                        <div>• Includes approval workflow status</div>
                    </div>
                    <a href="{{ route('admin.reports.activities.view') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-chart-line mr-2"></i>
                        Generate Report
                    </a>
                </div>
            </div>

            <!-- Users Report -->
            <div class="report-card">
                <div class="report-card-header">
                    <h3 class="text-lg font-semibold flex items-center">
                        <i class="fas fa-users mr-2"></i>
                        Users Report
                    </h3>
                </div>
                <div class="report-card-body">
                    <p class="text-gray-600 mb-4">Generate detailed reports on system users and their activity statistics.</p>
                    <div class="space-y-2 text-sm text-gray-500 mb-4">
                        <div>• Filter by role, department, status</div>
                        <div>• User activity summaries</div>
                        <div>• Registration and login data</div>
                    </div>
                    <a href="{{ route('admin.reports.users.view') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-user-chart mr-2"></i>
                        Generate Report
                    </a>
                </div>
            </div>

            <!-- Statistics Report -->
            <div class="report-card">
                <div class="report-card-header">
                    <h3 class="text-lg font-semibold flex items-center">
                        <i class="fas fa-chart-bar mr-2"></i>
                        Statistics Report
                    </h3>
                </div>
                <div class="report-card-body">
                    <p class="text-gray-600 mb-4">Generate statistical analysis and trends for system usage and activities.</p>
                    <div class="space-y-2 text-sm text-gray-500 mb-4">
                        <div>• Activity trends and patterns</div>
                        <div>• Department-wise statistics</div>
                        <div>• Monthly/quarterly analysis</div>
                    </div>
                    <a href="{{ route('admin.reports.statistics.view') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-analytics mr-2"></i>
                        Generate Report
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
