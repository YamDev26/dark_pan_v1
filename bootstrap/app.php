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
        $middleware->appendToGroup('SuperAdmin', [App\Http\Middleware\henshawUserHasAdmin::class]);
        $middleware->appendToGroup('UserAutres', [App\Http\Middleware\henshawUserHasAutres::class]);
        $middleware->appendToGroup('UserEnseigment', [App\Http\Middleware\hensHawUserHasEnseigment::class]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
