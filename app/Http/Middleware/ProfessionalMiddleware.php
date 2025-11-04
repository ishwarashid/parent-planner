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
            $user = Auth::user();
            
            // Check if user has professional role (either basic role or Spatie role)
            $hasProfessionalRole = ($user->role === 'professional') || $user->hasRole('Professional');
            // Check if user has parent role (either basic role or Spatie role)
            $hasParentRole = in_array($user->role, ['parent', 'co-parent', 'nanny', 'grandparent', 'guardian', 'other']) ||
                           $user->hasAnyRole(['Main Parent', 'Invited User', 'Co-Parent']);
            
            // If user has professional role but is accessing a non-professional route
            if ($hasProfessionalRole && !Str::startsWith($request->path(), 'professional')) {
                // If user has BOTH professional and parent capabilities, allow access to non-professional routes
                if ($hasParentRole) {
                    // User has both capabilities - allow access to non-professional route
                } else {
                    // User is only professional, redirect to professional dashboard
                    return redirect('/professional/dashboard');
                }
            }
            // If user doesn't have professional role but is trying to access professional route
            else if (!$hasProfessionalRole && Str::startsWith($request->path(), 'professional')) {
                return redirect('/dashboard');
            }
            // If user has both roles and is accessing professional route, allow it
            else if ($hasProfessionalRole && $hasParentRole && Str::startsWith($request->path(), 'professional')) {
                // User has both capabilities - allow access to professional route
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
