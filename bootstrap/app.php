<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle 404 errors
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Page not found'], 404);
            }

            return app(\App\Http\Controllers\ErrorController::class)->notFound();
        });

        // Handle 403 errors
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Access denied'], 403);
            }

            return app(\App\Http\Controllers\ErrorController::class)->forbidden();
        });

        // Handle general 403 errors (abort(403))
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, $request) {
            if ($e->getStatusCode() === 403) {
                if ($request->expectsJson()) {
                    return response()->json(['error' => 'Access denied'], 403);
                }

                return app(\App\Http\Controllers\ErrorController::class)->forbidden();
            }
        });
    })->create();
