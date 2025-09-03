<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DashboardAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Define roles that can access the dashboard
        $allowedRoles = ['Main Parent', 'Co-Parent', 'Invited User', 'Professional'];

        if (Auth::check() && Auth::user()->hasRole($allowedRoles)) {
            return $next($request);
        }

        return redirect('/login')->with('error', 'You do not have permission to access this page.');
    }
}
