<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Constants\UserRole;

class AuthTeacher
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userRole = Auth::user()->utype;
        
        if (in_array($userRole, UserRole::getTeacherRoles())) {
            return $next($request);
        }

        return $this->redirectToUserDashboard($userRole);
    }

    private function redirectToUserDashboard($role)
    {
        switch ($role) {
            case UserRole::STUDENT:
                return redirect()->route('student.dashboard');
            case UserRole::PARENT:
                return redirect()->route('parent.dashboard');
            default:
                return redirect()->route('login');
        }
    }
}
