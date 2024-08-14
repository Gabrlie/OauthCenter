<?php

use App\Http\Middleware\AdminAuthenticateApi;
use App\Http\Middleware\AuthenticateApi;
use App\Http\Middleware\DeveloperAuthenticateApi;
use App\Http\Middleware\VerifyCaptcha;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        //
        $middleware->alias([
            'adminAuth' => AdminAuthenticateApi::class,
            'developerAuth' => DeveloperAuthenticateApi::class,
            'myAuth' => AuthenticateApi::class,
            'verify.captcha' => VerifyCaptcha::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
