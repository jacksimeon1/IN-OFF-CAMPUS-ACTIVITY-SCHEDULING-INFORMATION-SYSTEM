<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; line-height: 1.4; color: #333; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #059669; padding-bottom: 20px; }
        .logo-section { display: flex; align-items: center; justify-content: center; margin-bottom: 15px; }
        .logo { width: 80px; height: 80px; margin-right: 20px; }
        .university-info { text-align: left; }
        .university-name { color: #059669; font-size: 20px; font-weight: bold; margin: 0; line-height: 1.2; }
        .university-address { color: #666; font-size: 12px; margin: 2px 0; }
        .header h1 { color: #059669; font-size: 24px; margin: 15px 0 5px 0; font-weight: bold; }
        .header .subtitle { color: #047857; font-size: 16px; margin: 5px 0; font-weight: 600; }
        .report-meta { background: #f8f9fa; padding: 10px; border-radius: 5px; margin-top: 15px; font-size: 11px; }
        .summary { display: table; width: 100%; margin-bottom: 20px; }
        .summary-item { display: table-cell; text-align: center; padding: 15px; background-color: #f0fdf4; border: 1px solid #bbf7d0; width: 33%; }
        .summary-number { font-size: 18px; font-weight: bold; color: #059669; }
        .summary-label { font-size: 11px; color: #047857; margin-top: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; vertical-align: top; }
        th { background-color: #059669; color: white; font-weight: bold; font-size: 10px; }
        td { font-size: 9px; }
        .status-approved { background-color: #dcfce7; color: #166534; padding: 2px 6px; border-radius: 3px; font-size: 9px; }
        .status-pending { background-color: #fef3c7; color: #92400e; padding: 2px 6px; border-radius: 3px; font-size: 9px; }
        .status-rejected { background-color: #fecaca; color: #991b1b; padding: 2px 6px; border-radius: 3px; font-size: 9px; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #666; border-top: 1px solid #ddd; padding-top: 10px; }
        .no-data { text-align: center; padding: 40px; color: #666; font-style: italic; }
        .print-button { position: fixed; top: 20px; right: 20px; background: #059669; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 14px; z-index: 1000; }
        .print-button:hover { background: #047857; }
        @media print { .print-button { display: none; } }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()">
        <i class="fas fa-print"></i> Print / Save as PDF
    </button>

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
                    <strong>Approved By:</strong> {{ $generated_by }} ({{ $roleLabel }})<br>
                    <strong>Report Generated:</strong> {{ $generated_at->format('F d, Y \a\t g:i A') }}<br>
                    <strong>Academic Year:</strong> {{ date('Y') }}-{{ date('Y')+1 }}
                </div>
                <div style="text-align: right;">
                    <strong>Total Records:</strong> {{ $activities->count() }}<br>
                    <strong>Report Type:</strong> My Approvals Report<br>
                    <strong>Filters Applied:</strong> {{ $filterLabel }}
                </div>
            </div>
        </div>
    </div>

    <div class="summary">
        <div class="summary-item">
            <div class="summary-number">{{ $summary['total'] }}</div>
            <div class="summary-label"><i class="fas fa-list"></i> Total Processed</div>
        </div>
        <div class="summary-item">
            <div class="summary-number">{{ $summary['approved'] }}</div>
            <div class="summary-label"><i class="fas fa-check-circle"></i> Fully Approved</div>
        </div>
        <div class="summary-item">
            <div class="summary-number">{{ $summary['in_progress'] }}</div>
            <div class="summary-label"><i class="fas fa-hourglass-half"></i> Still In Progress</div>
        </div>
    </div>

    @if($activities->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 16%;">Activity Details</th>
                    <th style="width: 8%;">Date Submitted</th>
                    <th style="width: 10%;">Student Officers</th>
                    <th style="width: 10%;">Department</th>
                    <th style="width: 12%;">Leader/Organizers</th>
                    <th style="width: 14%;">Objectives/Purpose</th>
                    <th style="width: 12%;">Schedule</th>
                    <th style="width: 8%;">Status</th>
                    <th style="width: 10%;">Location</th>
                </tr>
            </thead>
            <tbody>
                @foreach($activities as $activity)
                    <tr>
                        <td>
                            <strong>{{ $activity->title }}</strong><br>
                            <small style="color: #666;">
                                <i class="fas fa-tag"></i> {{ $activity->type ? ucfirst(str_replace('_',' ', $activity->type)) : 'General Activity' }}
                            </small>
                        </td>
                        <td>{{ $activity->created_at ? $activity->created_at->format('M d, Y') : 'N/A' }}</td>
                        <td><strong>{{ $activity->user->name }}</strong></td>
                        <td><strong>{{ $activity->organization ?? ($activity->user->department ?? 'N/A') }}</strong></td>
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
                                @endif
                                @if($activity->start_time)
                                    <div style="color: #666; font-size: 10px;">
                                        {{ $activity->start_time->format('g:i A') }}
                                        @if($activity->end_time) - {{ $activity->end_time->format('g:i A') }} @endif
                                    </div>
                                @endif
                            @else
                                <span style="color: #999;">Not scheduled</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $isApproved = $activity->workflow_status === 'approved_by_vp';
                                $isRejected = ($activity->workflow_status === 'rejected') || ($activity->status === 'rejected');
                            @endphp
                            <span class="{{ $isApproved ? 'status-approved' : ($isRejected ? 'status-rejected' : 'status-pending') }}">
                                @if($isApproved)
                                    <i class="fas fa-check-circle"></i> Approved
                                @elseif($isRejected)
                                    <i class="fas fa-times-circle"></i> Rejected
                                @else
                                    <i class="fas fa-hourglass-half"></i> Pending
                                @endif
                            </span>
                        </td>
                        <td>{{ Str::limit($activity->location, 50) ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            <strong>No activities have been processed by you yet.</strong>
        </div>
    @endif

    <div class="footer">
        <div style="display: table; width: 100%; margin-bottom: 15px;">
            <div style="display: table-cell; width: 33%; text-align: left;">
                <strong>Report Information</strong><br>
                Document ID: RPT-{{ date('Ymd') }}-{{ str_pad($activities->count(), 4, '0', STR_PAD_LEFT) }}<br>
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
            <p style="margin: 5px 0;"><strong>Confidentiality Notice:</strong> This report contains confidential student information and is intended solely for authorized personnel.</p>
            <p style="margin: 5px 0; color: #059669; font-weight: bold;">© {{ date('Y') }} St. Paul University Philippines - All Rights Reserved</p>
        </div>
    </div>
</body>
</html>
