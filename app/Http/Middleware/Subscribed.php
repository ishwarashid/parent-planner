<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class Subscribed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Log::info('Subscribed middleware check for user: ' . $request->user()->id . ' - subscribed: ' . ($request->user()->subscribed('default') ? 'yes' : 'no'));
        if ($request->user() && ! $request->user()->subscribed('default')) {
            // Redirect user to pricing page if they are not subscribed.
            return redirect()->route('pricing');
        }

        return $next($request);
    }
}
