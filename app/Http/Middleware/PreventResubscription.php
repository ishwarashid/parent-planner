<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventResubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && $request->user()->subscribed()) {

            // User is subscribed, so redirect them to a more appropriate page.
            // The dashboard is a good default.
            return redirect()->route('dashboard')
                ->with('status', 'You are already a subscriber.');
        }

        // If the user is not subscribed, allow them to proceed.
        return $next($request);
    }
}
