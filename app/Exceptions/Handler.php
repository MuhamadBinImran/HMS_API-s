<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\Request;
use App\Models\ErrorLog;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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

    /**
     * Log exceptions into the database and return the default response.
     */
    public function render($request, Throwable $exception)
    {
        try {
            ErrorLog::create([
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'message' => $exception->getMessage(),
            ]);
        } catch (\Throwable $e) {
            // Avoid logging failure if DB connection or table doesn't exist yet
        }

        return parent::render($request, $exception);
    }
}
