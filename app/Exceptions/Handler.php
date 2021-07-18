<?php

namespace App\Exceptions;

use Exception;
use Throwable;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Http\Request;

class Handler extends ExceptionHandler
{
    //when enabled show actual sql error messages and trace string for all exception
    const TRACE = true;

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param \Throwable $e
     *
     * @return void
     */
    public function report(Throwable $e)
    {

        //parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception               $e
     *
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $e)
    {
        $message = $e->getMessage() . ' in file ' . $e->getFile() . ' at line ' . $e->getLine();

        switch (get_class($e)) {

            case 'Symfony\Component\HttpKernel\Exception\NotFoundHttpException':
                try {
                    throw new ApiException('client', 404, 'not_found_http', '', 0);
                } catch (Exception $e) {
                    $exceptionData = self::getExceptionData($request, $e);
                }
                break;

            case 'PDOException':
            case 'Illuminate\Database\QueryException':
                try {
                    throw new DbException($e);
                } catch (Exception $e) {
                    $exceptionData = self::getExceptionData($request, $e);
                }
                break;

            case 'Symfony\Component\Debug\Exception\FatalErrorException':
                try {
                    throw new ApiException('semantic', 500, 'semantic_error', '', 0, ['message' => $message]);
                } catch (Exception $e) {
                    $exceptionData = self::getExceptionData($request, $e);
                }
                break;


            case 'App\Exceptions\ApiException':
                $exceptionData = self::getExceptionData($request, $e);
                break;


            case 'Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException':
                try {
                    throw new ApiException('client', 405, 'method_not_allowed', '', 0);
                } catch (Exception $e) {
                    $exceptionData = self::getExceptionData($request, $e);
                }
                break;

            default:
                if (get_parent_class($e) == 'App\Exceptions\ApiException') {
                    $exceptionData = self::getExceptionData($request, $e);
                    break;
                }

                try {
                    throw new ApiException('server', 500, 'internal_server_error', '', 0, ['message' => $message]);
                } catch (Exception $e) {
                    $exceptionData = self::getExceptionData($request, $e);
                }
                break;
        }

        return response()->json($exceptionData, $e->getStatusCode(), [], JSON_PRETTY_PRINT);
    }

    /**
     * create response exception from excpetion object.
     *
     * @author Andy
     *
     * @param Illuminate\Http\Request $request
     * @param \Exception              $e
     *
     * @return array
     */
    protected static function getExceptionData(Request $request, Exception $e)
    {
        $exceptionData = [];
        // $exceptionData['data'] = [];
        $exceptionData['status'] = $e->getStatusCode();
        $exceptionData['success'] = $e->getSuccessCode();
        $exceptionData['message'] = $e->getMessage();

        if (config('app.debug')) {
            $exception['type'] = $e->getType(); //ApiException::$exceptionTypes[$httpStatusCode];
            $exception['group'] = $e->getGroup(); // $exceptionGroup;
            $exception['status'] = $e->getStatusText(); //$httpStatusText;
            $exception['method'] = $request->method();
            $exception['link'] = $request->url(); //'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            $query = ($request->method() == 'GET') ? $request->getQueryString() : http_build_query($request->request->all());
            $exception['query'] = empty($query) ? '' : $query;
            $exception['datetime'] = date('YmdHis');
            $details = $e->getDetails();
            if (!empty($details)) {
                $exception['details'] = $details;
            }

            if (self::TRACE) {
                $exception['details']['trace'] = $e->getTraceAsString();
            }

            $exceptionData['data']['exception'] = $exception;
        }

        return $exceptionData;
    }
}
