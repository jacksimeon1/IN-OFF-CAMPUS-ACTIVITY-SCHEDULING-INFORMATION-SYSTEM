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
        
        .section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        
        .section h2 {
            color: #059669;
            font-size: 16px;
            margin-bottom: 15px;
            border-bottom: 1px solid #059669;
            padding-bottom: 5px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        th {
            background-color: #059669;
            color: white;
            font-weight: bold;
            font-size: 11px;
        }
        
        td {
            font-size: 10px;
        }
        
        .chart-placeholder {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            padding: 20px;
            text-align: center;
            color: #666;
            margin-bottom: 20px;
        }
        
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        
        .stats-item {
            display: table-cell;
            text-align: center;
            padding: 15px;
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            width: 33.33%;
        }
        
        .stats-number {
            font-size: 18px;
            font-weight: bold;
            color: #059669;
        }
        
        .stats-label {
            font-size: 11px;
            color: #047857;
            margin-top: 5px;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
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
                    <strong>Analysis Period:</strong> {{ ucfirst($period) }} (from {{ $start_date->format('M d, Y') }})
                </div>
                <div style="text-align: right;">
                    <strong>Report Type:</strong> Statistics Report<br>
                    <strong>Data Points:</strong> {{ $statistics['activities_by_status']->sum('count') ?? 0 }} activities<br>
                    <strong>Generated By:</strong> {{ auth()->user()->name ?? 'System Administrator' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Activities by Status -->
    <div class="section">
        <h2>Activities by Status</h2>
        @if($statistics['activities_by_status']->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Status</th>
                        <th style="text-align: center;">Count</th>
                        <th style="text-align: center;">Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = $statistics['activities_by_status']->sum('count'); @endphp
                    @foreach($statistics['activities_by_status'] as $item)
                        <tr>
                            <td>
                                @if($item->workflow_status === 'approved_by_vp')
                                    Approved
                                @else
                                    {{ ucfirst(str_replace('_', ' ', $item->workflow_status)) }}
                                @endif
                            </td>
                            <td style="text-align: center;">{{ $item->count }}</td>
                            <td style="text-align: center;">{{ $total > 0 ? round(($item->count / $total) * 100, 1) : 0 }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No data available for this period.</p>
        @endif
    </div>

    <!-- Activities by Department -->
    <div class="section">
        <h2>Activities by Department</h2>
        @if($statistics['activities_by_department']->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Department</th>
                        <th style="text-align: center;">Count</th>
                        <th style="text-align: center;">Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = $statistics['activities_by_department']->sum('count'); @endphp
                    @foreach($statistics['activities_by_department'] as $item)
                        <tr>
                            <td>{{ $item->department ?? 'N/A' }}</td>
                            <td style="text-align: center;">{{ $item->count }}</td>
                            <td style="text-align: center;">{{ $total > 0 ? round(($item->count / $total) * 100, 1) : 0 }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No data available for this period.</p>
        @endif
    </div>

    <!-- Activities by Type -->
    <div class="section">
        <h2>Activities by Type</h2>
        @if($statistics['activities_by_type']->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Activity Type</th>
                        <th style="text-align: center;">Count</th>
                        <th style="text-align: center;">Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = $statistics['activities_by_type']->sum('count'); @endphp
                    @foreach($statistics['activities_by_type'] as $item)
                        <tr>
                            <td>{{ $item->type ?? 'N/A' }}</td>
                            <td style="text-align: center;">{{ $item->count }}</td>
                            <td style="text-align: center;">{{ $total > 0 ? round(($item->count / $total) * 100, 1) : 0 }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No data available for this period.</p>
        @endif
    </div>

    <!-- Monthly Trends -->
    <div class="section">
        <h2>Monthly Trends</h2>
        @if($statistics['monthly_trends']->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Year</th>
                        <th>Month</th>
                        <th style="text-align: center;">Activities</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($statistics['monthly_trends'] as $item)
                        <tr>
                            <td>{{ $item->year }}</td>
                            <td>{{ date('F', mktime(0, 0, 0, $item->month, 1)) }}</td>
                            <td style="text-align: center;">{{ $item->count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No data available for this period.</p>
        @endif
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>This report was generated automatically by the SPUP Activity Management System.</p>
        <p>For questions or concerns, please contact the system administrator.</p>
    </div>
</body>
</html>
