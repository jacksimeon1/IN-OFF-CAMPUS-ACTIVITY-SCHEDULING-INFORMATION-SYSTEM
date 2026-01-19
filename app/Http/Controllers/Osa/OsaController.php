<?php

namespace App\Http\Controllers\Osa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Models\Activity;
use App\Models\User;

class OsaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isOsa()) {
                abort(403, 'Access denied. OSA only.');
            }
            return $next($request);
        });
    }

    public function dashboard()
    {
        $user = Auth::user();

        // OSA-specific statistics
        $totalActivities = Activity::count();
        $approvedActivities = Activity::where('workflow_status', 'approved_by_vp')->count();
        $pendingActivities = Activity::whereNotIn('workflow_status', ['approved_by_vp', 'rejected'])->count();
        $thisMonthActivities = Activity::whereMonth('created_at', now()->month)->count();

        // Recent approved activities
        $recentApprovedActivities = Activity::with(['user'])
            ->where('workflow_status', 'approved_by_vp')
            ->orderBy('vp_approved_at', 'desc')
            ->limit(10)
            ->get();

        // All recent activities
        $recentActivities = Activity::with(['user'])
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        return view('osa.dashboard', compact(
            'totalActivities',
            'approvedActivities',
            'pendingActivities',
            'thisMonthActivities',
            'recentApprovedActivities',
            'recentActivities'
        ));
    }

    public function profile()
    {
        return view('osa.profile');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->update($request->only(['name', 'email']));

        return redirect()->route('osa.profile')->with('status', 'profile-updated');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('osa.profile')->with('status', 'password-updated');
    }

    public function activities()
    {
        $activities = Activity::with(['user'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('osa.activities', compact('activities'));
    }

    public function showActivity(Activity $activity)
    {
        return view('osa.show-activity', compact('activity'));
    }
}
