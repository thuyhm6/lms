<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Constants\UserRole;

class AuthAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userRole = Auth::user()->utype;
        
        if (in_array($userRole, UserRole::getAdminRoles())) {
            return $next($request);
        }

        // Chuyển hướng dựa trên role
        return $this->redirectToUserDashboard($userRole);
    }

    private function redirectToUserDashboard($role)
    {
        switch ($role) {
            case UserRole::TEACHER:
                return redirect()->route('user.index');
            case UserRole::STUDENT:
                return redirect()->route('user.index');
            case UserRole::PARENT:
                return redirect()->route('user.index');
            default:
                return redirect()->route('login');
        }
    }
}
