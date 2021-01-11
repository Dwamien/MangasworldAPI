<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;

class Handler extends ExceptionHandler {

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
    public function report(Exception $exception) {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception) {
        $typeException = get_class($exception);
        if (($typeException == "Symfony\Component\HttpKernel\Exception\NotFoundHttpException") || ($typeException == "Symfony\Component\Debug\Exception\FatalThrowableError")) {
            return response()->json("Ressource inexistante !", 404);
        }
        return parent::render($request, $exception);
    }

    /**
     * Redéfinition de la méthode unauthenticated
     * @param type $request
     * @param AuthenticationException $exception
     * @return type
     */
    protected function unauthenticated($request, AuthenticationException $exception) {
        return response()->json("Accès non autorisé !", 401);
    }

}
