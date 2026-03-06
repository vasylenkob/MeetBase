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
            'role'      => \App\Http\Middleware\EnsureRole::class,
            'not.blocked' => \App\Http\Middleware\CheckNotBlocked::class,
        ]);
        // Перевірка бану для всіх auth-маршрутів
        $middleware->appendToGroup('web', \App\Http\Middleware\CheckNotBlocked::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
