<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StudentOnlyMiddleware
{
    /**
     * Handle an incoming request.
     * Ensures only students can access mobile API endpoints.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        // Check if user is authenticated
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        
        // Check if user is a student
        if (!$user->student) {
            return response()->json([
                'message' => 'Access denied. Mobile app is only available for enrolled students.'
            ], 403);
        }
        
        return $next($request);
    }
}
