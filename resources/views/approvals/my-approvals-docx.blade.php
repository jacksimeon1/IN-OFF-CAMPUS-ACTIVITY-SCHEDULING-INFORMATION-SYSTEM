<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 9pt; color: #333; margin: 0; padding: 0; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2pt solid #059669; padding-bottom: 10px; }
        .university-name { color: #059669; font-size: 15pt; font-weight: bold; margin: 0; }
        .university-address { color: #666; font-size: 9pt; margin: 2px 0; }
        h1 { color: #059669; font-size: 18pt; margin: 15px 0 5px 0; font-weight: bold; text-align: center; }
        .subtitle { color: #047857; font-size: 12pt; margin: 5px 0; font-weight: 600; text-align: center; }
        .report-meta { background-color: #f8f9fa; padding: 10px; font-size: 8.5pt; }
        .summary-cell { text-align: center; padding: 10px; background-color: #f0fdf4; border: 1pt solid #bbf7d0; }
        .summary-number { font-size: 13.5pt; font-weight: bold; color: #059669; }
        .summary-label { font-size: 8pt; color: #047857; }
        table.data-table { width: 100%; border-collapse: collapse; margin-top: 15px; table-layout: fixed; }
        table.data-table th, table.data-table td { border: 1pt solid #ddd; padding: 5px; text-align: left; vertical-align: top; font-size: 6.5pt; word-wrap: break-word; }
        table.data-table th { background-color: #059669; color: white; font-weight: bold; font-size: 7.5pt; }
        .footer { margin-top: 30px; border-top: 1pt solid #ddd; padding-top: 10px; font-size: 7.5pt; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <table width="100%" cellpadding="0" cellspacing="0" border="0" align="center">
            <tr>
                <td width="30%" align="right">
                    <img src="{{ asset('images/SPUP-final-logo.png') }}" alt="SPUP Logo" width="80" height="80">
                </td>
                <td width="70%" align="left" style="padding-left: 15px;">
                    <div class="university-name">ST. PAUL UNIVERSITY PHILIPPINES</div>
                    <div class="university-address">Tuguegarao City, Cagayan, Philippines</div>
                </td>
            </tr>
        </table>
        <h1>{{ $title }}</h1>
        <div class="subtitle">In/Off Campus Activity Scheduling Information System</div>
        <div class="report-meta">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td width="50%" align="left">
                        <strong>Approved By:</strong> {{ $generated_by }} ({{ $roleLabel }})<br>
                        <strong>Report Generated:</strong> {{ $generated_at->format('F d, Y \a\t g:i A') }}<br>
                        <strong>Academic Year:</strong> {{ date('Y') }}-{{ date('Y')+1 }}
                    </td>
                    <td width="50%" align="right">
                        <strong>Total Records:</strong> {{ $activities->count() }}<br>
                        <strong>Report Type:</strong> My Approvals Report<br>
                        <strong>Filters Applied:</strong> {{ $filterLabel }}
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <table width="100%" cellpadding="0" cellspacing="10" border="0" style="margin-bottom: 20px;">
        <tr>
            <td class="summary-cell" width="33%">
                <div class="summary-number">{{ $summary['total'] }}</div>
                <div class="summary-label">Total Processed</div>
            </td>
            <td class="summary-cell" width="33%">
                <div class="summary-number">{{ $summary['approved'] }}</div>
                <div class="summary-label">Fully Approved</div>
            </td>
            <td class="summary-cell" width="34%">
                <div class="summary-number">{{ $summary['in_progress'] }}</div>
                <div class="summary-label">Still In Progress</div>
            </td>
        </tr>
    </table>

    @if($activities->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th width="16%">Activity Details</th>
                    <th width="8%">Date Submitted</th>
                    <th width="10%">Student Officers</th>
                    <th width="10%">Department</th>
                    <th width="12%">Leader/Organizers</th>
                    <th width="14%">Objectives/Purpose</th>
                    <th width="12%">Schedule</th>
                    <th width="8%">Status</th>
                    <th width="10%">Location</th>
                </tr>
            </thead>
            <tbody>
                @foreach($activities as $activity)
                    <tr>
                        <td>
                            <strong>{{ $activity->title }}</strong><br>
                            <span style="color: #666; font-size: 6pt;">
                                {{ $activity->type ? ucfirst(str_replace('_',' ', $activity->type)) : 'General Activity' }}
                            </span>
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
                                    <br><span style="color: #666; font-size: 6pt;">{{ Str::limit($activity->objective_2, 80) }}</span>
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
                                    <br><span style="color: #666; font-size: 6pt;">
                                        {{ $activity->start_time->format('g:i A') }}
                                        @if($activity->end_time) - {{ $activity->end_time->format('g:i A') }} @endif
                                    </span>
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
                            <strong>
                                @if($isApproved)
                                    <span style="color: #166534;">Approved</span>
                                @elseif($isRejected)
                                    <span style="color: #991b1b;">Rejected</span>
                                @else
                                    <span style="color: #92400e;">Pending</span>
                                @endif
                            </strong>
                        </td>
                        <td>{{ Str::limit($activity->location, 50) ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div style="text-align: center; padding: 40px; color: #666; font-style: italic;">
            <strong>No activities have been processed by you yet.</strong>
        </div>
    @endif

    <div class="footer">
        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td width="33%" align="left">
                    <strong>Report Information</strong><br>
                    Document ID: RPT-{{ date('Ymd') }}-{{ str_pad($activities->count(), 4, '0', STR_PAD_LEFT) }}<br>
                    Format: DOCX Export
                </td>
                <td width="34%" align="center">
                    <strong>Contact Information</strong><br>
                    Office of Student Affairs<br>
                    St. Paul University Philippines
                </td>
                <td width="33%" align="right">
                    <strong>System Information</strong><br>
                    In/Off Campus Activity Scheduling Information System<br>
                    Generated: {{ $generated_at->format('M d, Y g:i A') }}
                </td>
            </tr>
        </table>
        <div style="border-top: 1pt solid #ddd; margin-top: 10px; padding-top: 10px; text-align: center;">
            <p style="margin: 5px 0;"><strong>Confidentiality Notice:</strong> This report contains confidential student information and is intended solely for authorized personnel.</p>
            <p style="margin: 5px 0; color: #059669; font-weight: bold;">© {{ date('Y') }} St. Paul University Philippines - All Rights Reserved</p>
        </div>
    </div>
</body>
</html>
