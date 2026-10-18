<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\ContentManagerMiddleware;
use App\Http\Middleware\EnsureTwoFactorEnabled;
use App\Http\Middleware\RedirectManagerMiddleware;
use App\Http\Middleware\SecurityHeadersMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(RedirectManagerMiddleware::class);

        $middleware->web(append: [
            SecurityHeadersMiddleware::class,
        ]);

        $middleware->alias([
            'admin' => AdminMiddleware::class,
            'content.manager' => ContentManagerMiddleware::class,
            'two_factor' => EnsureTwoFactorEnabled::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
