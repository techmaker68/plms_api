<?php

namespace App\Exceptions;

use Throwable;
use App\Utils\HttpStatusCode;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use League\OAuth2\Server\Exception\OAuthServerException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Spatie\Permission\Exceptions\UnauthorizedException;


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

    /**
     * Render an exception into an HTTP response.
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     */
    public function render($request, Throwable $exception){
        if ($request->is('api/*')) {
            if ($exception instanceof UnauthorizedException) {
                return response()->json([
                    'error' => true,
                    'statusText' => HttpStatusCode::$statusTexts[HttpStatusCode::FORBIDDEN],
                    'message' => 'You do not have the required permissions to perform this action.',
                    'code' => HttpStatusCode::FORBIDDEN,
                ], HttpStatusCode::FORBIDDEN);
            }
            elseif ($exception instanceof \Illuminate\Validation\ValidationException) {
                $errors = [];
                foreach ($exception->errors() as $key => $error) {
                    $errors[$key] = $error[0];
                }
                logger([
                    'error' => true,
                    'message' => $exception->getMessage(),
                    'line' => $exception->getLine(),
                    'file' => $exception->getFile(),
                    'data' => $errors
                ]);
                return response()->json([
                    'error' => true,
                    'statusText' => HttpStatusCode::$statusTexts[HttpStatusCode::UNPROCESSABLE_ENTITY], 
                    'message' => $errors,
                    'code' => HttpStatusCode::UNPROCESSABLE_ENTITY,
                ], HttpStatusCode::UNPROCESSABLE_ENTITY);
            } elseif ($exception instanceof ModelNotFoundException) {
                return response()->json([
                    'error' => true,
                    'statusText' => HttpStatusCode::$statusTexts[HttpStatusCode::NOT_FOUND],
                    "message" => "Data not found",
                    'code' => HttpStatusCode::NOT_FOUND,
                ], HttpStatusCode::NOT_FOUND);
            } elseif ($exception instanceof AuthenticationException || $exception instanceof OAuthServerException || $exception instanceof AccessDeniedHttpException) {
                return response()->json([
                    'error' => true,
                    'statusText' => HttpStatusCode::$statusTexts[HttpStatusCode::UNAUTHORIZED],
                    "message" => "Unauthroized url",
                    'code' => HttpStatusCode::UNAUTHORIZED,
                ], HttpStatusCode::UNAUTHORIZED);
            } elseif ($exception instanceof NotFoundHttpException) {
                return response()->json([
                    'error' => true,
                    'statusText' => HttpStatusCode::$statusTexts[HttpStatusCode::NOT_FOUND],
                    "message" => "Route not found",
                    'code' => HttpStatusCode::NOT_FOUND,
                ], HttpStatusCode::NOT_FOUND);
            } elseif ($exception instanceof MethodNotAllowedHttpException) {
                return response()->json([
                    'error' => true,
                    'statusText' => HttpStatusCode::$statusTexts[HttpStatusCode::METHOD_NOT_ALLOWED],
                    "message" => "Methode not allowed",
                    'code' => HttpStatusCode::METHOD_NOT_ALLOWED,
                ], HttpStatusCode::METHOD_NOT_ALLOWED);
            } elseif ($exception instanceof RouteNotFoundException) {
                return response()->json([
                    'error' => true,
                    'statusText' => HttpStatusCode::$statusTexts[HttpStatusCode::NOT_FOUND],
                    "message" => "Route not found",
                    'code' => HttpStatusCode::NOT_FOUND,
                ], HttpStatusCode::NOT_FOUND);
            }
        }
        return parent::render($request, $exception);
    }
}
