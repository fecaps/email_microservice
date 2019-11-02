<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;

class Handler extends ExceptionHandler
{
    const DEFAULT_NOT_FOUND_HTTP_CODE = 404;

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
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof NotFoundHttpException) {
            return $this->renderResourceNotFound();
        }

        if ($exception instanceof ModelNotFoundException) {
            return $this->renderResourceNotFound();
        }

        if ($exception instanceof  MethodNotAllowedHttpException) {
            return $this->renderResourceNotFound();
        }

        return parent::render($request, $exception);
    }

    /**
     * Render Resource Not found
     *
     * @return Response
     */
    private function renderResourceNotFound(): Response
    {
        return response()->json([
            'message' => 'Resource Not Found',
            'errors' => [
                'http_method;http_headers;http_url' =>
                    'Resource not found for this HTTP method, headers and url'
            ]
        ], self::DEFAULT_NOT_FOUND_HTTP_CODE);
    }
}
