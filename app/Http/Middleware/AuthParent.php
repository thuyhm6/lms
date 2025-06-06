<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Constants\UserRole;

class AuthParent
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->utype === UserRole::PARENT) {
            return $next($request);
        }

        return redirect()->route('login')->with('error', 'Chỉ phụ huynh mới có thể truy cập');
    }
}
