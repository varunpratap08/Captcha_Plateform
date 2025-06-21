<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'jwt.admin' => \App\Http\Middleware\JwtAdminMiddleware::class,
            'auth.jwt' => \App\Http\Middleware\AuthenticateWithJwt::class,
        ]);

        // We'll apply the auth.jwt middleware to specific routes instead of globally
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
