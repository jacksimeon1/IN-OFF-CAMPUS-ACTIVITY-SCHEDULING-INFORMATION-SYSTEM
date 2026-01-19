<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'type',
        'activity_date',
        'end_date',
        'start_time',
        'end_time',
        'location',
        'objective_1',
        'objective_2',
        'objective_3',
        'leaders',
        'speakers',
        'budget',
        'organization',
        'expected_participants',
        'budget_file',
        'permit_file',
        'supporting_documents',
        'status',
        'adviser_comments',
        'osa_comments',
        'adviser_reviewed_at',
        'osa_reviewed_at',
        'adviser_id',
        'osa_id',
        // New workflow fields
        'submitted_at',
        'workflow_status',
        'adviser_noted_by',
        'adviser_noted_at',
        'adviser_notes',
        'adviser_signature',
        'dean_noted_by',
        'dean_noted_at',
        'dean_notes',
        'dean_signature',
        'psg_reviewed_by',
        'psg_reviewed_at',
        'psg_review_comments',
        'psg_signature',
        'director_endorsed_by',
        'director_endorsed_at',
        'director_endorsement_comments',
        'director_signature',
        'vp_approved_by',
        'vp_approved_at',
        'vp_approval_comments',
        'vp_signature',
        'rejected_by',
        'rejected_at',
        'rejection_reason',
        'meets_deadline_requirement',
        'days_before_activity',
        'copy_distribution',
        'copies_distributed',
    ];

    protected $casts = [
        'activity_date' => 'date',
        'end_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'budget' => 'decimal:2',
        'expected_participants' => 'integer',
        'adviser_reviewed_at' => 'datetime',
        'osa_reviewed_at' => 'datetime',
        // New workflow casts
        'submitted_at' => 'datetime',
        'adviser_noted_at' => 'datetime',
        'dean_noted_at' => 'datetime',
        'psg_reviewed_at' => 'datetime',
        'director_endorsed_at' => 'datetime',
        'vp_approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'copy_distribution' => 'array',
        'copies_distributed' => 'boolean',
        'meets_deadline_requirement' => 'boolean',
    ];

    /**
     * User who submitted the activity
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Adviser assigned to review the activity
     */
    public function adviser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'adviser_id');
    }

    /**
     * OSA staff who reviewed the activity
     */
    public function osa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'osa_id');
    }

    /**
     * Activity logs for this activity
     */
    public function logs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Notifications related to this activity
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Adviser who noted the activity
     */
    public function adviserNoted(): BelongsTo
    {
        return $this->belongsTo(User::class, 'adviser_noted_by');
    }

    /**
     * Dean who noted the activity
     */
    public function deanNoted(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dean_noted_by');
    }

    /**
     * PSG Adviser who reviewed the activity
     */
    public function psgReviewed(): BelongsTo
    {
        return $this->belongsTo(User::class, 'psg_reviewed_by');
    }

    /**
     * Director who endorsed the activity
     */
    public function directorEndorsed(): BelongsTo
    {
        return $this->belongsTo(User::class, 'director_endorsed_by');
    }

    /**
     * VP who approved the activity
     */
    public function vpApproved(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vp_approved_by');
    }

    /**
     * User who rejected the activity
     */
    public function rejectedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    /**
     * Check if activity is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Get the URL for the budget file
     */
    public function getBudgetFileUrlAttribute(): ?string
    {
        if (!$this->budget_file) return null;

        $filename = basename($this->budget_file);
        return route('attachments.view', ['filename' => $filename]);
    }

    /**
     * Get the URL for the permit file
     */
    public function getPermitFileUrlAttribute(): ?string
    {
        if (!$this->permit_file) return null;

        $filename = basename($this->permit_file);
        return route('attachments.view', ['filename' => $filename]);
    }

    /**
     * Get the URL for the supporting documents
     */
    public function getSupportingDocumentsUrlAttribute(): ?string
    {
        if (!$this->supporting_documents) return null;

        $filename = basename($this->supporting_documents);
        return route('attachments.view', ['filename' => $filename]);
    }

    /**
     * Get download URL for budget file
     */
    public function getBudgetFileDownloadUrlAttribute(): ?string
    {
        if (!$this->budget_file) return null;

        $filename = basename($this->budget_file);
        return route('activity.download', ['type' => 'budget', 'filename' => $filename]);
    }

    /**
     * Get download URL for permit file
     */
    public function getPermitFileDownloadUrlAttribute(): ?string
    {
        if (!$this->permit_file) return null;

        $filename = basename($this->permit_file);
        return route('activity.download', ['type' => 'permits', 'filename' => $filename]);
    }

    /**
     * Get download URL for supporting documents
     */
    public function getSupportingDocumentsDownloadUrlAttribute(): ?string
    {
        if (!$this->supporting_documents) return null;

        $filename = basename($this->supporting_documents);
        return route('activity.download', ['type' => 'documents', 'filename' => $filename]);
    }

    /**
     * Check if activity is recommended
     */
    public function isRecommended(): bool
    {
        return $this->status === 'recommended';
    }

    /**
     * Check if activity is approved
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if activity is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if activity is in-campus
     */
    public function isInCampus(): bool
    {
        return $this->type === 'in-campus';
    }

    /**
     * Check if activity is off-campus
     */
    public function isOffCampus(): bool
    {
        return $this->type === 'off-campus';
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeColor(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'recommended' => 'info',
            'approved' => 'success',
            'rejected' => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Get workflow status badge color
     */
    public function getWorkflowStatusBadgeColor(): string
    {
        return match($this->workflow_status) {
            'draft' => 'secondary',
            'submitted' => 'info',
            'noted_by_adviser' => 'primary',
            'noted_by_dean' => 'primary',
            'reviewed_by_psg' => 'warning',
            'endorsed_by_director' => 'warning',
            'approved_by_vp' => 'success',
            'rejected' => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Get workflow status display name
     */
    public function getWorkflowStatusDisplayName(): string
    {
        return match($this->workflow_status) {
            'draft' => 'Draft',
            'submitted' => 'Submitted to OSA',
            'noted_by_adviser' => 'Noted by Adviser',
            'noted_by_dean' => 'Noted by Dean',
            'reviewed_by_psg' => 'Reviewed by PSG Council',
            'endorsed_by_director' => 'Endorsed by Director',
            'approved_by_vp' => 'Approved by VP',
            'rejected' => 'Rejected',
            default => 'Unknown Status'
        };
    }

    /**
     * Check if activity meets deadline requirement
     */
    public function checkDeadlineRequirement(): bool
    {
        if (!$this->activity_date) return false;

        $daysBeforeActivity = now()->diffInDays($this->activity_date, false);
        $this->days_before_activity = $daysBeforeActivity;
        $this->meets_deadline_requirement = $daysBeforeActivity >= 7;

        return $this->meets_deadline_requirement;
    }

    /**
     * Get next approval step
     */
    public function getNextApprovalStep(): ?string
    {
        return match($this->workflow_status) {
            'draft', 'submitted' => 'adviser_noted',
            'noted_by_adviser' => 'dean_noted',
            'noted_by_dean' => 'psg_reviewed',
            'reviewed_by_psg' => 'director_endorsed',
            'endorsed_by_director' => 'vp_approved',
            default => null
        };
    }

    /**
     * Check if user can take action on this activity
     */
    public function canUserTakeAction(User $user): bool
    {
        $nextStep = $this->getNextApprovalStep();
        $userLevel = $user->getApprovalLevel();

        return $nextStep && $userLevel && $nextStep === $userLevel;
    }

    /**
     * Get current approval step display name
     */
    public function getCurrentApprovalStepName(): string
    {
        return match($this->workflow_status) {
            'draft' => 'Draft',
            'noted_by_adviser' => 'Noted by Adviser',
            'noted_by_dean' => 'Noted by Dean/Unit Head',
            'reviewed_by_psg' => 'Reviewed by PSG Council Adviser',
            'endorsed_by_director' => 'Endorsed by Director of Student Affairs',
            'approved_by_vp' => 'Approved by Vice President for Academics',
            'rejected' => 'Rejected',
            default => 'Unknown Status'
        };
    }

    /**
     * Get next approver role
     */
    public function getNextApproverRole(): ?string
    {
        return match($this->workflow_status) {
            'draft' => 'adviser',
            'noted_by_adviser' => 'dean',
            'noted_by_dean' => 'psg_adviser',
            'reviewed_by_psg' => 'director',
            'endorsed_by_director' => 'vp',
            default => null
        };
    }

    /**
     * Check if activity is at a specific workflow step
     */
    public function isAtStep(string $step): bool
    {
        return $this->workflow_status === $step;
    }

    /**
     * Check if activity has passed a specific workflow step
     */
    public function hasPassedStep(string $step): bool
    {
        $steps = [
            'draft' => 0,
            'noted_by_adviser' => 1,
            'noted_by_dean' => 2,
            'reviewed_by_psg' => 3,
            'endorsed_by_director' => 4,
            'approved_by_vp' => 5,
            'rejected' => -1
        ];

        $currentStepOrder = $steps[$this->workflow_status] ?? 0;
        $targetStepOrder = $steps[$step] ?? 0;

        return $currentStepOrder > $targetStepOrder;
    }

    /**
     * Get copy distribution list
     */
    public function getCopyDistributionList(): array
    {
        return [
            'osa' => 'Office of Student Affairs',
            'dean' => 'Dean/Unit Head',
            'director' => 'Director of Student Affairs and Academic Support Services',
            'vp' => 'Vice President for Academics and Quality Assurance'
        ];
    }

    /**
     * Check if activity date range conflicts with existing activities
     */
    public function hasDateConflict(): bool
    {
        return static::where('location', $this->location)
            ->where('id', '!=', $this->id ?? 0)
            ->whereIn('status', ['approved', 'recommended'])
            ->where(function($query) {
                // Check for date range overlaps
                $query->where(function($q) {
                    // New activity starts during existing activity
                    $q->where('activity_date', '<=', $this->activity_date)
                      ->where('end_date', '>=', $this->activity_date);
                })->orWhere(function($q) {
                    // New activity ends during existing activity
                    $q->where('activity_date', '<=', $this->end_date)
                      ->where('end_date', '>=', $this->end_date);
                })->orWhere(function($q) {
                    // New activity completely contains existing activity
                    $q->where('activity_date', '>=', $this->activity_date)
                      ->where('end_date', '<=', $this->end_date);
                });
            })
            ->exists();
    }

    /**
     * Check for time and venue conflicts with detailed information
     */
    public function getConflictDetails(): array
    {
        $conflicts = [];

        // Check for exact time and venue conflicts
        $timeVenueConflicts = static::where('location', 'LIKE', '%' . trim($this->location) . '%')
            ->where('id', '!=', $this->id ?? 0)
            ->whereIn('status', ['approved', 'recommended', 'pending'])
            ->where(function($query) {
                // Check for date range overlaps
                $query->where(function($q) {
                    // New activity starts during existing activity
                    $q->where('activity_date', '<=', $this->activity_date)
                      ->where('end_date', '>=', $this->activity_date);
                })->orWhere(function($q) {
                    // New activity ends during existing activity
                    $q->where('activity_date', '<=', $this->end_date)
                      ->where('end_date', '>=', $this->end_date);
                })->orWhere(function($q) {
                    // New activity completely contains existing activity
                    $q->where('activity_date', '>=', $this->activity_date)
                      ->where('end_date', '<=', $this->end_date);
                });
            })
            ->where(function($query) {
                // Check for overlapping time periods
                $query->where(function($q) {
                    // New activity starts during existing activity
                    $q->where('start_time', '<=', $this->start_time)
                      ->where('end_time', '>', $this->start_time);
                })->orWhere(function($q) {
                    // New activity ends during existing activity
                    $q->where('start_time', '<', $this->end_time)
                      ->where('end_time', '>=', $this->end_time);
                })->orWhere(function($q) {
                    // New activity completely contains existing activity
                    $q->where('start_time', '>=', $this->start_time)
                      ->where('end_time', '<=', $this->end_time);
                })->orWhere(function($q) {
                    // Existing activity completely contains new activity
                    $q->where('start_time', '<=', $this->start_time)
                      ->where('end_time', '>=', $this->end_time);
                });
            })
            ->with('user')
            ->get();

        foreach ($timeVenueConflicts as $conflict) {
            $conflicts[] = [
                'type' => 'time_venue',
                'severity' => 'high',
                'message' => "Time and venue conflict with '{$conflict->title}' by {$conflict->user->name}",
                'details' => [
                    'conflicting_activity' => $conflict->title,
                    'conflicting_user' => $conflict->user->name,
                    'conflicting_time' => $conflict->start_time->format('g:i A') . ' - ' . $conflict->end_time->format('g:i A'),
                    'conflicting_location' => $conflict->location,
                    'conflicting_status' => $conflict->status
                ]
            ];
        }

        return $conflicts;
    }

    /**
     * Get scheduling warnings and suggestions
     */
    public function getSchedulingWarnings(): array
    {
        $warnings = [];

        // Skip the short notice warning as it's not needed

        // Skip same day activities warning as it's not needed

        // Check for weekend activities
        $dayOfWeek = $this->activity_date->dayOfWeek;
        if ($dayOfWeek == 0 || $dayOfWeek == 6) { // Sunday = 0, Saturday = 6
            $warnings[] = [
                'type' => 'weekend',
                'severity' => 'low',
                'message' => 'Activity is scheduled on a weekend.',
                'suggestion' => 'Ensure necessary staff and resources are available for weekend activities.'
            ];
        }

        // Check budget warnings
        if ($this->budget && $this->budget < 1000) {
            $warnings[] = [
                'type' => 'low_budget',
                'severity' => 'low',
                'message' => 'Budget appears to be quite low for this activity.',
                'suggestion' => 'Review if all necessary expenses are included in the budget.'
            ];
        }

        return $warnings;
    }

    /**
     * Get alternative time suggestions
     */
    public function getAlternativeTimeSlots(): array
    {
        $suggestions = [];
        $date = $this->activity_date;

        // Get all activities on the same date
        $existingActivities = static::where('activity_date', $date)
            ->whereIn('status', ['approved', 'recommended'])
            ->orderBy('start_time')
            ->get();

        // Suggest time slots based on gaps
        $timeSlots = [
            ['start' => '08:00', 'end' => '10:00', 'label' => 'Morning (8:00 AM - 10:00 AM)'],
            ['start' => '10:00', 'end' => '12:00', 'label' => 'Late Morning (10:00 AM - 12:00 PM)'],
            ['start' => '13:00', 'end' => '15:00', 'label' => 'Afternoon (1:00 PM - 3:00 PM)'],
            ['start' => '15:00', 'end' => '17:00', 'label' => 'Late Afternoon (3:00 PM - 5:00 PM)'],
            ['start' => '18:00', 'end' => '20:00', 'label' => 'Evening (6:00 PM - 8:00 PM)'],
        ];

        foreach ($timeSlots as $slot) {
            $hasConflict = false;
            foreach ($existingActivities as $existing) {
                if ($existing->location === $this->location) {
                    $existingStart = $existing->start_time->format('H:i');
                    $existingEnd = $existing->end_time->format('H:i');

                    // Check if time slot overlaps with existing activity
                    if (($slot['start'] < $existingEnd && $slot['end'] > $existingStart)) {
                        $hasConflict = true;
                        break;
                    }
                }
            }

            if (!$hasConflict) {
                $suggestions[] = $slot;
            }
        }

        return $suggestions;
    }

    /**
     * Scope for filtering by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for filtering by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for filtering by date range
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('activity_date', [$startDate, $endDate]);
    }

    /**
     * Advance workflow to next step
     */
    public function advanceWorkflow(User $approver, string $comments = null, string $action = 'approve'): bool
    {
        if (!$this->canUserTakeAction($approver)) {
            return false;
        }

        $currentTime = now();

        switch ($this->workflow_status) {
            case 'draft':
                if ($approver->isAdviser()) {
                    $this->adviser_noted_by = $approver->id;
                    $this->adviser_noted_at = $currentTime;
                    $this->adviser_notes = $comments;
                    $this->workflow_status = 'noted_by_adviser';
                }
                break;

            case 'noted_by_adviser':
                if ($approver->isDean()) {
                    $this->dean_noted_by = $approver->id;
                    $this->dean_noted_at = $currentTime;
                    $this->dean_notes = $comments;
                    $this->workflow_status = 'noted_by_dean';
                }
                break;

            case 'noted_by_dean':
                if ($approver->isPsgAdviser()) {
                    $this->psg_reviewed_by = $approver->id;
                    $this->psg_reviewed_at = $currentTime;
                    $this->psg_review_comments = $comments;
                    $this->workflow_status = 'reviewed_by_psg';
                }
                break;

            case 'reviewed_by_psg':
                if ($approver->isDirector()) {
                    $this->director_endorsed_by = $approver->id;
                    $this->director_endorsed_at = $currentTime;
                    $this->director_endorsement_comments = $comments;
                    $this->workflow_status = 'endorsed_by_director';
                }
                break;

            case 'endorsed_by_director':
                if ($approver->isVp()) {
                    $this->vp_approved_by = $approver->id;
                    $this->vp_approved_at = $currentTime;
                    $this->vp_approval_comments = $comments;
                    $this->workflow_status = 'approved_by_vp';
                    $this->status = 'approved'; // Final approval
                }
                break;

            default:
                return false;
        }

        if ($action === 'reject') {
            $this->rejected_by = $approver->id;
            $this->rejected_at = $currentTime;
            $this->rejection_reason = $comments;
            $this->workflow_status = 'rejected';
            $this->status = 'rejected';
        }

        return $this->save();
    }

    /**
     * Get workflow progress percentage
     */
    public function getWorkflowProgress(): int
    {
        $steps = [
            'draft' => 0,
            'submitted' => 10,
            'noted_by_adviser' => 25,
            'noted_by_dean' => 40,
            'reviewed_by_psg' => 60,
            'endorsed_by_director' => 80,
            'approved_by_vp' => 100,
            'rejected' => 0
        ];

        return $steps[$this->workflow_status] ?? 0;
    }
}
