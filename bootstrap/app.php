<?php

use App\Exceptions\AppException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->dontReport([
            AppException::class,
        ]);

        /* $exceptions->render(function (Throwable $e, Request $request) {
             $render = parent::render($request, $e);
             $status = $render->getStatusCode();

             // 419 error
             // Useful when deploying new versions
             if ($status == 419) {
                 return response()->json(['status' => 419], 500);
             }

             // On this specific scenarios we want to use default Laravel render
             // Notice `AppException` has a custom render
             if (app()->environment() == 'local' || $e instanceof AuthenticationException || $e instanceof AppException) {
                 return $render;
             }

             // Afterward this point it displays a custom nice error view

             $title = match ($status) {
                 503 => 'Come back soon.',
                 500 => 'Something went wrong on our side.',
                 404 => 'Page not found.',
                 401 => 'Not authenticated.',
                 403 => 'Permission denied.',
                 default => 'Unknown error.'
             };

             return response()->view('errors.error', [
                 'isLivewire' => $request->hasHeader('X-Livewire'),
                 'title' => $title,
                 'detail' => $e->getMessage(),
             ], 500);
         });*/
    })->create();
