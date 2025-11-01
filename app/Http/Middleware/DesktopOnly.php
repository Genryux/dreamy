<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DesktopOnly
{
    /**
     * Block access from web browsers - desktop app only
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->is_desktop) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'This feature is only available on the desktop application.',
                    'platform' => 'web',
                    'message' => 'Please use the Dreamy School Management desktop app to access this feature.'
                ], 403);
            }
            
            // Redirect authenticated admin users to web-admin-message page
            if (auth()->check() && auth()->user()->hasRole(['registrar', 'super_admin'])) {
                return redirect()->route('web.admin.message');
            }
            
            // For non-authenticated or other users, show error page
            return response()->view('errors.desktop-only', [
                'message' => 'This feature is only available on the desktop application.',
                'suggestion' => 'Please download and install the Dreamy School Management desktop app to access administrative features.'
            ], 403);
        }
        
        return $next($request);
    }
}
