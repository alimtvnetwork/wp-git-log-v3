<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LaneAMiddleware
{
    /**
     * Handle an incoming request.
     * Authenticate via Laravel Sanctum bearer token (Lane A).
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Mock Auth for Headless/Local Development
        if (app()->environment('local') && $request->hasHeader('X-Mock-Auth')) {
            // Bypass auth and act as a super admin
            return $next($request);
        }

        $user = $request->user('sanctum') ?? \Illuminate\Support\Facades\Auth::guard('sanctum')->user();

        if (! $user) {
            return \App\Support\ApiResponse::fail(new \App\Support\ErrorEnvelope('GL-AUTH-INVALID-TOKEN', 'Missing or invalid Lane A authentication token.', 'warn', now()->toIso8601String()), 401);
        }
        
        // Ensure request->user() returns the resolved user downstream
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        return $next($request);
    }
}
