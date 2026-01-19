<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Check role for given credentials without logging in (for UI display only).
     * Returns { ok: true, role: "..." } when credentials are valid; otherwise { ok: false }.
     */
    public function checkRole(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        // Validate credentials without logging in
        if (Auth::validate($credentials)) {
            $user = User::where('email', $request->email)->first();
            if ($user && $user->is_active) {
                return response()->json([
                    'ok' => true,
                    'role' => $user->role,
                ]);
            }
        }

        // Do not disclose which part failed
        return response()->json(['ok' => false]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate the request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Always use credential-based authentication
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Check if user is active
            if (!$user->is_active) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => 'Your account has been deactivated. Please contact administrator.',
                ]);
            }

            $request->session()->regenerate();

            // Redirect based on user's actual role
            return $this->redirectBasedOnRole($user->role);
        }

        throw ValidationException::withMessages([
            'email' => 'These credentials do not match our records.',
        ]);
    }



    /**
     * Redirect user based on their role
     */
    private function redirectBasedOnRole($role)
    {
        return match($role) {
            'admin' => redirect()->intended('/admin/dashboard'),
            'student' => redirect()->intended('/student/dashboard'),
            'adviser' => redirect()->intended('/adviser/dashboard'),
            'dean' => redirect()->intended('/dean/dashboard'),
            'psg_adviser' => redirect()->intended('/psg/dashboard'),
            'director' => redirect()->intended('/director/dashboard'),
            'vp' => redirect()->intended('/vp/dashboard'),
            default => redirect()->intended('/dashboard')
        };
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
