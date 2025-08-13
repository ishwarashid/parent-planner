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
        $allowedRoles = ['parent', 'co-parent', 'nanny', 'guardian', 'grandparent', 'professional', 'other'];

        if (Auth::check() && in_array(Auth::user()->role, $allowedRoles)) {
            return $next($request);
        }

        return redirect('/login')->with('error', 'You do not have permission to access this page.');
    }
}
