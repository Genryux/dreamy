<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BlockDesktopFromHomepage
{
    /**
     * Block desktop app users from accessing homepage
     */
    public function handle(Request $request, Closure $next)
    {
        $userAgent = $request->header('User-Agent');
        $isDesktop = str_contains($userAgent, 'Electron') || 
                     str_contains($userAgent, 'DreamyDesktopApp') ||
                     $request->hasHeader('X-Electron-App');
        
        if ($isDesktop) {
            // Redirect desktop users to login page
            if (!$request->expectsJson()) {
                return redirect()->route('login');
            }
            
            return response()->json([
                'error' => 'Homepage is not available for desktop application users.',
                'redirect' => route('login')
            ], 403);
        }
        
        return $next($request);
    }
}
