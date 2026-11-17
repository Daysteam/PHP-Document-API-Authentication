<?php

use App\Http\Middleware\RoleMiddleware;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Str;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(fn(Request $request) => $request->is('api/*') ? null : route('login'));
        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        // 401: Unauthorised
        $exceptions->render(function(AuthenticationException $e, Request $request){
            if($request->is('api/*')){
                return response()->json([
                    'success' => false,
                    'code' => 401,
                    'message' => 'Anda belum login'
                ],401);
            }
        });

        // 404: Model atau route tidak ditemukan
        $exceptions->render(function(NotFoundHttpException $e, Request $request){
            if($request->is('api/*')){
                if($e->getPrevious() instanceof ModelNotFoundException){
                    $modelName = Str::lower(
                        class_basename($e->getPrevious()->getModel())
                    );

                    return response()->json([
                        'success' => false,
                        'code' => 404,
                        'message' => "Data model $modelName tidak ditemukan"
                    ],404);
                }

                return response()->json([
                    'success' => false,
                    'code' => 404,
                    'message' => "Route tidak ditemukan"
                ],404);
            }
        });
    })->create();
