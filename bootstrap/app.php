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
        
        // تسجيل الـ Middleware الخاص بالأدوار (Role Manager)
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleManager::class,
        ]);

    })
    ->withSchedule(function ($schedule) {
        $schedule->command('reminder:send')->dailyAt('09:00');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();