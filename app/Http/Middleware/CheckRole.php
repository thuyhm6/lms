<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Constants\UserRole;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập');
        }

        $user = Auth::user();
        $userRole = $user->utype;

        // Kiểm tra user có role được phép không
        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        // Chuyển hướng dựa trên role của user
        return $this->redirectBasedOnRole($userRole);
    }

    private function redirectBasedOnRole($role)
    {
        switch ($role) {
            case UserRole::ADMIN:
                return redirect()->route('admin.dashboard');
            case UserRole::TEACHER:
                return redirect()->route('teacher.dashboard');
            case UserRole::STUDENT:
                return redirect()->route('student.dashboard');
            case UserRole::PARENT:
                return redirect()->route('parent.dashboard');
            // case UserRole::STAFF:
            //     return redirect()->route('staff.dashboard');
            default:
                session()->flush();
                return redirect()->route('login')->with('error', 'Không có quyền truy cập');
        }
    }
}
