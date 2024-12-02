<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule; 

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            '2fa' => \App\Http\Middleware\Require2FA::class,
        ]);
    })

    ->withSchedule(function (Schedule $schedule) {
        $schedule->command('posts:process-scheduled')->everyMinute();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();


