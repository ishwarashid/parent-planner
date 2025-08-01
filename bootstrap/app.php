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
            'subscribed' => \App\Http\Middleware\Subscribed::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'professional' => \App\Http\Middleware\ProfessionalMiddleware::class,
            'parent' => \App\Http\Middleware\ParentMiddleware::class,
            'dashboard.access' => \App\Http\Middleware\DashboardAccessMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
