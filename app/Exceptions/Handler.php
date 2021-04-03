<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

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
        $this->renderable(function(Exception $e) {

            if ($e instanceof ValidationException){
                return $this->validationError($e);
            }

            if ($e instanceof NotFoundHttpException){
                return $this->notFoundError();
            }

            if ($e instanceof UnauthorizedHttpException){
                return $this->unAuthorizeError();
            }

            if ($e instanceof MethodNotAllowedHttpException){
                return $this->methodNotAllowedError();
            }

            return $this->debugErrors($e->getMessage());
        });
    }

    private function debugErrors($e){
        return response()->json([
            'success' => false,
            'code' => 500,
            'message' => $e,
            'data' => null
        ]);
    }

    private function methodNotAllowedError(){
        return response()->json([
            'success' => false,
            'code' => 405,
            'message' => 'Method not allowed',
            'data' => null
        ]);
    }

    private function unAuthorizeError(){
        return response()->json([
            'success' => false,
            'code' => 403,
            'message' => 'UnAuthorized',
            'data' => null
        ]);
    }

    private function notFoundError(){
        return response()->json([
            'success' => false,
            'code' => 404,
            'message' => 'Not found',
            'data' => null
        ]);
    }

    private function validationError($e){
        return response()->json([
            'success' => false,
            'code' => 400,
            'message' => $this->errorValidation($e)[0],
            'data' => null
        ]);
    }

    private function errorValidation($exception)
    {
        $errors = collect($exception->errors());
        return $errors->unique()->first();
    }
}
