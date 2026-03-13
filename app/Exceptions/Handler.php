<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception): Response
    {
        if ($exception instanceof AuthorizationException) {

            Log::channel('security')->warning('Unauthorized access attempt', [
                'user_id' => auth()->id(),
                'ip_address' => $request->ip(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
            ]);

            return response()->view('errors.403', [], 403);
        }

        return parent::render($request, $exception);
    }

}
