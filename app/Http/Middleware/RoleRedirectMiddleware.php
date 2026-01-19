<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleRedirectMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            $currentRoute = $request->route()->getName();

            // Define role-specific dashboard routes
            $roleDashboards = [
                'student' => 'dashboard',
                'adviser' => 'dashboard',
                'dean' => 'dashboard',
                'psg_adviser' => 'dashboard',
                'director_student_affairs' => 'dashboard',
                'vp_academics' => 'dashboard',
                'osa' => 'osa.dashboard',
                'admin' => 'admin.dashboard',
            ];

            // If user is accessing dashboard route, redirect to role-specific dashboard
            if ($currentRoute === 'dashboard' && isset($roleDashboards[$user->role])) {
                $targetRoute = $roleDashboards[$user->role];

                // Only redirect if not already on the correct dashboard
                if ($targetRoute !== 'dashboard') {
                    return redirect()->route($targetRoute);
                }
            }
        }

        return $next($request);
    }
}
