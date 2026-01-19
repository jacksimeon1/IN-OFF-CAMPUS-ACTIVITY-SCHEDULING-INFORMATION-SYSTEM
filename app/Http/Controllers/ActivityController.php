<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityLog;
use App\Models\Notification;
use App\Models\Organization;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Activity::with(['user', 'adviser', 'osa']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by date range
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('activity_date', [$request->date_from, $request->date_to]);
        }

        // Role-based filtering
        $user = Auth::user();
        if ($user->isStudent()) {
            $query->where('user_id', $user->id);
        } elseif ($user->isAdviser()) {
            $query->where('adviser_id', $user->id);
        } elseif ($user->isOsa()) {
            $query->whereIn('status', ['recommended', 'approved', 'rejected']);
        }

        $activities = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('activities.index', compact('activities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $organizations = Organization::active()->get();
        return view('activities.create', compact('organizations'));
    }

    /**
     * Check for conflicts before storing (AJAX endpoint)
     */
    public function checkConflicts(Request $request)
    {
        try {
            $request->validate([
                'activity_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:activity_date',
                'start_time' => 'required',
                'end_time' => 'required',
                'location' => 'required|string',
            ]);

            // Normalize inputs
            $startDate = date('Y-m-d', strtotime($request->activity_date));
            $endDate = date('Y-m-d', strtotime($request->end_date));
            $startTime = date('H:i:s', strtotime($request->start_time));
            $endTime = date('H:i:s', strtotime($request->end_time));
            $location = trim(preg_replace('/\s+/', ' ', $request->location));

            // Precise overlap rules:
            // Date ranges overlap if NOT (existing.end_date < new.start_date OR existing.start_date > new.end_date)
            // Time ranges overlap if (existing.start_time < new.end_time AND existing.end_time > new.start_time)
            $conflicts = Activity::query()
                // Consider only activities that are active in the workflow or overall status
                ->where(function ($q) {
                    $q->whereIn('status', ['approved', 'recommended', 'pending'])
                      ->orWhereIn('workflow_status', [
                          'submitted_to_adviser', 'reviewed_by_adviser', 'reviewed_by_dean',
                          'endorsed_by_director', 'approved_by_vp', 'submitted_to_osa', 'pending', 'draft'
                      ]);
                })
                // Location match: case-insensitive equality or partial match
                ->where(function ($q) use ($location) {
                    $q->whereRaw('LOWER(location) = ?', [mb_strtolower($location)])
                      ->orWhere('location', 'LIKE', '%' . $location . '%');
                })
                // Date overlap
                ->whereRaw('NOT (end_date < ? OR activity_date > ?)', [$startDate, $endDate])
                // Time overlap
                ->where(function ($q) use ($startTime, $endTime) {
                    $q->where('start_time', '<', $endTime)
                      ->where('end_time', '>', $startTime);
                })
                ->with('user')
                ->get();

            $conflictDetails = [];
            foreach ($conflicts as $conflict) {
                $dateRange = $conflict->activity_date->format('M d, Y');
                if ($conflict->activity_date->format('Y-m-d') !== $conflict->end_date->format('Y-m-d')) {
                    $dateRange .= ' - ' . $conflict->end_date->format('M d, Y');
                }

                $conflictDetails[] = [
                    'type' => 'time_venue',
                    'message' => "Conflict with '{$conflict->title}' by {$conflict->user->name}",
                    'details' => [
                        'conflicting_activity' => $conflict->title,
                        'conflicting_user' => $conflict->user->name,
                        'conflicting_department' => $conflict->organization ?? ($conflict->user->department ?? null),
                        'conflicting_time' => date('g:i A', strtotime($conflict->start_time)) . ' - ' . date('g:i A', strtotime($conflict->end_time)),
                        'conflicting_date' => $dateRange,
                        'conflicting_location' => $conflict->location,
                        'conflicting_status' => $conflict->status
                    ]
                ];
            }

            $hasConflicts = !empty($conflictDetails);

            // Build smart suggestions if conflicts exist
            $suggestions = [];
            if ($hasConflicts) {
                // Helper closure to check availability of a proposed slot
                $isAvailable = function (string $sDate, string $eDate, string $sTime, string $eTime, string $loc): bool {
                    $existing = Activity::query()
                        ->where(function ($q) {
                            $q->whereIn('status', ['approved', 'recommended', 'pending'])
                              ->orWhereIn('workflow_status', [
                                  'submitted_to_adviser', 'reviewed_by_adviser', 'reviewed_by_dean',
                                  'endorsed_by_director', 'approved_by_vp', 'submitted_to_osa', 'pending', 'draft'
                              ]);
                        })
                        ->where(function ($q) use ($loc) {
                            $q->whereRaw('LOWER(location) = ?', [mb_strtolower($loc)])
                              ->orWhere('location', 'LIKE', '%' . $loc . '%');
                        })
                        ->whereRaw('NOT (end_date < ? OR activity_date > ?)', [$sDate, $eDate])
                        ->where(function ($q) use ($sTime, $eTime) {
                            $q->where('start_time', '<', $eTime)
                              ->where('end_time', '>', $sTime);
                        })
                        ->limit(1)
                        ->exists();
                    return !$existing;
                };

                $maxSuggestions = 8; // cap total suggestions

                // Utility: add suggestion safely (dedupe)
                $appendTimeSuggestion = function(array &$list, string $sDate, string $eDate, string $sTime, string $eTime, string $label) use ($maxSuggestions) {
                    if (count($list) >= $maxSuggestions) return;
                    $key = $sDate.'|'.$eDate.'|'.$sTime.'|'.$eTime;
                    foreach ($list as $s) {
                        if (($s['type'] ?? '') === 'time' && ($s['activity_date'].'|'.$s['end_date'].'|'.$s['start_time'].'|'.$s['end_time']) === $key) {
                            return; // duplicate
                        }
                    }
                    $list[] = [
                        'type' => 'time',
                        'activity_date' => $sDate,
                        'end_date' => $eDate,
                        'start_time' => $sTime,
                        'end_time' => $eTime,
                        'label' => $label,
                    ];
                };

                // Time suggestions: try backward and forward shifts on same day
                $tryTimeShifts = [-60, -30, 30, 60, 90, 120];
                foreach ($tryTimeShifts as $mins) {
                    $s = date('H:i:s', strtotime($startTime . " +{$mins} minutes"));
                    $e = date('H:i:s', strtotime($endTime . " +{$mins} minutes"));
                    // Keep shifts within same calendar day if original is single-day
                    if ($startDate === $endDate) {
                        if ($isAvailable($startDate, $endDate, $s, $e, $location)) {
                            $labelDelta = ($mins >= 0 ? '+' : '') . $mins . ' mins';
                            $appendTimeSuggestion(
                                $suggestions,
                                $startDate,
                                $endDate,
                                $s,
                                $e,
                                date('g:i A', strtotime($s)) . ' - ' . date('g:i A', strtotime($e)) . " (".$labelDelta.")"
                            );
                        }
                    }
                }

                // Next day same time as fallback
                $nextStartDate = date('Y-m-d', strtotime($startDate . ' +1 day'));
                $nextEndDate = date('Y-m-d', strtotime($endDate . ' +1 day'));
                if ($isAvailable($nextStartDate, $nextEndDate, $startTime, $endTime, $location)) {
                    $appendTimeSuggestion(
                        $suggestions,
                        $nextStartDate,
                        $nextEndDate,
                        $startTime,
                        $endTime,
                        'Next day (' . date('M d, Y', strtotime($nextStartDate)) . ') same time'
                    );
                }

                // Same-day scanning for first available slot with the same duration
                if ($startDate === $endDate) {
                    $durationSec = strtotime($endTime) - strtotime($startTime);
                    if ($durationSec > 0) {
                        $scanStart = strtotime('06:00:00');
                        $scanEnd = strtotime('21:00:00');
                        for ($t = $scanStart; $t <= $scanEnd; $t += 30 * 60) { // 30-min steps
                            $s = date('H:i:s', $t);
                            $e = date('H:i:s', $t + $durationSec);
                            if ($isAvailable($startDate, $endDate, $s, $e, $location)) {
                                $appendTimeSuggestion(
                                    $suggestions,
                                    $startDate,
                                    $endDate,
                                    $s,
                                    $e,
                                    'Available Time: ' . date('g:i A', $t) . ' - ' . date('g:i A', $t + $durationSec)
                                );
                                if (count($suggestions) >= $maxSuggestions) break;
                            }
                        }
                    }
                }

                // Scan next few days (2-3 days) for same time and for first available window during working hours
                $daysAhead = [2, 3];
                foreach ($daysAhead as $d) {
                    $dStart = date('Y-m-d', strtotime($startDate . " +{$d} day"));
                    $dEnd = date('Y-m-d', strtotime($endDate . " +{$d} day"));
                    // First, same time on that day(s)
                    if ($isAvailable($dStart, $dEnd, $startTime, $endTime, $location)) {
                        $appendTimeSuggestion(
                            $suggestions,
                            $dStart,
                            $dEnd,
                            $startTime,
                            $endTime,
                            date('D, M d', strtotime($dStart)) . ': same time'
                        );
                    }
                    if ($startDate === $endDate && count($suggestions) < $maxSuggestions) {
                        // Then scan working hours for an available window with same duration
                        $durationSec = strtotime($endTime) - strtotime($startTime);
                        $scanStart = strtotime('06:00:00');
                        $scanEnd = strtotime('21:00:00');
                        for ($t = $scanStart; $t <= $scanEnd; $t += 30 * 60) {
                            $s = date('H:i:s', $t);
                            $e = date('H:i:s', $t + $durationSec);
                            if ($isAvailable($dStart, $dEnd, $s, $e, $location)) {
                                $appendTimeSuggestion(
                                    $suggestions,
                                    $dStart,
                                    $dEnd,
                                    $s,
                                    $e,
                                    date('D, M d', strtotime($dStart)) . ': ' . date('g:i A', $t) . ' - ' . date('g:i A', $t + $durationSec)
                                );
                                break; // one per day
                            }
                        }
                    }
                    if (count($suggestions) >= $maxSuggestions) break;
                }

                // Venue suggestions: try known in-campus venues if different
                $knownVenues = ['Student Center', 'BEU', 'Global', 'MM Hall'];
                foreach ($knownVenues as $venue) {
                    if (mb_strtolower($venue) === mb_strtolower($location)) { continue; }
                    if ($isAvailable($startDate, $endDate, $startTime, $endTime, $venue)) {
                        $suggestions[] = [
                            'type' => 'venue',
                            'location' => $venue,
                            'label' => $venue
                        ];
                    }
                }
            }

            return response()->json([
                'success' => true,
                'hasConflicts' => $hasConflicts,
                'conflicts' => $conflictDetails,
                'canProceed' => !$hasConflicts,
                'suggestions' => $suggestions,
                'message' => !$hasConflicts
                    ? 'No conflicts found. You can proceed with this schedule.'
                    : 'Scheduling conflicts detected. Please choose a different time or venue.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'message' => 'Error checking conflicts: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Force organization to the logged-in student's department
        $authUser = Auth::user();
        if ($authUser && method_exists($authUser, 'isStudent') && $authUser->isStudent()) {
            if (empty($authUser->department)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['organization' => 'You are not assigned to a department. Please contact the administrator.']);
            }
            // Override any incoming organization value
            $request->merge(['organization' => $authUser->department]);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:in-campus,off-campus',
            'activity_date' => 'required|date|after:today',
            'end_date' => 'required|date|after_or_equal:activity_date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'location' => 'required|string|max:255',
            'organization' => 'required|string|max:255',
            'leaders' => 'required|string',
            'objective_1' => 'required|string|max:500',
            'objective_2' => 'nullable|string|max:500',
            'objective_3' => 'nullable|string|max:500',
            'expected_participants' => 'required|integer|min:1',
            'budget' => 'nullable|numeric|min:0',
            'speakers' => 'nullable|string',
            'budget_file' => 'required|file|mimes:pdf,doc,docx,png,jpg,jpeg',
            'permit_file' => 'required|file|mimes:pdf,doc,docx,png,jpg,jpeg',
            'supporting_documents' => 'nullable|file|mimes:pdf,doc,docx,png,jpg,jpeg',
        ]);

        $validated['user_id'] = Auth::id();

        // Check for conflicts before saving
        $tempActivity = new Activity($validated);
        $conflicts = $tempActivity->getConflictDetails();

        if (!empty($conflicts)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['conflict' => 'There are scheduling conflicts with your activity. Please check the details and choose a different time or venue.'])
                ->with('conflicts', $conflicts);
        }

        // Handle file uploads with original filenames
        if ($request->hasFile('budget_file')) {
            $file = $request->file('budget_file');
            $originalName = $file->getClientOriginalName();
            $timestamp = time();
            $filename = $timestamp . '_' . $originalName;
            $validated['budget_file'] = $file->storeAs('activities/budget', $filename, 'public');
        }

        if ($request->hasFile('permit_file')) {
            $file = $request->file('permit_file');
            $originalName = $file->getClientOriginalName();
            $timestamp = time();
            $filename = $timestamp . '_' . $originalName;
            $validated['permit_file'] = $file->storeAs('activities/permits', $filename, 'public');
        }

        if ($request->hasFile('supporting_documents')) {
            $file = $request->file('supporting_documents');
            $originalName = $file->getClientOriginalName();
            $timestamp = time();
            $filename = $timestamp . '_' . $originalName;
            $validated['supporting_documents'] = $file->storeAs('activities/documents', $filename, 'public');
        }

        // Set workflow status to draft (pending adviser approval)
        $validated['workflow_status'] = 'draft';
        $validated['submitted_at'] = now();

        $activity = Activity::create($validated);

        // Check deadline requirement
        $activity->checkDeadlineRequirement();
        $activity->save();

        // Create activity log
        ActivityLog::create([
            'activity_id' => $activity->id,
            'user_id' => Auth::id(),
            'action' => 'created',
            'previous_status' => null,
            'new_status' => 'draft',
            'comments' => 'Activity created and submitted for adviser approval',
        ]);

        // Send notification using the notification service
        $this->notificationService->notifyActivitySubmitted($activity);

        return redirect()->route('activities.index')->with('success', 'Activity submitted successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Activity $activity)
    {
        $activity->load(['user', 'adviser', 'osa', 'logs.user']);

        // Check if user can view this activity
        $user = Auth::user();
        if ($user->isStudent() && $activity->user_id !== $user->id) {
            abort(403);
        }

        return view('activities.show', compact('activity'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Activity $activity)
    {
        // Allow editing only if the activity is in draft or rejected and owned by the user
        if (!in_array($activity->workflow_status, ['draft', 'rejected']) || $activity->user_id !== Auth::id()) {
            $reason = [];
            if (!in_array($activity->workflow_status, ['draft', 'rejected'])) { $reason[] = 'not editable status'; }
            if ($activity->user_id !== Auth::id()) { $reason[] = 'not owner'; }
            return redirect()->route('activities.index')
                ->with('error', 'You can only edit your own activities in Draft or Rejected status.' . (count($reason) ? ' (' . implode(', ', $reason) . ')' : ''));
        }

        $organizations = Organization::active()->get();
        return view('activities.edit', compact('activity', 'organizations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Activity $activity)
    {
        // Allow updating only if the activity is in draft or rejected and owned by the user
        if (!in_array($activity->workflow_status, ['draft', 'rejected']) || $activity->user_id !== Auth::id()) {
            $reason = [];
            if (!in_array($activity->workflow_status, ['draft', 'rejected'])) { $reason[] = 'not editable status'; }
            if ($activity->user_id !== Auth::id()) { $reason[] = 'not owner'; }
            return redirect()->route('activities.index')
                ->with('error', 'You can only edit your own activities in Draft or Rejected status.' . (count($reason) ? ' (' . implode(', ', $reason) . ')' : ''));
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:in-campus,off-campus',
            'activity_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:activity_date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'location' => 'required|string|max:255',
            'organization' => 'required|string|max:255',
            'leaders' => 'required|string',
            'objective_1' => 'required|string|max:500',
            'objective_2' => 'nullable|string|max:500',
            'objective_3' => 'nullable|string|max:500',
            'expected_participants' => 'required|integer|min:1',
            'budget' => 'nullable|numeric|min:0',
            'speakers' => 'nullable|string',
            'budget_file' => 'nullable|file|mimes:pdf,doc,docx,png,jpg,jpeg',
            'permit_file' => 'nullable|file|mimes:pdf,doc,docx,png,jpg,jpeg',
            'supporting_documents' => 'nullable|file|mimes:pdf,doc,docx,png,jpg,jpeg',
        ]);

        // Handle file uploads with original filenames
        if ($request->hasFile('budget_file')) {
            if ($activity->budget_file) {
                Storage::disk('public')->delete($activity->budget_file);
            }
            $file = $request->file('budget_file');
            $originalName = $file->getClientOriginalName();
            $timestamp = time();
            $filename = $timestamp . '_' . $originalName;
            $validated['budget_file'] = $file->storeAs('activities/budget', $filename, 'public');
        }

        if ($request->hasFile('permit_file')) {
            if ($activity->permit_file) {
                Storage::disk('public')->delete($activity->permit_file);
            }
            $file = $request->file('permit_file');
            $originalName = $file->getClientOriginalName();
            $timestamp = time();
            $filename = $timestamp . '_' . $originalName;
            $validated['permit_file'] = $file->storeAs('activities/permits', $filename, 'public');
        }

        if ($request->hasFile('supporting_documents')) {
            if ($activity->supporting_documents) {
                Storage::disk('public')->delete($activity->supporting_documents);
            }
            $file = $request->file('supporting_documents');
            $originalName = $file->getClientOriginalName();
            $timestamp = time();
            $filename = $timestamp . '_' . $originalName;
            $validated['supporting_documents'] = $file->storeAs('activities/documents', $filename, 'public');
        }

        $previousWorkflow = $activity->workflow_status;

        $activity->update($validated);

        // If previously rejected, treat this update as a resubmission
        if ($previousWorkflow === 'rejected') {
            // Capture rejector before clearing fields
            $rejector = $activity->rejectedBy; // relation to User

            // Map rejector role to the appropriate checkpoint so the next approver equals the rejector role
            $targetCheckpoint = 'draft';
            if ($rejector) {
                switch ($rejector->role) {
                    case 'adviser':
                        $targetCheckpoint = 'draft';
                        break;
                    case 'dean':
                        $targetCheckpoint = 'noted_by_adviser';
                        break;
                    case 'psg_adviser':
                        $targetCheckpoint = 'noted_by_dean';
                        break;
                    case 'director':
                        $targetCheckpoint = 'reviewed_by_psg';
                        break;
                    case 'vp':
                        $targetCheckpoint = 'endorsed_by_director';
                        break;
                    default:
                        $targetCheckpoint = 'draft';
                }
            }

            // Set workflow to checkpoint just before the rejecting role
            $activity->workflow_status = $targetCheckpoint;

            // Legacy overall status reset (must not be null due to DB constraint)
            $activity->status = 'pending';
            // Mark as (re)submitted and re-evaluate deadline requirement
            $activity->submitted_at = now();
            $activity->checkDeadlineRequirement();

            // Now clear rejection metadata (after we've used it)
            $activity->rejected_by = null;
            $activity->rejected_at = null;
            $activity->rejection_reason = null;

            $activity->save();

            // Log resubmission event
            ActivityLog::create([
                'activity_id' => $activity->id,
                'user_id' => Auth::id(),
                'action' => 'resubmitted',
                'previous_status' => 'rejected',
                'new_status' => $targetCheckpoint,
                'comments' => $rejector ? ('Resubmitted to ' . ucfirst(str_replace('_', ' ', $rejector->role))) : 'Resubmitted for next approval step',
            ]);

            // Notify the rejector directly if available; otherwise notify next approver per workflow
            if ($rejector && $rejector->is_active) {
                $this->notificationService->notifyResubmissionToRejector($activity, $rejector);
            } else {
                $this->notificationService->notifyNextApprover($activity);
            }

            // Also notify the activity owner of successful resubmission
            // (reuse submitted message semantics)
            // This informs the student their resubmission has been sent to the appropriate role
            // without re-notifying all initial approvers unnecessarily.
            // We'll keep using the existing success pattern by sending a direct notification to the owner.
            // Implemented inside NotificationService method below.
            $this->notificationService->notifyOwnerResubmitted($activity, $rejector);
        }

        return redirect()->route('activities.show', $activity)->with('success', 'Activity updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Activity $activity)
    {
        // Allow deletion only if the activity is in draft and owned by the user
        if ($activity->workflow_status !== 'draft' || $activity->user_id !== Auth::id()) {
            $reason = [];
            if ($activity->workflow_status !== 'draft') { $reason[] = 'not in draft'; }
            if ($activity->user_id !== Auth::id()) { $reason[] = 'not owner'; }
            return redirect()->route('activities.index')
                ->with('error', 'You can only delete your own activities in draft status.' . (count($reason) ? ' (' . implode(', ', $reason) . ')' : ''));
        }

        // Delete associated files
        if ($activity->budget_file) {
            Storage::disk('public')->delete($activity->budget_file);
        }
        if ($activity->permit_file) {
            Storage::disk('public')->delete($activity->permit_file);
        }
        if ($activity->supporting_documents) {
            Storage::disk('public')->delete($activity->supporting_documents);
        }

        $activity->delete();

        return redirect()->route('activities.index')->with('success', 'Activity deleted successfully!');
    }

    /**
     * Notify advisers about new activity submission
     */
    private function notifyAdvisers(Activity $activity)
    {
        $advisers = \App\Models\User::where('role', 'adviser')->get();

        foreach ($advisers as $adviser) {
            Notification::create([
                'user_id' => $adviser->id,
                'activity_id' => $activity->id,
                'title' => 'New Activity Submission',
                'message' => "A new activity '{$activity->title}' has been submitted for review.",
                'type' => 'info',
            ]);
        }
    }

    /**
     * Notify admins about new activity submission
     */
    private function notifyAdmins(Activity $activity)
    {
        $admins = \App\Models\User::where('role', 'admin')->get();
        $student = $activity->user;

        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'activity_id' => $activity->id,
                'title' => '🎯 New Activity Submitted!',
                'message' => "📋 '{$activity->title}' submitted by {$student->name}\n📅 Scheduled: {$activity->activity_date->format('M d, Y')}\n📍 Location: {$activity->location}\n⏰ Time: {$activity->start_time->format('g:i A')} - {$activity->end_time->format('g:i A')}",
                'type' => 'info',
            ]);
        }
    }
}
