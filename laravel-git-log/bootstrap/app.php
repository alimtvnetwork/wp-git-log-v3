<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'gl.lane-a' => \App\Http\Middleware\LaneAMiddleware::class,
            'gl.lane-b' => \App\Http\Middleware\LaneBMiddleware::class,
            'gl.permission' => \App\Http\Middleware\PermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Throwable $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                $code = 'GL-SYS-500';
                $severity = 'error';
                $statusCode = 500;
                $message = $e->getMessage() ?: 'Internal Server Error';

                if ($e instanceof \Illuminate\Validation\ValidationException) {
                    $code = 'GL-VAL-400';
                    $severity = 'warn';
                    $statusCode = 422;
                    $message = 'Validation failed';
                } elseif ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException || $e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                    $code = 'GL-REQ-404';
                    $severity = 'warn';
                    $statusCode = 404;
                    $message = 'Not Found';
                } elseif ($e instanceof \App\Exceptions\GlValidationException) {
                    $code = $e->errorCode;
                    $severity = 'error';
                    $statusCode = 400;
                    $message = $e->getMessage();
                }

                $error = new \App\Support\ErrorEnvelope(
                    code: $code,
                    message: $message,
                    severity: $severity,
                    timestamp: now()->toIso8601String(),
                );

                return \App\Support\ApiResponse::fail($error, $statusCode);
            }
        });
    })->create();
