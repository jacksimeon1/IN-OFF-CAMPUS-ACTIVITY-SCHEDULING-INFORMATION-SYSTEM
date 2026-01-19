<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Response;

class AdminReportsController extends Controller
{
    /**
     * Display the reports dashboard
     */
    public function index()
    {
        // Get quick statistics for the reports dashboard
        $stats = [
            'total_activities' => Activity::count(),
            'total_users' => User::count(),
            'pending_activities' => Activity::whereIn('workflow_status', ['draft', 'noted_by_adviser', 'noted_by_dean', 'reviewed_by_psg', 'endorsed_by_director'])->count(),
            'approved_activities' => Activity::where('workflow_status', 'approved_by_vp')->count(),
            // Count rejected across both workflow_status (current) and status (legacy)
            'rejected_activities' => Activity::where('workflow_status', 'rejected')
                ->orWhere('status', 'rejected')
                ->count(),
            'this_month_activities' => Activity::whereMonth('created_at', now()->month)->count(),
            'departments' => User::whereNotNull('department')->distinct('department')->count('department'),
        ];

        // Get recent report generation activity (if we track this)
        $recentReports = collect(); // Placeholder for future report history tracking

        return view('admin.reports.index', compact('stats', 'recentReports'));
    }

    /**
     * Generate activities report
     */
    public function activitiesReport(Request $request)
    {
        $request->validate([
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'status' => 'nullable|string',
            'department' => 'nullable|string',
            'type' => 'nullable|string',
            'format' => 'required|in:preview,pdf,excel'
        ]);

        // Build query based on filters with comprehensive relationships
        $query = Activity::with([
            'user',
            'adviser',
            'deanNoted',
            'psgReviewed',
            'directorEndorsed',
            'vpApproved',
            'rejectedBy'
        ]);

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('status')) {
            if ($request->status === 'rejected') {
                // Support legacy records tagged via status='rejected'
                $query->where(function($q) use ($request) {
                    $q->where('workflow_status', 'rejected')
                      ->orWhere('status', 'rejected');
                });
            } else {
                $query->where('workflow_status', $request->status);
            }
        }

        if ($request->filled('department')) {
            $query->where('organization', $request->department);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $activities = $query->orderBy('created_at', 'desc')->get();

        // Generate comprehensive report data
        $reportData = [
            'title' => 'Activities Report',
            'subtitle' => 'Saint Paul University Philippines - Activity Management System',
            'generated_at' => now(),
            'generated_by' => auth()->user()->name,
            'report_period' => $this->getReportPeriod($request),
            'filters' => $request->only(['date_from', 'date_to', 'status', 'department', 'type']),
            'activities' => $activities,
            'summary' => [
                'total_activities' => $activities->count(),
                'pending' => $activities->whereIn('workflow_status', ['draft', 'noted_by_adviser', 'noted_by_dean', 'reviewed_by_psg', 'endorsed_by_director'])->count(),
                'noted_by_adviser' => $activities->where('workflow_status', 'noted_by_adviser')->count(),
                'noted_by_dean' => $activities->where('workflow_status', 'noted_by_dean')->count(),
                'reviewed_by_psg' => $activities->where('workflow_status', 'reviewed_by_psg')->count(),
                'endorsed_by_director' => $activities->where('workflow_status', 'endorsed_by_director')->count(),
                'approved_by_vp' => $activities->where('workflow_status', 'approved_by_vp')->count(),
                // Count rejected across both columns to support legacy records
                'rejected' => $activities->filter(function($a){
                    return ($a->workflow_status === 'rejected') || ($a->status === 'rejected');
                })->count(),
                'departments_involved' => $activities->pluck('organization')->filter()->unique()->count(),
                'average_processing_time' => $this->calculateAverageProcessingTime($activities),
                'most_active_department' => $this->getMostActiveDepartment($activities),
                'activity_types_breakdown' => $this->getActivityTypesBreakdown($activities),
                'additional_stats' => [
                    'top_department' => $activities->groupBy('organization')->sortByDesc(function($group) { return $group->count(); })->keys()->first() ?? 'N/A',
                    'avg_per_student' => $activities->count() > 0 ? round($activities->groupBy('user_id')->count() / $activities->pluck('user_id')->unique()->count(), 1) : 0,
                    'total_hours' => $activities->sum('duration_hours') ?? 0,
                    'this_month' => $activities->where('created_at', '>=', now()->startOfMonth())->count(),
                    'last_30_days' => $activities->where('created_at', '>=', now()->subDays(30))->count(),
                    'approval_rate' => $activities->count() > 0 ? round(($activities->where('workflow_status', 'approved_by_vp')->count() / $activities->count()) * 100, 1) : 0,
                ]
            ],
            'detailed_statistics' => [
                'by_month' => $this->getActivitiesByMonth($activities),
                'by_department' => $this->getActivitiesByDepartment($activities),
                'by_status' => $this->getActivitiesByStatus($activities),
                'approval_timeline' => $this->getApprovalTimeline($activities),
            ]
        ];

        // Handle different output formats
        switch ($request->format) {
            case 'pdf':
                // Return HTML view optimized for PDF printing
                $html = view('admin.reports.activities-pdf', $reportData)->render();
                return Response::make($html, 200, [
                    'Content-Type' => 'text/html',
                    'Content-Disposition' => 'inline; filename="activities-report-' . now()->format('Y-m-d') . '.html"'
                ]);

            case 'excel':
                // Generate CSV format for Excel compatibility
                return $this->generateActivitiesCSV($reportData);

            default: // preview
                return view('admin.reports.activities-preview', $reportData);
        }
    }

    /**
     * Generate users report
     */
    public function usersReport(Request $request)
    {
        $request->validate([
            'role' => 'nullable|string',
            'department' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
            'format' => 'required|in:preview,pdf,excel'
        ]);

        // Build query based on filters
        $query = User::with(['activities']);

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->whereNotNull('email_verified_at');
            } else {
                $query->whereNull('email_verified_at');
            }
        }

        $users = $query->orderBy('created_at', 'desc')->get();

        // Generate report data
        $reportData = [
            'title' => 'Users Report',
            'generated_at' => now(),
            'filters' => $request->only(['role', 'department', 'status']),
            'users' => $users,
            'summary' => [
                'total_users' => $users->count(),
                'student_officers' => $users->where('role', 'student')->count(),
                'advisers' => $users->where('role', 'adviser')->count(),
                'deans' => $users->where('role', 'dean')->count(),
                'vp_acads' => $users->where('role', 'vp')->count(),
                'director' => $users->where('role', 'director')->count(),
                'psg_council_adviser' => $users->where('role', 'psg_adviser')->count(),
            ]
        ];

        // Handle different output formats
        switch ($request->format) {
            case 'pdf':
                // Return HTML view optimized for PDF printing
                $html = view('admin.reports.users-pdf', $reportData)->render();
                return Response::make($html, 200, [
                    'Content-Type' => 'text/html',
                    'Content-Disposition' => 'inline; filename="users-report-' . now()->format('Y-m-d') . '.html"'
                ]);

            case 'excel':
                // Generate CSV format for Excel compatibility
                return $this->generateUsersCSV($reportData);

            default: // preview
                return view('admin.reports.users-preview', $reportData);
        }
    }

    /**
     * Generate statistics report
     */
    public function statisticsReport(Request $request)
    {
        $request->validate([
            'period' => 'required|in:week,month,quarter,year',
            'format' => 'required|in:preview,pdf,excel'
        ]);

        $period = $request->period;
        $startDate = $this->getStartDateForPeriod($period);

        // Generate comprehensive statistics
        $stats = [
            'activities_by_status' => Activity::select('workflow_status', DB::raw('count(*) as count'))
                ->where('created_at', '>=', $startDate)
                ->groupBy('workflow_status')
                ->get(),
            
            'activities_by_department' => Activity::select('organization as department', DB::raw('count(*) as count'))
                ->where('created_at', '>=', $startDate)
                ->whereNotNull('organization')
                ->where('organization', '!=', '')
                ->groupBy('organization')
                ->orderBy('count', 'desc')
                ->get()
                ->map(function ($item) {
                    return (object)[
                        'department' => $item->department,
                        'count' => $item->count
                    ];
                }),
            
            'activities_by_type' => Activity::select('type', DB::raw('count(*) as count'))
                ->where('created_at', '>=', $startDate)
                ->groupBy('type')
                ->get(),
            
            'monthly_trends' => Activity::select(
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('count(*) as count')
                )
                ->where('created_at', '>=', $startDate)
                ->groupBy('year', 'month')
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->get(),
        ];

        $reportData = [
            'title' => 'Statistics Report - ' . ucfirst($period),
            'generated_at' => now(),
            'period' => $period,
            'start_date' => $startDate,
            'statistics' => $stats,
        ];

        // Handle different output formats
        switch ($request->format) {
            case 'pdf':
                // Return HTML view optimized for PDF printing
                $html = view('admin.reports.statistics-pdf', $reportData)->render();
                return Response::make($html, 200, [
                    'Content-Type' => 'text/html',
                    'Content-Disposition' => 'inline; filename="statistics-report-' . now()->format('Y-m-d') . '.html"'
                ]);

            case 'excel':
                // Generate CSV format for Excel compatibility
                return $this->generateStatisticsCSV($reportData);

            default: // preview
                return view('admin.reports.statistics-preview', $reportData);
        }
    }

    /**
     * Get filter options for reports
     */
    public function getFilterOptions()
    {
        // Get only the 5 real school departments that start with "SCHOOL OF"
        $schoolDepartments = User::whereNotNull('department')
            ->where('department', 'LIKE', 'SCHOOL OF%')
            ->distinct()
            ->pluck('department')
            ->sort()
            ->values();

        return response()->json([
            'departments' => $schoolDepartments,
            'activity_types' => Activity::distinct()->pluck('type')->filter()->sort()->values(),
            'roles' => User::distinct()->pluck('role')->filter()->sort()->values(),
            'workflow_statuses' => [
                'draft' => 'Draft',
                'noted_by_adviser' => 'Noted by Adviser',
                'noted_by_dean' => 'Noted by Dean',
                'reviewed_by_psg' => 'Reviewed by PSG',
                'endorsed_by_director' => 'Endorsed by Director',
                'approved_by_vp' => 'Approved by VP',
                'rejected' => 'Rejected'
            ]
        ]);
    }

    /**
     * Generate CSV for activities report
     */
    private function generateActivitiesCSV($reportData)
    {
        $filename = 'activities-report-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($reportData) {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($file, [
                'ID',
                'Title',
                'Type',
                'Student Name',
                'Department',
                'Status',
                'Activity Date',
                'Created Date'
            ]);

            // Add data rows
            foreach ($reportData['activities'] as $activity) {
                fputcsv($file, [
                    $activity->id,
                    $activity->title,
                    $activity->type ?? 'N/A',
                    $activity->user->name,
                    $activity->organization ?? 'N/A',
                    $this->getStatusLabel($activity->workflow_status),
                    $activity->activity_date ? $activity->activity_date->format('Y-m-d') : 'N/A',
                    $activity->created_at->format('Y-m-d H:i')
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Generate CSV for users report
     */
    private function generateUsersCSV($reportData)
    {
        $filename = 'users-report-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($reportData) {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($file, [
                'ID',
                'Name',
                'Email',
                'Role',
                'Department',
                'Status',
                'Total Activities',
                'Approved Activities',
                'Registration Date'
            ]);

            // Add data rows
            foreach ($reportData['users'] as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    ucfirst($user->role),
                    $user->department ?? 'N/A',
                    $user->email_verified_at ? 'Active' : 'Inactive',
                    $user->activities->count(),
                    $user->activities->where('workflow_status', 'approved_by_vp')->count(),
                    $user->created_at->format('Y-m-d')
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Generate CSV for statistics report
     */
    private function generateStatisticsCSV($reportData)
    {
        $filename = 'statistics-report-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($reportData) {
            $file = fopen('php://output', 'w');

            // Activities by Status
            fputcsv($file, ['Activities by Status']);
            fputcsv($file, ['Status', 'Count', 'Percentage']);
            $total = $reportData['statistics']['activities_by_status']->sum('count');
            foreach ($reportData['statistics']['activities_by_status'] as $item) {
                fputcsv($file, [
                    $this->getStatusLabel($item->workflow_status),
                    $item->count,
                    $total > 0 ? round(($item->count / $total) * 100, 1) . '%' : '0%'
                ]);
            }

            fputcsv($file, []); // Empty row

            // Activities by Department
            fputcsv($file, ['Activities by Department']);
            fputcsv($file, ['Department', 'Count', 'Percentage']);
            $total = $reportData['statistics']['activities_by_department']->sum('count');
            foreach ($reportData['statistics']['activities_by_department'] as $item) {
                fputcsv($file, [
                    $item->department ?? 'N/A',
                    $item->count,
                    $total > 0 ? round(($item->count / $total) * 100, 1) . '%' : '0%'
                ]);
            }

            fputcsv($file, []); // Empty row

            // Activities by Type
            fputcsv($file, ['Activities by Type']);
            fputcsv($file, ['Type', 'Count', 'Percentage']);
            $total = $reportData['statistics']['activities_by_type']->sum('count');
            foreach ($reportData['statistics']['activities_by_type'] as $item) {
                fputcsv($file, [
                    $item->type ?? 'N/A',
                    $item->count,
                    $total > 0 ? round(($item->count / $total) * 100, 1) . '%' : '0%'
                ]);
            }

            fputcsv($file, []); // Empty row

            // Monthly Trends
            fputcsv($file, ['Monthly Trends']);
            fputcsv($file, ['Year', 'Month', 'Count']);
            foreach ($reportData['statistics']['monthly_trends'] as $item) {
                fputcsv($file, [
                    $item->year,
                    date('F', mktime(0, 0, 0, $item->month, 1)),
                    $item->count
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Get human readable status label
     */
    private function getStatusLabel($status)
    {
        $labels = [
            'draft' => 'Draft',
            'submitted' => 'Submitted',
            'pending' => 'Pending',
            'noted_by_adviser' => 'Noted by Adviser',
            'noted_by_dean' => 'Noted by Dean',
            'reviewed_by_psg' => 'Reviewed by PSG',
            'endorsed_by_director' => 'Endorsed by Director',
            'approved_by_vp' => 'Approved',
            'rejected' => 'Rejected'
        ];

        return $labels[$status] ?? $status;
    }

    /**
     * Get report period description
     */
    private function getReportPeriod($request)
    {
        if ($request->filled('date_from') && $request->filled('date_to')) {
            return 'From ' . Carbon::parse($request->date_from)->format('F d, Y') .
                   ' to ' . Carbon::parse($request->date_to)->format('F d, Y');
        } elseif ($request->filled('date_from')) {
            return 'From ' . Carbon::parse($request->date_from)->format('F d, Y') . ' onwards';
        } elseif ($request->filled('date_to')) {
            return 'Up to ' . Carbon::parse($request->date_to)->format('F d, Y');
        }
        return 'All time';
    }

    /**
     * Calculate average processing time for activities
     */
    private function calculateAverageProcessingTime($activities)
    {
        $completedActivities = $activities->where('workflow_status', 'approved_by_vp');
        if ($completedActivities->count() === 0) return 'N/A';

        $totalDays = 0;
        $count = 0;

        foreach ($completedActivities as $activity) {
            if ($activity->vp_approved_at) {
                $days = $activity->created_at->diffInDays($activity->vp_approved_at);
                $totalDays += $days;
                $count++;
            }
        }

        return $count > 0 ? round($totalDays / $count, 1) . ' days' : 'N/A';
    }

    /**
     * Get most active department
     */
    private function getMostActiveDepartment($activities)
    {
        $departments = $activities->groupBy('organization')->map->count()->sortDesc();
        return $departments->keys()->first() ?? 'N/A';
    }

    /**
     * Get activity types breakdown
     */
    private function getActivityTypesBreakdown($activities)
    {
        return $activities->groupBy('type')->map->count()->sortDesc()->take(5);
    }

    /**
     * Get activities by month
     */
    private function getActivitiesByMonth($activities)
    {
        return $activities->groupBy(function($activity) {
            return $activity->created_at->format('Y-m');
        })->map->count()->sortKeys();
    }

    /**
     * Get activities by department
     */
    private function getActivitiesByDepartment($activities)
    {
        return $activities->groupBy('organization')->map->count()->sortDesc();
    }

    /**
     * Get activities by status
     */
    private function getActivitiesByStatus($activities)
    {
        return $activities->groupBy('workflow_status')->map->count();
    }

    /**
     * Get approval timeline statistics
     */
    private function getApprovalTimeline($activities)
    {
        $timeline = [];
        $statuses = ['draft', 'submitted', 'noted_by_adviser', 'noted_by_dean', 'reviewed_by_psg', 'endorsed_by_director', 'approved_by_vp'];

        foreach ($statuses as $status) {
            $timeline[$status] = $activities->where('workflow_status', $status)->count();
        }

        return $timeline;
    }

    /**
     * Get start date based on period
     */
    private function getStartDateForPeriod($period)
    {
        switch ($period) {
            case 'week':
                return now()->startOfWeek();
            case 'month':
                return now()->startOfMonth();
            case 'quarter':
                return now()->startOfQuarter();
            case 'year':
                return now()->startOfYear();
            default:
                return now()->startOfMonth();
        }
    }
}
