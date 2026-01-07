<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $permission
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        // Check if user is authenticated
        if (!$request->user()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthenticated'
            ], 401);
        }

        $user = $request->user();

        // Check if user has the required permission
        if (!$user->hasPermissionTo($permission, 'api')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. You do not have permission: ' . $permission
            ], 403);
        }

        return $next($request);
    }
}
