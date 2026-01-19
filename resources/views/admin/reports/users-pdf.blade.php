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
            width: 20%;
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
            font-size: 11px;
        }
        
        td {
            font-size: 10px;
        }
        
        .status-active {
            background-color: #dcfce7;
            color: #166534;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
        }
        
        .status-inactive {
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
                    <strong>Report Type:</strong> Users Report
                </div>
                <div style="text-align: right;">
                    <strong>Total Records:</strong> {{ $users->count() }}<br>
                    <strong>Report Type:</strong> Users Report<br>
                    <strong>Generated By:</strong> {{ auth()->user()->name ?? 'System Administrator' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Applied Filters -->
    @if(array_filter($filters))
        <div class="filters">
            <h3>Applied Filters:</h3>
            @if(!empty($filters['role']))
                <div class="filter-item"><strong>Role:</strong> {{ ucfirst($filters['role']) }}</div>
            @endif
            @if(!empty($filters['department']))
                <div class="filter-item"><strong>Department:</strong> {{ $filters['department'] }}</div>
            @endif
            @if(!empty($filters['status']))
                <div class="filter-item"><strong>Status:</strong> {{ ucfirst($filters['status']) }}</div>
            @endif
        </div>
    @endif

    <!-- Summary Statistics -->
    <div class="summary">
        <div class="summary-item">
            <div class="summary-number">{{ $summary['total_users'] }}</div>
            <div class="summary-label">Total Users</div>
        </div>
        <div class="summary-item">
            <div class="summary-number">{{ $summary['student_officers'] }}</div>
            <div class="summary-label">Student Officers</div>
        </div>
        <div class="summary-item">
            <div class="summary-number">{{ $summary['advisers'] }}</div>
            <div class="summary-label">Advisers</div>
        </div>
        <div class="summary-item">
            <div class="summary-number">{{ $summary['deans'] }}</div>
            <div class="summary-label">Deans</div>
        </div>
        <div class="summary-item">
            <div class="summary-number">{{ $summary['vp_acads'] }}</div>
            <div class="summary-label">VP Acads</div>
        </div>
        <div class="summary-item">
            <div class="summary-number">{{ $summary['director'] }}</div>
            <div class="summary-label">Director</div>
        </div>
        <div class="summary-item">
            <div class="summary-number">{{ $summary['psg_council_adviser'] }}</div>
            <div class="summary-label">PSG Council Adviser</div>
        </div>
    </div>

    <!-- Users Table -->
    @if($users->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 30%;">Name</th>
                    <th style="width: 35%;">Email</th>
                    <th style="width: 15%;">Role</th>
                    <th style="width: 20%;">Department</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role === 'student' ? 'Student Officer' : ucfirst($user->role) }}</td>
                        <td>{{ $user->department ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            No users found matching the selected criteria.
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>This report was generated automatically by the SPUP Activity Management System.</p>
        <p>For questions or concerns, please contact the system administrator.</p>
    </div>
</body>
</html>
