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
        // Mock Auth for Headless/Local Development
        if (app()->environment('local') && $request->hasHeader('X-Mock-Auth')) {
            // Bypass auth and act as a super admin
            return $next($request);
        }

        $user = $request->user();
        
        // This is a simplified check since we are running headless without WP
        // In full WP integration, this would query the AccessToRole and RolePermission tables.
        if ($user && !in_array($permission, $user->permissions ?? [])) {
            return \App\Support\ApiResponse::fail(new \App\Support\ErrorEnvelope('GL-AUTHZ-PERMISSION-DENIED', "Missing required permission: {$permission}", 'error', now()->toIso8601String()), 403);
        }

        return $next($request);
    }
}
