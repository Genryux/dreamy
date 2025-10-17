<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckPinSecurity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Skip PIN checks for guest users
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // Skip PIN checks for PIN-related routes to avoid redirect loops
        if ($this->isPinRelatedRoute($request)) {
            return $next($request);
        }

        // Skip PIN checks for logout route
        if ($request->routeIs('logout')) {
            return $next($request);
        }

        // Log middleware execution for debugging
        \Log::info('PIN Security Middleware executing', [
            'user_id' => $user->id,
            'route' => $request->route()?->getName(),
            'url' => $request->url(),
            'pin_exists' => !is_null($user->pin),
            'pin_enabled' => $user->pin_enabled,
            'pin_verified' => Session::get('pin_verified', false),
            'timestamp' => now()
        ]);
        

        // Check if user has PIN setup
        if (!$user->pin) {
            // User doesn't have a PIN - redirect to setup
            \Log::info('User has no PIN, redirecting to setup', [
                'user_id' => $user->id,
                'route' => $request->route()?->getName()
            ]);
            return redirect()->route('auth.pin.setup');
        } else {
            // User has PIN - check if it's enabled and verified
            if ($user->pin_enabled) {
                // Check if PIN has been verified in this session
                $pinVerified = Session::get('pin_verified', false);
                $pinVerifiedAt = Session::get('pin_verified_at');
                
                // Check if PIN verification has expired (30 minutes)
                if ($pinVerified && $pinVerifiedAt) {
                    $expiryTime = $pinVerifiedAt->addMinutes(30);
                    if (now()->gt($expiryTime)) {
                        // PIN verification expired
                        Session::forget(['pin_verified', 'pin_verified_at']);
                        $pinVerified = false;
                        
                        \Log::info('PIN verification expired', [
                            'user_id' => $user->id,
                            'ip_address' => $request->ip(),
                            'timestamp' => now()
                        ]);
                    }
                }
                
                if (!$pinVerified) {
                    // PIN is enabled but not verified or expired - redirect to verification
                    \Log::warning('PIN not verified, redirecting to verification', [
                        'user_id' => $user->id,
                        'route' => $request->route()?->getName(),
                        'url' => $request->url(),
                        'pin_verified' => $pinVerified,
                        'timestamp' => now()
                    ]);
                    return redirect()->route('auth.pin.verify');
                }
            }
        }

        return $next($request);
    }

    /**
     * Check if the current route is PIN-related to avoid redirect loops
     */
    private function isPinRelatedRoute(Request $request): bool
    {
        $pinRoutes = [
            'auth.pin.setup',
            'auth.pin.setup.store',
            'auth.pin.verify',
            'auth.pin.verify.store',
            'profile.pin.setup',
            'profile.pin.update',
            'profile.pin.enable',
            'profile.pin.disable',
        ];

        return $request->routeIs($pinRoutes);
    }
}
