<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     * Authorize by looking up RolePermission.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();
        
        // Example permission check for Lane A admins
        if ($user && !in_array($permission, $user->permissions ?? [])) {
            return response()->json([
                'ErrorCode' => 'GL-AUTH-INSUFFICIENT-PERMISSIONS',
                'TraceId' => (string) \Illuminate\Support\Str::uuid(),
                'Message' => "Missing required permission: {$permission}",
                'Details' => []
            ], 403);
        }

        return $next($request);
    }
}
