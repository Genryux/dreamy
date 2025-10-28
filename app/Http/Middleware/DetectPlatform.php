<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DetectPlatform
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
        $userAgent = $request->header('User-Agent');
        
        // Detect if request is from Electron desktop app
        $isElectron = str_contains($userAgent, 'Electron') || 
                      str_contains($userAgent, 'DreamyDesktopApp') ||
                      $request->hasHeader('X-Electron-App');
        
        // Add platform info to request for easy access
        $request->merge([
            'is_desktop' => $isElectron,
            'is_web' => !$isElectron,
            'platform' => $isElectron ? 'desktop' : 'web'
        ]);
        
        return $next($request);
    }
}
