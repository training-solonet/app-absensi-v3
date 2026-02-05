<?php

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
            'ceklogin' => \App\Http\Middleware\CekLogin::class,
        ]);
    })
    ->withSchedule(function (Schedule $schedule) {
        // Run mark absent command every day at 23:00 (11 PM)
        $schedule->command('absensi:mark-absent')->dailyAt('23:00');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    });

// Register RouteServiceProvider
$app->withProviders([
    \App\Providers\RouteServiceProvider::class,
]);

return $app->create();
