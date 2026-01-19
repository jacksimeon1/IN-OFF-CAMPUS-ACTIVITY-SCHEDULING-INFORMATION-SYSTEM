<?php

namespace App\Http\Traits;

use App\Models\User;
use Illuminate\Http\Request;

trait AdminAuth
{
    /**
     * Get the currently authenticated admin user
     */
    protected function getAdminUser(Request $request): ?User
    {
        $adminUserId = $request->session()->get('admin_user_id');
        
        if (!$adminUserId) {
            return null;
        }
        
        return User::find($adminUserId);
    }
    
    /**
     * Check if admin is logged in
     */
    protected function isAdminLoggedIn(Request $request): bool
    {
        return $request->session()->get('admin_logged_in', false);
    }
    
    /**
     * Get admin user from request attributes (set by middleware)
     */
    protected function getAdminUserFromRequest(Request $request): ?User
    {
        return $request->attributes->get('admin_user');
    }

    /**
     * Get student user from request attributes (set by middleware)
     */
    protected function getStudentUserFromRequest(Request $request): ?User
    {
        return $request->attributes->get('student_user');
    }
}
