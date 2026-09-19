<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

class MyApprovalsController extends Controller
{
    /**
     * Display activities approved/noted by the current user
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $status = $request->get('status', 'all');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $department = $request->get('department');

        $activities = $this->getMyApprovedActivities($user, $status, $dateFrom, $dateTo, $department);

        $roleLabel = $this->getRoleLabel($user->role);

        // Get departments list for PSG, Director, VP roles
        $departments = collect();
        if (in_array($user->role, ['psg_adviser', 'director', 'vp'])) {
            // Get departments from User model (real school departments starting with SCHOOL OF)
            $departments = User::whereNotNull('department')
                ->where('department', 'LIKE', 'SCHOOL OF%')
                ->distinct()
                ->pluck('department')
                ->sort()
                ->values();
        }

        return view('approvals.my-approvals', compact(
            'activities', 'roleLabel', 'status', 'dateFrom', 'dateTo', 'department', 'departments'
        ));
    }

    /**
     * Export as PDF (print-friendly HTML)
     */
    public function exportPdf(Request $request)
    {
        $user = Auth::user();
        $status = $request->get('status', 'all');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $department = $request->get('department');

        $activities = $this->getMyApprovedActivities($user, $status, $dateFrom, $dateTo, $department);
        $roleLabel = $this->getRoleLabel($user->role);

        $filterLabel = $this->getFilterLabel($status, $dateFrom, $dateTo, $department);

        $reportData = [
            'title' => 'My Approvals Report',
            'subtitle' => $roleLabel,
            'generated_at' => now(),
            'generated_by' => $user->name,
            'activities' => $activities,
            'roleLabel' => $roleLabel,
            'filterLabel' => $filterLabel,
            'summary' => [
                'total' => $activities->count(),
                'approved' => $activities->where('workflow_status', 'approved_by_vp')->count(),
                'in_progress' => $activities->whereNotIn('workflow_status', ['approved_by_vp', 'rejected'])->count(),
                'rejected' => $activities->where('workflow_status', 'rejected')->count(),
            ],
        ];

        $html = view('approvals.my-approvals-pdf', $reportData)->render();
        return Response::make($html, 200, [
            'Content-Type' => 'text/html',
            'Content-Disposition' => 'inline; filename="my-approvals-report-' . now()->format('Y-m-d') . '.html"'
        ]);
    }

    /**
     * Export as DOCX (Word document download)
     */
    public function exportDocx(Request $request)
    {
        $user = Auth::user();
        $status = $request->get('status', 'all');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $department = $request->get('department');

        $activities = $this->getMyApprovedActivities($user, $status, $dateFrom, $dateTo, $department);
        $roleLabel = $this->getRoleLabel($user->role);

        $filterLabel = $this->getFilterLabel($status, $dateFrom, $dateTo, $department);

        $reportData = [
            'title' => 'My Approvals Report',
            'subtitle' => $roleLabel,
            'generated_at' => now(),
            'generated_by' => $user->name,
            'activities' => $activities,
            'roleLabel' => $roleLabel,
            'filterLabel' => $filterLabel,
            'summary' => [
                'total' => $activities->count(),
                'approved' => $activities->where('workflow_status', 'approved_by_vp')->count(),
                'in_progress' => $activities->whereNotIn('workflow_status', ['approved_by_vp', 'rejected'])->count(),
                'rejected' => $activities->where('workflow_status', 'rejected')->count(),
            ],
        ];

        $html = view('approvals.my-approvals-docx', $reportData)->render();
        return Response::make($html, 200, [
            'Content-Type' => 'application/vnd.ms-word',
            'Content-Disposition' => 'attachment; filename="my-approvals-report-' . now()->format('Y-m-d') . '.doc"'
        ]);
    }

    /**
     * Get activities that this user has approved/noted/reviewed/endorsed
     */
    private function getMyApprovedActivities($user, $status = 'all', $dateFrom = null, $dateTo = null, $department = null)
    {
        $query = Activity::with(['user', 'adviserNoted', 'deanNoted', 'psgReviewed', 'directorEndorsed', 'vpApproved']);

        switch ($user->role) {
            case 'adviser':
                $query->where('adviser_noted_by', $user->id);
                break;
            case 'dean':
                $query->where('dean_noted_by', $user->id);
                break;
            case 'psg_adviser':
                $query->where('psg_reviewed_by', $user->id);
                break;
            case 'director':
                $query->where('director_endorsed_by', $user->id);
                break;
            case 'vp':
                $query->where('vp_approved_by', $user->id);
                break;
            default:
                return collect();
        }

        // Apply status filter
        if ($status === 'approved') {
            $query->where('workflow_status', 'approved_by_vp');
        } elseif ($status === 'pending') {
            $query->whereNotIn('workflow_status', ['approved_by_vp', 'rejected']);
        }
        // 'all' → no additional filter

        // Apply date filters
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        // Apply department filter (only for PSG, Director, VP)
        if ($department && in_array($user->role, ['psg_adviser', 'director', 'vp'])) {
            $query->where('organization', $department);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Get human-readable role label
     */
    private function getRoleLabel($role)
    {
        return match($role) {
            'adviser' => 'Adviser',
            'dean' => 'Dean / Unit Head',
            'psg_adviser' => 'PSG Council Adviser',
            'director' => 'Director of Student Affairs',
            'vp' => 'VP for Academics',
            default => 'Unknown Role'
        };
    }

    /**
     * Get human-readable filter label
     */
    private function getFilterLabel($status, $dateFrom = null, $dateTo = null, $department = null)
    {
        $parts = [];

        // Status part
        $parts[] = match($status) {
            'approved' => 'Approved Activities Only',
            'pending' => 'Pending Activities Only',
            default => 'All Activities'
        };

        // Date range part
        if ($dateFrom && $dateTo) {
            $parts[] = 'From ' . Carbon::parse($dateFrom)->format('F d, Y') . ' to ' . Carbon::parse($dateTo)->format('F d, Y');
        } elseif ($dateFrom) {
            $parts[] = 'From ' . Carbon::parse($dateFrom)->format('F d, Y') . ' onwards';
        } elseif ($dateTo) {
            $parts[] = 'Up to ' . Carbon::parse($dateTo)->format('F d, Y');
        }

        // Department part
        if ($department) {
            $parts[] = 'Department: ' . $department;
        }

        return implode(' | ', $parts);
    }
}
