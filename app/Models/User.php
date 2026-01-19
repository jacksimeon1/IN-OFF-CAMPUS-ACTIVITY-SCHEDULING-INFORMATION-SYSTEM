<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'student_id',
        'department',
        'course',
        'year_level',
        'role',
        'school',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    /**
     * Check if user has a specific role
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if user is student (role-based check)
     */
    public function isStudent(): bool
    {
        return $this->hasRole('student');
    }

    /**
     * Check if user is adviser
     */
    public function isAdviser(): bool
    {
        return $this->hasRole('adviser');
    }

    /**
     * Check if user is OSA
     */
    public function isOsa(): bool
    {
        return $this->hasRole('osa');
    }

    /**
     * Check if user is Dean/Unit Head
     */
    public function isDean(): bool
    {
        return $this->hasRole('dean');
    }

    /**
     * Check if user is PSG Council Adviser
     */
    public function isPsgAdviser(): bool
    {
        return $this->hasRole('psg_adviser');
    }

    /**
     * Check if user is Director of Student Affairs
     */
    public function isDirector(): bool
    {
        return $this->hasRole('director');
    }

    /**
     * Check if user is Vice President for Academics
     */
    public function isVp(): bool
    {
        return $this->hasRole('vp');
    }

    /**
     * Get user's approval level for workflow management
     */
    public function getApprovalLevel(): ?string
    {
        return match($this->role) {
            'adviser' => 'adviser_noted',
            'dean' => 'dean_noted',
            'psg_adviser' => 'psg_reviewed',
            'director' => 'director_endorsed',
            'vp' => 'vp_approved',
            default => null
        };
    }

    /**
     * Get user's role display name
     */
    public function getRoleDisplayName(): string
    {
        return match($this->role) {
            'admin' => 'Administrator',
            'student' => 'Student',
            'adviser' => 'Adviser',
            'dean' => 'Dean/Unit Head',
            'psg_adviser' => 'PSG Council Adviser',
            'director' => 'Director of Student Affairs',
            'vp' => 'Vice President for Academics',
            'osa' => 'Office of Student Affairs',
            default => ucfirst($this->role)
        };
    }



    /**
     * Activities submitted by this user
     */
    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * Activities advised by this user
     */
    public function advisedActivities(): HasMany
    {
        return $this->hasMany(Activity::class, 'adviser_id');
    }

    /**
     * Activities reviewed by OSA (this user)
     */
    public function osaActivities(): HasMany
    {
        return $this->hasMany(Activity::class, 'osa_id');
    }

    /**
     * Activity logs by this user
     */
    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Notifications for this user
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Organizations advised by this user
     */
    public function advisedOrganizations(): HasMany
    {
        return $this->hasMany(Organization::class, 'adviser_id');
    }

    /**
     * Activities noted by this dean
     */
    public function deanNotedActivities(): HasMany
    {
        return $this->hasMany(Activity::class, 'dean_noted_by');
    }

    /**
     * Activities reviewed by this PSG adviser
     */
    public function psgReviewedActivities(): HasMany
    {
        return $this->hasMany(Activity::class, 'psg_reviewed_by');
    }

    /**
     * Activities endorsed by this director
     */
    public function directorEndorsedActivities(): HasMany
    {
        return $this->hasMany(Activity::class, 'director_endorsed_by');
    }

    /**
     * Activities approved by this VP
     */
    public function vpApprovedActivities(): HasMany
    {
        return $this->hasMany(Activity::class, 'vp_approved_by');
    }
}
