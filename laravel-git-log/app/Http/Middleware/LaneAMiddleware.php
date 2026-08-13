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
        if (! $request->user()) {
            return response()->json([
                'ErrorCode' => 'GL-AUTH-INVALID-TOKEN',
                'TraceId' => (string) \Illuminate\Support\Str::uuid(),
                'Message' => 'Missing or invalid Lane A authentication token.',
                'Details' => []
            ], 401);
        }

        return $next($request);
    }
}
