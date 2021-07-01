<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use BadMethodCallException;
use App\Traits\ApiResponser;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    use ApiResponser;
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
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($request->wantsJson()) {

            if ($exception instanceof NotFoundHttpException) {
                return $this->error('Route not found', null, 404);
            }

            if ($exception instanceof ModelNotFoundException) {
                return $this->error($exception->getMessage(), null, 404);
            }

            if ($exception instanceof BadMethodCallException) {
                return $this->error($exception->getMessage(), null, 404);
            }

            if ($exception instanceof ValidationException) {
                $errors = $exception->errors();
                $first_error = reset($errors)[0];
                return $this->error($first_error, null, 501);
            }

            if ($exception instanceof \Twilio\Exceptions\RestException) {
                return $this->error($exception->getMessage(), null, 500);
            }
        }

        return parent::render($request, $exception);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return $this->error($exception->getMessage(), null, 401);
        }

        $guard = $exception->guards()[0];

        if ($guard == 'admin') {
            return redirect()->guest(route('admin.login'));
        }

        return redirect()->guest(route('login'));
    }
}
