<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProfessionalMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            if (Auth::user()->role === 'professional') {
                // If user is professional, and the route is not a professional route, redirect to professional dashboard
                if (!Str::startsWith($request->path(), 'professional')) {
                    return redirect('/professional/dashboard');
                }
            } else {
                // If user is not professional, and the route is a professional route, redirect to dashboard
                if (Str::startsWith($request->path(), 'professional')) {
                    return redirect('/dashboard');
                }
            }
        } else {
            // If user is not authenticated, and the route is a professional route, redirect to login
            if (Str::startsWith($request->path(), 'professional')) {
                return redirect('/login');
            }
        }

        return $next($request);
    }
}
