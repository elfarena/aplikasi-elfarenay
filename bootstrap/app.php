<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use App\Http\Middleware\CustomAuth;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias(['auth.custom' => \App\Http\Middleware\CustomAuth::class]);

        // Percayai semua proxy (Railway, Nginx, dll) agar header HTTPS terbaca dengan benar
        $middleware->trustProxies(at: '*');
    })->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
