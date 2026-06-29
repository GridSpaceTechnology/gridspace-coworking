<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOnboardingComplete
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || $user->isAdmin() || $user->onboarding_step >= 4) {
            return $next($request);
        }

        if ($request->routeIs('onboarding.*') || $request->routeIs('logout')) {
            return $next($request);
        }

        return redirect()->route(match (true) {
            $user->onboarding_step < 1 => 'onboarding.step1',
            $user->onboarding_step < 2 => 'onboarding.step2',
            $user->onboarding_step < 3 => 'onboarding.step3',
            default => 'onboarding.step4',
        });
    }
}
