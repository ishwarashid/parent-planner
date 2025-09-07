<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminMiddleware
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
            if (Auth::user()->is_admin) {
                // If user is admin, and the route is not an admin route and not profile route, redirect to admin dashboard
                if (!Str::startsWith($request->path(), 'admin') && !Str::startsWith($request->path(), 'profile')) {
                    return redirect('/admin');
                }
            } else {
                // If user is not admin, and the route is an admin route, redirect to dashboard
                if (Str::startsWith($request->path(), 'admin')) {
                    return redirect('/dashboard');
                }
            }
        } else {
            // If user is not authenticated, and the route is an admin route, redirect to login
            if (Str::startsWith($request->path(), 'admin')) {
                return redirect('/login');
            }
        }

        return $next($request);
    }
}