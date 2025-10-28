<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class WebOnly
{
    /**
     * Block access from desktop app - web browsers only
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->is_desktop) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'This feature is only available on the web version.',
                    'platform' => 'desktop',
                    'message' => 'Please use the web browser to access this feature.'
                ], 403);
            }
            
            return response()->view('errors.web-only', [
                'message' => 'This feature is only available on the web version.',
                'suggestion' => 'Please open your web browser and visit the website to access this feature.'
            ], 403);
        }
        
        return $next($request);
    }
}
