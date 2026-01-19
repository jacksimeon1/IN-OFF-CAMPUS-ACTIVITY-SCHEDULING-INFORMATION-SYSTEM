<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class StudentAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if student is logged in via session
        if (!$request->session()->get('student_logged_in')) {
            return redirect()->route('login');
        }

        // Get student user from session
        $studentUserId = $request->session()->get('student_user_id');
        if (!$studentUserId) {
            return redirect()->route('login');
        }

        $user = \App\Models\User::find($studentUserId);
        if (!$user || $user->role !== 'student') {
            $request->session()->forget(['student_user_id', 'student_logged_in']);
            return redirect()->route('login')->withErrors([
                'email' => 'Access denied. Student account required.',
            ]);
        }

        // Make student user available in the request
        $request->attributes->set('student_user', $user);

        return $next($request);
    }
}
