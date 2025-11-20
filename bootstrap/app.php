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
        // Add global security headers middleware
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);
        
        // Rate limiting aliases already configured by default
        // throttle middleware available as 'throttle:60,1' etc
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
