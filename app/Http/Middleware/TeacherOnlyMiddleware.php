<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TeacherOnlyMiddleware
{
    /**
     * Handle an incoming request.
     * Ensures only teachers can access teacher API endpoints.
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
        
        // Check if user is a teacher
        if (!$user->teacher) {
            return response()->json([
                'message' => 'Access denied. This endpoint is only available for teachers.'
            ], 403);
        }

        // Check if teacher account is active
        if (!$user->teacher->isActive()) {
            return response()->json([
                'message' => 'Your teacher account is not active. Please contact administration.'
            ], 403);
        }
        
        return $next($request);
    }
}
