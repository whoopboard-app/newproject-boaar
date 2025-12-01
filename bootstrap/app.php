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
        // Global middleware for subdomain detection and site access checking
        $middleware->web(append: [
            \App\Http\Middleware\SubdomainRouting::class,
            \App\Http\Middleware\CheckSiteAccess::class,
        ]);

        $middleware->alias([
            'team.permission' => \App\Http\Middleware\CheckTeamPermission::class,
            'team.subdomain' => \App\Http\Middleware\DetectTeamSubdomain::class,
        ]);

        // Exclude public survey API routes from CSRF verification
        $middleware->validateCsrfTokens(except: [
            'api/survey/*/config',
            'api/survey/*/respond',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
