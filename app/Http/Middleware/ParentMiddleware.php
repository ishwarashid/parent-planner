<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ParentMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Check basic role or Spatie roles for parent capabilities
            $hasParentRole = in_array($user->role, ['parent', 'co-parent', 'nanny', 'grandparent', 'guardian', 'other']) ||
                           $user->hasAnyRole(['Main Parent', 'Invited User', 'Co-Parent']);
            
            if ($hasParentRole) {
                return $next($request);
            }
        }

        return redirect('/login')->with('error', 'You do not have permission to access this page.');
    }
}