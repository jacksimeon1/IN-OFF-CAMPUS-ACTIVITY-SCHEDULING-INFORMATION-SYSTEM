<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Http\Traits\AdminAuth;

class DashboardController extends Controller
{
    use AdminAuth;
    public function index(Request $request)
    {
        $user = Auth::user();

        // Redirect to role-specific dashboard
        switch ($user->role) {
            case 'student':
                return redirect()->route('student.dashboard');
            case 'adviser':
                return redirect()->route('adviser.dashboard');
            case 'dean':
                return redirect()->route('dean.dashboard');
            case 'psg_adviser':
                return redirect()->route('psg.dashboard');
            case 'director':
                return redirect()->route('director.dashboard');
            case 'vp':
                return redirect()->route('vp.dashboard');
            case 'osa':
                return redirect()->route('osa.dashboard');
            case 'admin':
                return redirect()->route('admin.dashboard');
            default:
                return view('dashboard');
        }
    }

    public function adminDashboard(Request $request)
    {
        $tab = $request->get('tab', 'dashboard');
        
        // Initialize variables to avoid undefined errors
        $stats = $workflowStats = $monthlySubmissions = $weeklySubmissions = $statusDistribution = null;
        $recentActivities = $activeOrganizations = null;
        $allActivities = $calendarActivities = null;
        $departments = $selectedDepartment = null;

        // Only compute data needed for the selected tab to reduce load
        // Analytics metrics are needed for 'dashboard', 'analytics', and 'accounts' tabs
        if (in_array($tab, ['dashboard', 'analytics', 'accounts'], true)) {
            $stats = [
                'total_activities' => Activity::count(),
                'pending_activities' => Activity::where(function($query) {
                    $query->where('status', 'pending')
                          ->where('workflow_status', '!=', 'approved_by_vp')
                          ->where('status', '!=', 'approved');
                })->count(),
                'approved_activities' => Activity::where(function($query) {
                    $query->where('workflow_status', 'approved_by_vp')
                          ->orWhere('status', 'approved');
                })->count(),
                'rejected_activities' => Activity::where('status', 'rejected')->count(),
                'total_users' => User::count(),
                'total_students' => User::where('role', 'student')->count(),
                'total_advisers' => User::where('role', 'adviser')->count(),
                'total_deans' => User::where('role', 'dean')->count(),
                'total_psg_advisers' => User::where('role', 'psg_adviser')->count(),
                'total_directors' => User::where('role', 'director')->count(),
                'total_vps' => User::where('role', 'vp')->count(),
                'total_organizations' => Organization::count(),
            ];

            // Workflow statistics
            $workflowStats = [
                'draft' => Activity::where('workflow_status', 'draft')->count(),
                'noted_by_adviser' => Activity::where('workflow_status', 'noted_by_adviser')->count(),
                'noted_by_dean' => Activity::where('workflow_status', 'noted_by_dean')->count(),
                'reviewed_by_psg' => Activity::where('workflow_status', 'reviewed_by_psg')->count(),
                'endorsed_by_director' => Activity::where('workflow_status', 'endorsed_by_director')->count(),
                'approved_by_vp' => Activity::where('workflow_status', 'approved_by_vp')->count(),
                'rejected' => Activity::where('workflow_status', 'rejected')->count(),
            ];

            // Monthly activity submissions
            $monthlySubmissions = Activity::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

            // Weekly activity submissions (by day of week)
            $weeklySubmissions = Activity::select(
                DB::raw('DAYOFWEEK(created_at) as day_of_week'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('day_of_week')
            ->orderBy('day_of_week')
            ->get()
            ->map(function ($item) {
                // Convert MySQL DAYOFWEEK (1=Sunday, 2=Monday, etc.) to day names
                $dayNames = [
                    1 => 'Sunday',
                    2 => 'Monday',
                    3 => 'Tuesday',
                    4 => 'Wednesday',
                    5 => 'Thursday',
                    6 => 'Friday',
                    7 => 'Saturday'
                ];
                return [
                    'day' => $dayNames[$item->day_of_week],
                    'count' => $item->count
                ];
            });

            // Activity status distribution
            $statusDistribution = Activity::select('status', DB::raw('COUNT(*) as count'))
                ->groupBy('status')
                ->get();

            // Recent activities - optimized
            $recentActivities = Activity::with(['user:id,name,email'])
                ->select('id', 'title', 'type', 'activity_date', 'end_date', 'status', 'workflow_status', 'created_at', 'user_id')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // Most active organizations
            $activeOrganizations = Activity::select('organization', DB::raw('COUNT(*) as count'))
                ->whereNotNull('organization')
                ->groupBy('organization')
                ->orderBy('count', 'desc')
                ->limit(5)
                ->get();
        }

        if ($tab === 'activities') {
            // Department filter options (the 5 schools)
            $departments = User::whereNotNull('department')
                ->where('department', 'LIKE', 'SCHOOL OF%')
                ->distinct()
                ->pluck('department')
                ->sort()
                ->values();

            $selectedDepartment = $request->get('department');

            // All activities with pagination - optional filter by department (organization)
            $query = Activity::with(['user:id,name,email'])
                ->select('id', 'title', 'organization', 'activity_date', 'end_date', 'type', 'status', 'workflow_status', 'created_at', 'user_id')
                ->orderBy('created_at', 'desc');

            if (!empty($selectedDepartment)) {
                $query->where('organization', $selectedDepartment);
            }

            $allActivities = $query->paginate(15)->appends([
                'tab' => 'activities',
                'department' => $selectedDepartment,
            ]);

            // Precompute date information to avoid heavy calculations in Blade template
            $allActivities->getCollection()->transform(function ($activity) {
                if ($activity->activity_date && $activity->end_date) {
                    $startDate = $activity->activity_date;
                    $endDate = $activity->end_date;
                    $isSingleDay = $startDate->format('Y-m-d') === $endDate->format('Y-m-d');
                    
                    if ($isSingleDay) {
                        $activity->formatted_date = $startDate->format('M d, Y');
                        $activity->date_duration = 'Single day';
                    } else {
                        $activity->formatted_date = $startDate->format('M d') . ' - ' . $endDate->format('M d, Y');
                        $activity->date_duration = ($startDate->diffInDays($endDate) + 1) . ' days';
                    }
                    $activity->is_single_day = $isSingleDay;
                } else {
                    $activity->formatted_date = null;
                    $activity->date_duration = null;
                    $activity->is_single_day = false;
                }
                return $activity;
            });
        }

        if ($tab === 'calendar') {
            // Calendar activities (only approved activities for calendar display - same as student calendar)
            // Limit to current year and next year to improve performance
            $calendarActivities = Activity::select('id', 'title', 'organization', 'activity_date', 'end_date', 'start_time', 'end_time', 'location', 'workflow_status', 'status')
                ->where(function($q){
                    $q->where('workflow_status', 'approved_by_vp')
                      ->orWhere('status', 'approved');
                })
                ->whereBetween('activity_date', [
                    now()->startOfYear(),
                    now()->addYear()->endOfYear()
                ])
                ->orderBy('activity_date')
                ->limit(500) // Reasonable limit for calendar display
                ->get()
                ->map(function ($activity) {
                    return [
                        'id' => $activity->id,
                        'title' => $activity->title,
                        'organization' => $activity->organization,
                        'activity_date' => $activity->activity_date->format('Y-m-d'),
                        'end_date' => $activity->end_date->format('Y-m-d'),
                        'start_time' => \Carbon\Carbon::parse($activity->start_time)->format('g:i A'),
                        'end_time' => \Carbon\Carbon::parse($activity->end_time)->format('g:i A'),
                        'location' => $activity->location,
                        'status' => 'approved', // Normalize to 'approved' for calendar display
                    ];
                });
        }

        return view('dashboard.admin', compact(
            'stats',
            'workflowStats',
            'monthlySubmissions',
            'weeklySubmissions',
            'statusDistribution',
            'recentActivities',
            'activeOrganizations',
            'allActivities',
            'calendarActivities',
            'tab',
            'departments',
            'selectedDepartment'
        ));
    }

    public function studentDashboard()
    {
        $user = Auth::user();

        $stats = [
            'total_activities' => $user->activities()->count(),
            'pending_activities' => $user->activities()->where('status', 'pending')->count(),
            'approved_activities' => $user->activities()->where('status', 'approved')->count(),
            'rejected_activities' => $user->activities()->where('status', 'rejected')->count(),
        ];

        $recentActivities = $user->activities()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // All user activities with pagination
        $userActivities = $user->activities()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Calendar activities - show approved by VP (workflow) OR approved by Admin (status)
        $calendarActivities = Activity::select('id', 'title', 'activity_date', 'end_date', 'start_time', 'end_time', 'location', 'status', 'workflow_status')
            ->where(function($q){
                $q->where('workflow_status', 'approved_by_vp')
                  ->orWhere('status', 'approved');
            })
            ->orderBy('activity_date')
            ->get();

        $notifications = $user->notifications()
            ->unread()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Check for upcoming activities
        $upcomingActivities = $user->activities()
            ->where('status', 'approved')
            ->where('activity_date', '>=', now())
            ->orderBy('activity_date')
            ->limit(3)
            ->get();

        return view('dashboard.student', compact(
            'user',
            'stats',
            'recentActivities',
            'userActivities',
            'calendarActivities',
            'notifications',
            'upcomingActivities'
        ));
    }

    public function adviserDashboard()
    {
        $user = Auth::user();

        $stats = [
            'pending_reviews' => Activity::where('workflow_status', 'draft')->count(),
            'recommended_activities' => Activity::where('adviser_noted_by', $user->id)->where('workflow_status', 'noted_by_adviser')->count(),
            'noted_activities' => Activity::where('adviser_noted_by', $user->id)->count(),
            'total_reviewed' => Activity::where('adviser_noted_by', $user->id)->count(),
        ];

        $pendingActivities = Activity::with(['user'])
            ->where('workflow_status', 'draft')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $recentReviews = Activity::with(['user'])
            ->where('adviser_noted_by', $user->id)
            ->orderBy('adviser_noted_at', 'desc')
            ->limit(5)
            ->get();

        // Activities by workflow status for this adviser
        $reviewStats = Activity::select('workflow_status', DB::raw('COUNT(*) as count'))
            ->where('adviser_noted_by', $user->id)
            ->groupBy('workflow_status')
            ->get();

        return view('dashboard.adviser', compact(
            'stats',
            'pendingActivities',
            'recentReviews',
            'reviewStats'
        ));
    }

    public function osaDashboard()
    {
        $user = Auth::user();

        // Overall statistics
        $totalActivities = Activity::count();
        $pendingApprovalCount = Activity::whereIn('workflow_status', [
            'draft', 'noted_by_adviser', 'noted_by_dean', 'reviewed_by_psg', 'endorsed_by_director'
        ])->count();
        $approvedActivities = Activity::where('workflow_status', 'approved_by_vp')->count();
        $rejectedActivities = Activity::where('workflow_status', 'rejected')->count();

        // Workflow stage counts
        $workflowCounts = [
            'draft' => Activity::where('workflow_status', 'draft')->count(),
            'noted_by_adviser' => Activity::where('workflow_status', 'noted_by_adviser')->count(),
            'noted_by_dean' => Activity::where('workflow_status', 'noted_by_dean')->count(),
            'reviewed_by_psg' => Activity::where('workflow_status', 'reviewed_by_psg')->count(),
            'endorsed_by_director' => Activity::where('workflow_status', 'endorsed_by_director')->count(),
            'approved_by_vp' => Activity::where('workflow_status', 'approved_by_vp')->count(),
        ];

        // Recent activities (last 10 submitted)
        $recentActivities = Activity::with('user')
            ->whereNotNull('submitted_at')
            ->orderBy('submitted_at', 'desc')
            ->limit(10)
            ->get();

        // Legacy stats for existing dashboard
        $stats = [
            'pending_approval' => Activity::where('status', 'recommended')->count(),
            'approved_activities' => Activity::where('status', 'approved')->count(),
            'rejected_activities' => Activity::where('status', 'rejected')->count(),
            'total_reviewed' => Activity::whereIn('status', ['approved', 'rejected'])->count(),
        ];

        $pendingApprovals = Activity::with(['user', 'adviser'])
            ->where('status', 'recommended')
            ->orderBy('adviser_reviewed_at', 'desc')
            ->limit(10)
            ->get();

        $recentDecisions = Activity::with(['user', 'adviser'])
            ->where('osa_id', $user->id)
            ->whereIn('status', ['approved', 'rejected'])
            ->orderBy('osa_reviewed_at', 'desc')
            ->limit(5)
            ->get();

        // Decision statistics
        $decisionStats = Activity::select('status', DB::raw('COUNT(*) as count'))
            ->where('osa_id', $user->id)
            ->whereIn('status', ['approved', 'rejected'])
            ->groupBy('status')
            ->get();

        return view('dashboard.osa', compact(
            'stats',
            'pendingApprovals',
            'recentDecisions',
            'decisionStats',
            'totalActivities',
            'pendingApprovalCount',
            'approvedActivities',
            'rejectedActivities',
            'workflowCounts',
            'recentActivities'
        ));
    }

    public function deanDashboard()
    {
        $user = Auth::user();

        // Allow access to all authenticated users, but show role-appropriate data
        if (!$user) {
            return redirect()->route('login');
        }

        // If user is not a dean, show a message but still allow access to view the interface
        $isActualDean = ($user->role === 'dean');

        // Dean-specific statistics (show demo data if not actual dean)
        if ($isActualDean) {
            // If dean has department, filter by organization matching dean's department
            if ($user->department) {
                $pendingDeanNotes = Activity::where('workflow_status', 'noted_by_adviser')
                    ->where('organization', $user->department)
                    ->count();
            } else {
                // Show all activities if dean doesn't have specific department
                $pendingDeanNotes = Activity::where('workflow_status', 'noted_by_adviser')->count();
            }
        } else {
            $pendingDeanNotes = 0; // Demo data for non-deans
        }

        if ($isActualDean) {
            if ($user->department) {
                // Filter by dean's specific department
                $departmentActivities = Activity::whereHas('user', function($query) use ($user) {
                    $query->where('department', $user->department);
                })->count();

                $notedActivities = Activity::where('workflow_status', 'noted_by_dean')
                    ->whereHas('user', function($query) use ($user) {
                        $query->where('department', $user->department);
                    })->count();

                $thisMonthActivities = Activity::whereHas('user', function($query) use ($user) {
                    $query->where('department', $user->department);
                })->whereMonth('created_at', now()->month)->count();

                // Activities awaiting dean note
                $activitiesAwaitingNote = Activity::with(['user'])
                    ->where('workflow_status', 'noted_by_adviser')
                    ->whereHas('user', function($query) use ($user) {
                        $query->where('department', $user->department);
                    })
                    ->orderBy('adviser_noted_at', 'desc')
                    ->limit(10)
                    ->get();

                // Recent department activities
                $recentDepartmentActivities = Activity::with(['user'])
                    ->whereHas('user', function($query) use ($user) {
                        $query->where('department', $user->department);
                    })
                    ->orderBy('created_at', 'desc')
                    ->limit(8)
                    ->get();
            } else {
                // Show all activities if dean doesn't have specific department
                $departmentActivities = Activity::count();
                $notedActivities = Activity::where('workflow_status', 'noted_by_dean')->count();
                $thisMonthActivities = Activity::whereMonth('created_at', now()->month)->count();

                // Activities awaiting dean note
                $activitiesAwaitingNote = Activity::with(['user'])
                    ->where('workflow_status', 'noted_by_adviser')
                    ->orderBy('adviser_noted_at', 'desc')
                    ->limit(10)
                    ->get();

                // Recent department activities
                $recentDepartmentActivities = Activity::with(['user'])
                    ->orderBy('created_at', 'desc')
                    ->limit(8)
                    ->get();
            }
        } else {
            // Demo data for non-dean users
            $departmentActivities = 0;
            $notedActivities = 0;
            $thisMonthActivities = 0;
            $activitiesAwaitingNote = collect(); // Empty collection
            $recentDepartmentActivities = collect(); // Empty collection
        }

        // Handle AJAX requests for real-time updates
        if (request()->ajax()) {
            return response()->json([
                'pendingDeanNotes' => $pendingDeanNotes,
                'departmentActivities' => $departmentActivities,
                'notedActivities' => $notedActivities,
                'thisMonthActivities' => $thisMonthActivities,
                'timestamp' => now()->toISOString()
            ]);
        }

        return view('dashboard.dean', compact(
            'pendingDeanNotes',
            'departmentActivities',
            'notedActivities',
            'thisMonthActivities',
            'activitiesAwaitingNote',
            'recentDepartmentActivities',
            'isActualDean'
        ));
    }

    public function deanDashboardActivities()
    {
        $user = Auth::user();
        $isActualDean = ($user->role === 'dean');

        if ($isActualDean) {
            if ($user->department) {
                // Activities awaiting dean note - filter by organization matching dean's department
                $activitiesAwaitingNote = Activity::with(['user'])
                    ->where('workflow_status', 'noted_by_adviser')
                    ->where('organization', $user->department)
                    ->orderBy('adviser_noted_at', 'desc')
                    ->limit(10)
                    ->get();

                // Recent department activities
                $recentDepartmentActivities = Activity::with(['user'])
                    ->whereHas('user', function($query) use ($user) {
                        $query->where('department', $user->department);
                    })
                    ->orderBy('created_at', 'desc')
                    ->limit(8)
                    ->get();
            } else {
                // Show all activities if dean doesn't have specific department
                $activitiesAwaitingNote = Activity::with(['user'])
                    ->where('workflow_status', 'noted_by_adviser')
                    ->orderBy('adviser_noted_at', 'desc')
                    ->limit(10)
                    ->get();

                $recentDepartmentActivities = Activity::with(['user'])
                    ->orderBy('created_at', 'desc')
                    ->limit(8)
                    ->get();
            }
        } else {
            $activitiesAwaitingNote = collect();
            $recentDepartmentActivities = collect();
        }

        return response()->json([
            'activitiesAwaitingNote' => $activitiesAwaitingNote->map(function($activity) {
                return [
                    'id' => $activity->id,
                    'title' => $activity->title,
                    'organization' => $activity->organization,
                    'activity_date' => $activity->activity_date ? $activity->activity_date->format('M d, Y') : 'Not set',
                    'user_name' => $activity->user->name,
                    'workflow_status' => $activity->workflow_status,
                    'approval_step_name' => $activity->getCurrentApprovalStepName(),
                    'review_url' => route('workflow.approval.form', $activity)
                ];
            }),
            'recentDepartmentActivities' => $recentDepartmentActivities->map(function($activity) {
                return [
                    'id' => $activity->id,
                    'title' => $activity->title,
                    'organization' => $activity->organization,
                    'activity_date' => $activity->activity_date ? $activity->activity_date->format('M d, Y') : 'Not set',
                    'workflow_status' => $activity->workflow_status,
                    'approval_step_name' => $activity->getCurrentApprovalStepName(),
                    'status_class' => $activity->workflow_status === 'approved_by_vp' ? 'bg-green-100 text-green-800' :
                                   ($activity->workflow_status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')
                ];
            }),
            'timestamp' => now()->toISOString()
        ]);
    }

    public function psgDashboard()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $isActualPsg = ($user->role === 'psg_adviser');

        // PSG-specific statistics (show real data for PSG advisers, demo data for others)
        if ($isActualPsg) {
            $pendingPsgReviews = Activity::where('workflow_status', 'noted_by_dean')->count();
            $totalStudentActivities = Activity::count();
            $reviewedActivities = Activity::where('psg_reviewed_by', $user->id)->count();
            $thisMonthActivities = Activity::whereMonth('created_at', now()->month)->count();

            // Activities awaiting PSG review
            $activitiesAwaitingPsgReview = Activity::with(['user'])
                ->where('workflow_status', 'noted_by_dean')
                ->orderBy('dean_noted_at', 'desc')
                ->limit(10)
                ->get();

            // Recent student activities
            $recentStudentActivities = Activity::with(['user'])
                ->orderBy('created_at', 'desc')
                ->limit(8)
                ->get();
        } else {
            // Demo data for non-PSG users
            $pendingPsgReviews = 0;
            $totalStudentActivities = 0;
            $reviewedActivities = 0;
            $thisMonthActivities = 0;
            $activitiesAwaitingPsgReview = collect();
            $recentStudentActivities = collect();
        }

        return view('dashboard.psg', compact(
            'pendingPsgReviews',
            'totalStudentActivities',
            'reviewedActivities',
            'thisMonthActivities',
            'activitiesAwaitingPsgReview',
            'recentStudentActivities',
            'isActualPsg'
        ));
    }

    public function directorDashboard()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $isActualDirector = ($user->role === 'director');

        // Director-specific statistics
        if ($isActualDirector) {
            $pendingEndorsements = Activity::where('workflow_status', 'reviewed_by_psg')->count();
            $totalActivities = Activity::count();
            $endorsedActivities = Activity::where('director_endorsed_by', $user->id)->count();
            $thisMonthActivities = Activity::whereMonth('created_at', now()->month)->count();

            // Activities awaiting director endorsement
            $activitiesAwaitingEndorsement = Activity::with(['user'])
                ->where('workflow_status', 'reviewed_by_psg')
                ->orderBy('psg_reviewed_at', 'desc')
                ->limit(10)
                ->get();

            // Recent institutional activities
            $recentInstitutionalActivities = Activity::with(['user'])
                ->orderBy('created_at', 'desc')
                ->limit(8)
                ->get();
        } else {
            // Demo data for non-director users
            $pendingEndorsements = 0;
            $totalActivities = 0;
            $endorsedActivities = 0;
            $thisMonthActivities = 0;
            $activitiesAwaitingEndorsement = collect();
            $recentInstitutionalActivities = collect();
        }

        return view('dashboard.director', compact(
            'pendingEndorsements',
            'totalActivities',
            'endorsedActivities',
            'thisMonthActivities',
            'activitiesAwaitingEndorsement',
            'recentInstitutionalActivities',
            'isActualDirector'
        ));
    }

    public function vpDashboard()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $isActualVp = ($user->role === 'vp');

        // VP-specific statistics
        if ($isActualVp) {
            $pendingFinalApprovals = Activity::where('workflow_status', 'endorsed_by_director')->count();
            $totalActivities = Activity::count();
            $approvedActivities = Activity::where('vp_approved_by', $user->id)->count();
            $thisMonthActivities = Activity::whereMonth('created_at', now()->month)->count();

            // Activities awaiting final approval
            $activitiesAwaitingFinalApproval = Activity::with(['user'])
                ->where('workflow_status', 'endorsed_by_director')
                ->orderBy('director_endorsed_at', 'desc')
                ->limit(10)
                ->get();

            // Recent executive decisions
            $recentExecutiveDecisions = Activity::with(['user'])
                ->where('vp_approved_by', $user->id)
                ->orderBy('vp_approved_at', 'desc')
                ->limit(8)
                ->get();
        } else {
            // Demo data for non-VP users
            $pendingFinalApprovals = 0;
            $totalActivities = 0;
            $approvedActivities = 0;
            $thisMonthActivities = 0;
            $activitiesAwaitingFinalApproval = collect();
            $recentExecutiveDecisions = collect();
        }

        return view('dashboard.vp', compact(
            'pendingFinalApprovals',
            'totalActivities',
            'approvedActivities',
            'thisMonthActivities',
            'activitiesAwaitingFinalApproval',
            'recentExecutiveDecisions',
            'isActualVp'
        ));
    }

    public function adminProfile()
    {
        $user = Auth::user();
        return view('admin.profile', compact('user'));
    }

    public function updateAdminProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->update($request->only(['name', 'email']));

        return redirect()->route('admin.profile')->with('status', 'profile-updated');
    }

    public function updateAdminPassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.profile')->with('status', 'password-updated');
    }
}
