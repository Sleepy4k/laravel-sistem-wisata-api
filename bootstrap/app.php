<?php

use App\Facades\ApiResponse;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        apiPrefix: '',
        api: __DIR__.'/../routes/api.php',
        web: __DIR__.'/../routes/web.php',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->throttleApi();
        $middleware->append([
            \Spatie\Csp\AddCspHeaders::class,
            \App\Http\Middleware\AddSecureHeaderRequest::class,
        ]);
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);

        $middleware->redirectUsersTo(fn () => route('access.denied'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->respond(function (Response $response, Throwable $exception, Request $request) {
            return match (true) {
                $exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException,
                $exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException =>
                    ApiResponse::error('Resource not found', 404, [
                        'url' => $request->fullUrl(),
                        'method' => $request->method(),
                        'message' => $exception->getMessage(),
                    ]),

                $exception instanceof \Illuminate\Auth\Access\AuthorizationException =>
                    ApiResponse::error('Unauthorized', 403),

                $exception instanceof \Illuminate\Auth\AuthenticationException =>
                    ApiResponse::error('You are not authenticated', 401),

                $exception instanceof \Illuminate\Session\TokenMismatchException =>
                    ApiResponse::error('Session expired. Please refresh and try again.', 419),

                $exception instanceof \Illuminate\Validation\ValidationException =>
                    ApiResponse::error('Validation failed', 422, $exception->errors()),

                $exception instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException =>
                    ApiResponse::error('Method Not Allowed', 405),

                $exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException =>
                    ApiResponse::error($exception->getMessage() ?: 'HTTP Error', $exception->getStatusCode()),

                default => ApiResponse::error('An error occurred', 500),
            };
        });
    })->create();
