<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Constants\UserRole;

class AuthStudent
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->utype === UserRole::STUDENT) {
            return $next($request);
        }

        return redirect()->route('login')->with('error', 'Chỉ học sinh mới có thể truy cập');
    }
}
