<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LaneBMiddleware
{
    /**
     * Handle an incoming request.
     * Authenticate via TempToken or SSH validation (Lane B).
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        
        // Simple Lane B token validation (placeholder for SSH/10-step validation)
        if (!$token || strlen($token) < 10) {
            return response()->json([
                'ErrorCode' => 'GL-AUTH-INVALID-TOKEN',
                'TraceId' => (string) \Illuminate\Support\Str::uuid(),
                'Message' => 'Missing or invalid Lane B authentication token.',
                'Details' => []
            ], 401);
        }

        return $next($request);
    }
}
