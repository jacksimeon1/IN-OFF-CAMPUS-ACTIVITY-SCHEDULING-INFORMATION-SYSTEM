<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #059669;
            padding-bottom: 20px;
            position: relative;
        }

        .logo-section {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }

        .logo {
            width: 80px;
            height: 80px;
            margin-right: 20px;
        }

        .university-info {
            text-align: left;
        }

        .university-name {
            color: #059669;
            font-size: 20px;
            font-weight: bold;
            margin: 0;
            line-height: 1.2;
        }

        .university-address {
            color: #666;
            font-size: 12px;
            margin: 2px 0;
        }

        .header h1 {
            color: #059669;
            font-size: 24px;
            margin: 15px 0 5px 0;
            font-weight: bold;
        }

        .header .subtitle {
            color: #047857;
            font-size: 16px;
            margin: 5px 0;
            font-weight: 600;
        }

        .header p {
            color: #666;
            margin: 3px 0;
            font-size: 12px;
        }

        .report-meta {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-top: 15px;
            font-size: 11px;
        }
        
        .filters {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .filters h3 {
            margin: 0 0 10px 0;
            color: #059669;
            font-size: 14px;
        }
        
        .filter-item {
            display: inline-block;
            margin-right: 20px;
            margin-bottom: 5px;
        }
        
        .summary {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        
        .summary-item {
            display: table-cell;
            text-align: center;
            padding: 15px;
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            width: 25%;
        }
        
        .summary-number {
            font-size: 18px;
            font-weight: bold;
            color: #059669;
        }
        
        .summary-label {
            font-size: 11px;
            color: #047857;
            margin-top: 5px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }
        
        th {
            background-color: #059669;
            color: white;
            font-weight: bold;
            font-size: 10px;
        }
        
        td {
            font-size: 9px;
        }
        
        .status-approved {
            background-color: #dcfce7;
            color: #166534;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
        }
        
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
        }
        
        .status-rejected {
            background-color: #fecaca;
            color: #991b1b;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #059669;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            z-index: 1000;
        }

        .print-button:hover {
            background: #047857;
        }

        @media print {
            .print-button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Print Button -->
    <button class="print-button" onclick="window.print()">
        <i class="fas fa-print"></i> Print / Save as PDF
    </button>

    <!-- Header -->
    <div class="header">
        <div class="logo-section">
            <img src="{{ asset('images/SPUP-final-logo.png') }}" alt="SPUP Logo" class="logo">
            <div class="university-info">
                <h2 class="university-name">ST. PAUL UNIVERSITY PHILIPPINES</h2>
                <p class="university-address">Tuguegarao City, Cagayan, Philippines</p>
            </div>
        </div>

        <h1>{{ $title }}</h1>
        <div class="subtitle">In/Off Campus Activity Scheduling Information System</div>

        <div class="report-meta">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <strong>Report Generated:</strong> {{ $generated_at->format('F d, Y \a\t g:i A') }}<br>
                    <strong>Academic Year:</strong> {{ date('Y') }}-{{ date('Y')+1 }}<br>
                    <strong>Report Period:</strong>
                    @if(isset($filters['date_from']) && isset($filters['date_to']) && $filters['date_from'] && $filters['date_to'])
                        {{ \Carbon\Carbon::parse($filters['date_from'])->format('M d, Y') }} to {{ \Carbon\Carbon::parse($filters['date_to'])->format('M d, Y') }}
                    @else
                        All Time
                    @endif
                </div>
                <div style="text-align: right;">
                    <strong>Total Records:</strong> {{ $activities->count() }}<br>
                    <strong>Report Type:</strong> Activities Report<br>
                    <strong>Generated By:</strong> {{ auth()->user()->name ?? 'System Administrator' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Applied Filters -->
    @if(array_filter($filters))
        <div class="filters">
            <h3>Applied Filters:</h3>
            @if(isset($filters['date_from']) && $filters['date_from'])
                <div class="filter-item"><strong>Date From:</strong> {{ \Carbon\Carbon::parse($filters['date_from'])->format('M d, Y') }}</div>
            @endif
            @if(isset($filters['date_to']) && $filters['date_to'])
                <div class="filter-item"><strong>Date To:</strong> {{ \Carbon\Carbon::parse($filters['date_to'])->format('M d, Y') }}</div>
            @endif
            @if(isset($filters['status']) && $filters['status'])
                <div class="filter-item"><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $filters['status'])) }}</div>
            @endif
            @if(isset($filters['department']) && $filters['department'])
                <div class="filter-item"><strong>Department:</strong> {{ $filters['department'] }}</div>
            @endif
            @if(isset($filters['type']) && $filters['type'])
                <div class="filter-item"><strong>Type:</strong> {{ $filters['type'] }}</div>
            @endif
        </div>
    @endif

    <!-- Summary Statistics -->
    <div class="summary">
        <div class="summary-item">
            <div class="summary-number">{{ $summary['total_activities'] }}</div>
            <div class="summary-label"><i class="fas fa-list"></i> Total Activities</div>
        </div>
        <div class="summary-item">
            <div class="summary-number">{{ $summary['pending'] }}</div>
            <div class="summary-label"><i class="fas fa-clock"></i> Pending Review</div>
        </div>
        <div class="summary-item">
            <div class="summary-number">{{ $summary['approved_by_vp'] ?? 0 }}</div>
            <div class="summary-label"><i class="fas fa-check-circle"></i> Approved</div>
        </div>
        <div class="summary-item">
            <div class="summary-number">{{ $summary['rejected'] }}</div>
            <div class="summary-label"><i class="fas fa-times-circle"></i> Rejected</div>
        </div>
    </div>

    <!-- Additional Statistics -->
    @if(isset($summary['additional_stats']))
    <div style="display: table; width: 100%; margin-bottom: 20px;">
        <div style="display: table-cell; width: 50%; padding-right: 10px;">
            <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; border-left: 4px solid #059669;">
                <h4 style="margin: 0 0 10px 0; color: #059669; font-size: 14px;">
                    <i class="fas fa-chart-bar"></i> Activity Breakdown
                </h4>
                <div style="font-size: 11px;">
                    <div style="margin-bottom: 5px;">
                        <strong>Most Active Department:</strong> {{ $summary['additional_stats']['top_department'] ?? 'N/A' }}
                    </div>
                    <div style="margin-bottom: 5px;">
                        <strong>Average Activities per Student:</strong> {{ $summary['additional_stats']['avg_per_student'] ?? '0' }}
                    </div>
                    <div style="margin-bottom: 5px;">
                        <strong>Total Participation Hours:</strong> {{ $summary['additional_stats']['total_hours'] ?? '0' }} hours
                    </div>
                </div>
            </div>
        </div>
        <div style="display: table-cell; width: 50%; padding-left: 10px;">
            <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; border-left: 4px solid #047857;">
                <h4 style="margin: 0 0 10px 0; color: #047857; font-size: 14px;">
                    <i class="fas fa-calendar-alt"></i> Time Analysis
                </h4>
                <div style="font-size: 11px;">
                    <div style="margin-bottom: 5px;">
                        <strong>This Month:</strong> {{ $summary['additional_stats']['this_month'] ?? '0' }} activities
                    </div>
                    <div style="margin-bottom: 5px;">
                        <strong>Last 30 Days:</strong> {{ $summary['additional_stats']['last_30_days'] ?? '0' }} activities
                    </div>
                    <div style="margin-bottom: 5px;">
                        <strong>Approval Rate:</strong> {{ $summary['additional_stats']['approval_rate'] ?? '0' }}%
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Activities Table -->
    @if($activities->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 4%;">ID</th>
                    <th style="width: 15%;">Activity Details</th>
                    <th style="width: 12%;">Student Officers</th>
                    <th style="width: 10%;">Department</th>
                    <th style="width: 12%;">Leader/Organizers</th>
                    <th style="width: 15%;">Objectives/Purpose</th>
                    <th style="width: 12%;">Schedule</th>
                    <th style="width: 10%;">Status</th>
                    <th style="width: 10%;">Location</th>
                </tr>
            </thead>
            <tbody>
                @foreach($activities as $activity)
                    <tr>
                        <td style="text-align: center; font-weight: bold;">{{ $activity->id }}</td>
                        <td>
                            <strong>{{ $activity->title }}</strong><br>
                            <small style="color: #666;">
                                <i class="fas fa-tag"></i> {{ $activity->type ? ucfirst(str_replace('_',' ', $activity->type)) : 'General Activity' }}
                            </small>
                        </td>
                        <td>
                            <strong>{{ $activity->user->name }}</strong>
                        </td>
                        <td>
                            <strong>{{ $activity->organization ?? ($activity->user->department ?? 'N/A') }}</strong>
                        </td>
                        <td>
                            @if($activity->leaders)
                                {{ Str::limit($activity->leaders, 80) }}
                            @else
                                <span style="color: #999;">Not specified</span>
                            @endif
                        </td>
                        <td>
                            @if($activity->objective_1)
                                {{ Str::limit($activity->objective_1, 100) }}
                                @if($activity->objective_2)
                                    <br><small style="color: #666;">{{ Str::limit($activity->objective_2, 80) }}</small>
                                @endif
                            @else
                                <span style="color: #999;">Not specified</span>
                            @endif
                        </td>
                        <td>
                            @if($activity->activity_date)
                                @php
                                    $start = $activity->activity_date;
                                    $end = $activity->end_date ?? $activity->activity_date;
                                @endphp
                                @if($start->format('Y-m-d') === $end->format('Y-m-d'))
                                    {{ $start->format('M d, Y') }}
                                @else
                                    {{ $start->format('M d') }} - {{ $end->format('M d, Y') }}
                                    <div style="color: #666; font-size: 10px;">{{ $start->diffInDays($end) + 1 }} days</div>
                                @endif
                                @if($activity->start_time)
                                    <div style="color: #666; font-size: 10px;">
                                        {{ $activity->start_time->format('g:i A') }}
                                        @if($activity->end_time)
                                            - {{ $activity->end_time->format('g:i A') }}
                                        @endif
                                    </div>
                                @endif
                            @else
                                <span style="color: #999;">Not scheduled</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $isApproved = $activity->workflow_status === 'approved_by_vp';
                                $isRejected = ($activity->workflow_status === 'rejected') || (isset($activity->status) && $activity->status === 'rejected');
                                $isDraft = $activity->workflow_status === 'draft';
                                $statusLabel = $isApproved ? 'Approved' : ($isRejected ? 'Rejected' : ($isDraft ? 'Draft' : 'Pending'));
                                $statusClass = $isApproved ? 'status-approved' : ($isRejected ? 'status-rejected' : ($isDraft ? 'status-pending' : 'status-pending'));
                            @endphp
                            <span class="{{ $statusClass }}">
                                @if($isApproved)
                                    <i class="fas fa-check-circle"></i> Approved
                                @elseif($isRejected)
                                    <i class="fas fa-times-circle"></i> Rejected
                                @elseif($isDraft)
                                    <i class="fas fa-file"></i> Draft
                                @else
                                    <i class="fas fa-hourglass-half"></i> Pending
                                @endif
                            </span>
                        </td>
                        <td>
                            @if($activity->location)
                                {{ Str::limit($activity->location, 50) }}
                            @else
                                <span style="color: #999;">Not specified</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            <strong>No activities found matching the selected criteria.</strong><br><br>
            @if(isset($filters['department']) && $filters['department'])
                No activities have been submitted by users from <strong>{{ $filters['department'] }}</strong>.<br>
                This report accurately shows 0 activities for this department.
            @elseif(isset($filters['status']) && $filters['status'])
                No activities found with status: <strong>{{ ucfirst(str_replace('_', ' ', $filters['status'])) }}</strong>.
            @else
                Try adjusting your filter criteria to see results.
            @endif
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <div style="display: table; width: 100%; margin-bottom: 15px;">
            <div style="display: table-cell; width: 33%; text-align: left;">
                <strong>Report Information</strong><br>
                Document ID: RPT-{{ date('Ymd') }}-{{ str_pad($activities->count(), 4, '0', STR_PAD_LEFT) }}<br>
                Version: 1.0<br>
                Format: PDF Export
            </div>
            <div style="display: table-cell; width: 34%; text-align: center;">
                <strong>Contact Information</strong><br>
                Office of Student Affairs<br>
                St. Paul University Philippines
            </div>
            <div style="display: table-cell; width: 33%; text-align: right;">
                <strong>System Information</strong><br>
                In/Off Campus Activity Scheduling Information System<br>
                Generated: {{ $generated_at->format('M d, Y g:i A') }}<br>
                Page 1 of 1
            </div>
        </div>

        <div style="border-top: 1px solid #ddd; padding-top: 10px; text-align: center;">
            <p style="margin: 5px 0;">
                <strong>Confidentiality Notice:</strong> This report contains confidential student information and is intended solely for authorized personnel.
            </p>
            <p style="margin: 5px 0;">
                This report was generated automatically by the In/Off Campus Activity Scheduling Information System.
                For questions or concerns, please contact the system administrator or Office of Student Affairs.
            </p>
            <p style="margin: 5px 0; color: #059669; font-weight: bold;">
                © {{ date('Y') }} St. Paul University Philippines - All Rights Reserved
            </p>
        </div>
    </div>
</body>
</html>
