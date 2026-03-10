<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class HostMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && (Auth::user()->isHost() || Auth::user()->isAdmin())) {
            // Allow access to dashboard for unapproved hosts, but block other actions
            if ($request->route()->getName() === 'dashboard' && Auth::user()->isHost() && !Auth::user()->isApproved()) {
                return $next($request);
            }

            // Require approval for all other host routes
            if ((Auth::user()->isHost() && Auth::user()->isApproved()) || Auth::user()->isAdmin()) {
                return $next($request);
            }
        }

        return redirect()->route('login')->with('error', 'Access denied. Host privileges required.');
    }
}
