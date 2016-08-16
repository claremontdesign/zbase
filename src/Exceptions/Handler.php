<?php

namespace Zbase\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{

	/**
	 * A list of the exception types that should not be reported.
	 *
	 * @var array
	 */
	protected $dontReport = [
		AuthorizationException::class,
		HttpException::class,
		ModelNotFoundException::class,
		ValidationException::class,
	];

	/**
	 * Report or log an exception.
	 *
	 * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
	 *
	 * @param  \Exception  $e
	 * @return void
	 */
	public function report(\Exception $e)
	{
		if($e instanceof \Exception)
		{
			$error = $e->getMessage();
			if(preg_match('/failed to pass validation/', $error) == 0)
			{
				$error .= "\n";
				$error .= 'Date: ' . zbase_date_now()->format('Y-m-d h:i:s A') . "\n";
				$error .= 'URL: ' . zbase_url_uri() . "\n";
				$error .= 'Data: ' . json_encode(zbase_request_inputs()) . "\n";
				$error .= 'Routes: ' . json_encode(zbase_route_inputs()) . "\n";
				$error .= 'IP Address: ' . zbase_ip() . "\n";
				if(zbase_auth_has())
				{
					$user = zbase_auth_user();
					$error .= 'User: ' . $user->email() . ' ' . $user->username() . '[' . $user->id() . ']' . "\n";
				}
				zbase_messenger_email('dennes.b.abing@gmail.com', 'noreply', 'DermaSecrets.Biz Error', zbase_view_file_contents('email.exceptions'), ['error' => $error]);
			}
		}
		return parent::report($e);
	}

	/**
	 * Render an exception into an HTTP response.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Exception  $e
	 * @return \Illuminate\Http\Response
	 */
	public function render($request, Exception $e)
	{
		// return response()->view(zbase_view_file('errors.' . $e->getStatusCode()), compact('request', 'e'));
		return parent::render($request, $e);
	}

}
