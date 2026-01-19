<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    /**
     * Display the admin login view.
     */
    public function showLoginForm(Request $request)
    {
        return view('admin.auth.login');
    }

    /**
     * Handle an incoming admin authentication request.
     */
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'test_role' => ['required', 'string', 'in:admin,student,adviser,dean,psg_adviser,director,vp'],
        ], [
            'test_role.required' => 'Please select your role before logging in.',
            'test_role.in' => 'Please select a valid role.',
        ]);

        // Check hardcoded admin credentials first
        if ($request->email === 'admin@gmail.com' && $request->password === 'admin123') {
            // Create or find the admin user
            $user = User::firstOrCreate(
                ['email' => 'admin@gmail.com'],
                [
                    'name' => 'System Administrator',
                    'password' => Hash::make('admin123'),
                    'role' => 'admin',
                    'email_verified_at' => now(),
                ]
            );

            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        // Try normal authentication for other admin users
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            if ($user->role === 'admin') {
                $request->session()->regenerate();
                return redirect()->route('admin.dashboard');
            } else {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Access denied. Admin credentials required.',
                ]);
            }
        }

        return back()->withErrors([
            'email' => 'Invalid admin credentials.',
        ]);
    }



    /**
     * Destroy an authenticated admin session.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
