<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;

class Handler extends ExceptionHandler
{
    const NOT_FOUND_HTTP_CODE = 404;
    const METHOD_NOT_ALLOWED_HTTP_CODE = 405;

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

        if ($exception instanceof MethodNotAllowedHttpException) {
            return $this->renderResourceNotAllowed();
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
                'http_headers;url' =>
                    'Resource not found for this HTTP headers and URL'
            ]
        ], self::NOT_FOUND_HTTP_CODE);
    }

    /**
     * Render Resource Not found
     *
     * @return Response
     */
    private function renderResourceNotAllowed(): Response
    {
        return response()->json([
            'message' => 'Resource Not Allowed',
            'errors' => [
                'http_method;http_headers' =>
                    'Resource not allowed for this HTTP method and HTTP headers'
            ]
        ], self::METHOD_NOT_ALLOWED_HTTP_CODE);
    }
}
