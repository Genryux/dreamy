<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Allow access to verification-related routes
        if ($request->routeIs('verification.*')) {
            return $next($request);
        }

        if (!$request->user() || !$request->user()->hasVerifiedEmail()) {
            // Log unverified access attempt
            \Log::warning('Unverified email access attempt', [
                'user_id' => $request->user()?->id,
                'email' => $request->user()?->email,
                'route' => $request->route()?->getName(),
                'ip' => $request->ip(),
                'timestamp' => now()
            ]);

            return redirect()->route('verification.notice')
                ->with('error', 'Please verify your email address before accessing this page.');
        }

        return $next($request);
    }
}
