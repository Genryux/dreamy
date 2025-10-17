<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class EnhancedPinSecurity
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
        $ipAddress = $request->ip();
        $userAgent = $request->userAgent();

        // Check for suspicious activity patterns
        if ($this->isSuspiciousActivity($user, $ipAddress, $userAgent)) {
            Log::warning('Suspicious PIN activity detected', [
                'user_id' => $user->id,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'timestamp' => now()
            ]);
            
            // Force logout and redirect to login
            Auth::logout();
            $request->session()->invalidate();
            return redirect()->route('login')->with('error', 'Security violation detected. Please log in again.');
        }

        // Check if user has PIN setup
        if (!$user->pin) {
            if (!$request->routeIs('auth.pin.setup')) {
                return redirect()->route('auth.pin.setup');
            }
        } else {
            // User has PIN - check if it's enabled and verified
            if ($user->pin_enabled) {
                // Check if PIN has been verified in this session
                if (!session('pin_verified', false)) {
                    if (!$request->routeIs('auth.pin.verify')) {
                        return redirect()->route('auth.pin.verify');
                    }
                }
            }
        }

        return $next($request);
    }

    /**
     * Check for suspicious activity patterns
     */
    private function isSuspiciousActivity($user, $ipAddress, $userAgent): bool
    {
        $cacheKey = "pin_attempts_{$user->id}_{$ipAddress}";
        $attempts = Cache::get($cacheKey, 0);
        
        // If more than 5 failed attempts in 15 minutes from same IP
        if ($attempts >= 5) {
            return true;
        }

        // Check for rapid successive attempts (rate limiting)
        $rateLimitKey = "pin_rate_limit_{$user->id}_{$ipAddress}";
        $recentAttempts = Cache::get($rateLimitKey, []);
        
        // Remove attempts older than 1 minute
        $recentAttempts = array_filter($recentAttempts, function($timestamp) {
            return now()->diffInMinutes($timestamp) < 1;
        });
        
        // If more than 3 attempts in 1 minute
        if (count($recentAttempts) >= 3) {
            return true;
        }

        return false;
    }

    /**
     * Record failed PIN attempt
     */
    public static function recordFailedAttempt($userId, $ipAddress): void
    {
        $cacheKey = "pin_attempts_{$userId}_{$ipAddress}";
        $attempts = Cache::get($cacheKey, 0);
        Cache::put($cacheKey, $attempts + 1, now()->addMinutes(15));

        $rateLimitKey = "pin_rate_limit_{$userId}_{$ipAddress}";
        $recentAttempts = Cache::get($rateLimitKey, []);
        $recentAttempts[] = now();
        Cache::put($rateLimitKey, $recentAttempts, now()->addMinutes(1));
    }

    /**
     * Clear failed attempts on successful verification
     */
    public static function clearFailedAttempts($userId, $ipAddress): void
    {
        $cacheKey = "pin_attempts_{$userId}_{$ipAddress}";
        $rateLimitKey = "pin_rate_limit_{$userId}_{$ipAddress}";
        
        Cache::forget($cacheKey);
        Cache::forget($rateLimitKey);
    }
}
