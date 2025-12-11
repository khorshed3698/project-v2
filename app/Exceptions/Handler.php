<?php

namespace App\Exceptions;

use App\Libraries\UtilFunction;
use App\Modules\Apps\Models\EmailQueue;
use DateTime;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\Debug\Exception\FatalErrorException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
        ModelNotFoundException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param Exception $e
     * @return void
     */
    public function report(Exception $e)
    {
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param Exception $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if (
            config('app.server_type') == 'LIsVE'
        ) {
            $getCode = $e->getCode();
            $message = $e->getMessage();
            $getFile = $e->getFile();
            $getLine = $e->getLine();
            $getUrl = URL::current();

            $body_msg = '<span style="color:black;text-align:justify;"><b>';
            $body_msg .= '<b><h3>Error details:</h3><br>Date Time: </b>'.(new DateTime())->format("d-M-Y H:i:s").'<br>
            <b>Project Name: </b>'.config('app.project_name').'<br><b>Url: </b>'.$getUrl.'<br><b>Error Messages:</b> '.$message.'<br>
            <b>Error File:</b> '.$getFile.'<br><b>Error Line:</b> '.$getLine.'<br><b>Error Code: </b>'.$getCode.
                $body_msg .= '</span>';
            $body_msg .= '<br/><br/><br/>Thanks<br/>';
            $body_msg .= '<b>'.config('app.project_name').'</b>';

            $header = 'Error log details from BIDA ' . config('app.server_type') . ' server';
            $param = $body_msg;
            $email_content = view("Users::message", compact('header', 'param'))->render();

            $emailQueue = new EmailQueue();
            $emailQueue->service_id = 0;
            $emailQueue->app_id = 0;
            $emailQueue->email_content = $email_content;
            $emailQueue->email_to = "ossbida.ocpl@gmail.com";
            $emailQueue->sms_to =  "";
            $emailQueue->email_subject = $header;
            $emailQueue->attachment = '';
            $emailQueue->save();

            return response()->view('errors.custom');
        }

        if ($e instanceof NotFoundHttpException and !Auth::user()) {
            $status = $e->getStatusCode();
            return response()->view('errors.page-not-found', compact('status'));
        }

        if ($e instanceof ModelNotFoundException) {
            $e = new NotFoundHttpException($e->getMessage(), $e);
        }

        if ($e instanceof TokenMismatchException) {
            return redirect()->away(UtilFunction::logoutFromKeyCloak());
        }

        if ($e instanceof \PDOException) {
            $message ='Looks like something went wrong.';
            return $this->renderDynamicError($e ,$message , $request);
        }

        if ($e instanceof \ErrorException) {
            $message ='Looks like something went wrong.';
            return $this->renderDynamicError($e ,$message , $request);
        }

        if ($e instanceof FatalErrorException) {
            $message ='Looks like something went wrong.';
            return $this->renderDynamicError($e ,$message , $request);
        }


        return parent::render($request, $e);
    }// end -:- render()

    /**
     * Render dynamic error response.
     *
     * @param Exception $e
     * @param string $message
     * @return Response
     */
    private function renderDynamicError($e , $message , $request)
    {
        $error = [
            'code' => $e->getCode(),
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'url' => URL::current()
        ];

        Log::error($error);

        if(env('APP_DEBUG' , false)){
            return parent::render($request, $e);
        }

        $layout = auth()->check() ? 'layouts.admin' : 'layouts.front';
        $redirect =  auth()->check() ? 'dashboard' : '/';

        return response()->view('errors.dynamic-error',[
            'message' => $message ,
            'redirect'  => $redirect,
            'layout'  => $layout,
        ]);
    }// end -:- renderDynamicError()
}// end -:- Handler Class