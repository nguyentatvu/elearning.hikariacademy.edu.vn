<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        if ($exception instanceof RedirectException) {
            return;
        }
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($request->is('api/*')) {
            if ($exception instanceof AuthenticationException) {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }

            if ($exception instanceof \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException) {
                return response()->json(['error' => $exception->getMessage()], 401);
            }

            if ($exception instanceof NotFoundHttpException) {
                return response()->json(['error' => 'Not found'], 404);
            }

            if ($exception instanceof MethodNotAllowedHttpException) {
                return response()->json(['error' => 'Method not allowed'], 405);
            }

            if ($exception instanceof Exception) {
                app_log()->error($exception->getMessage(), [
                    'http_status' => 500,
                    'url' => $request->fullUrl(),
                    'method' => $request->method()
                ]);

                return response()->json([
                    'error' => 'Something went wrong.'
                ], 500);
            }
        }

        if ($exception instanceof RedirectException) {
            return $exception->getResponse();
        }

        return parent::render($request, $exception);
    }
}
