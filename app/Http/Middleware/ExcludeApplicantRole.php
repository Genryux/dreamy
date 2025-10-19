<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class ExcludeApplicantRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();
        
        // Check if user has applicant role
        $hasApplicantRole = $user->hasRole('applicant') || 
                           $user->getRoleNames()->contains('applicant') ||
                           $user->roles->where('name', 'applicant')->isNotEmpty();

        // If user has applicant role, check if they're trying to access admission routes
        if ($hasApplicantRole) {
            $currentPath = $request->path();
            
            // Allow access to admission-related routes
            $allowedPaths = [
                'admission',
                'admission/',
                'admission/status',
                'submit-document',
                'email/verify',
                'email/verify/success',
                'email/verify/failed',
                'email/verify/already-verified',
                'verification.notice',
                'verification.send',
                'logout',
                'pin/setup',
                'pin/verify',
                'profile',
                'portal/login',
                'portal/register'
            ];

            // Check if current path starts with any allowed path
            $isAllowed = false;
            foreach ($allowedPaths as $allowedPath) {
                if (str_starts_with($currentPath, $allowedPath) || $currentPath === $allowedPath) {
                    $isAllowed = true;
                    break;
                }
            }

            // If not allowed, redirect to admission dashboard
            if (!$isAllowed) {
                \Log::info('Applicant role blocked from accessing non-admission route', [
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'attempted_path' => $currentPath,
                    'full_url' => $request->fullUrl(),
                    'timestamp' => now()
                ]);

                return redirect()->route('admission.dashboard')
                    ->with('error', 'You do not have permission to access this area. You can only access the admission portal.');
            }
        }

        return $next($request);
    }
}
