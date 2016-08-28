<?php

namespace Zbase\Exceptions;

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
				zbase_messenger_error(['error' => $error]);
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
	public function render($request, \Exception $e)
	{
		if(zbase_route_username())
		{
			$uri = trim(zbase_url_uri(), '/');
			if(!empty($uri))
			{
				/**
				 * instances like:
				 * 	dxenns/dxenns/login
				 * 	username/username/login
				 */
				$uriEx = explode('/', $uri);
				if(!empty($uriEx))
				{
					$fU = null;
					foreach ($uriEx as $u)
					{
						if($fU == $u)
						{
							$hasSame = true;
							break;
						}
						$fU = $u;
					}
				}
				if(!empty($hasSame))
				{
					$url = str_replace($fU . '/' . $fU, '/' . $fU, $uri);
					header('location:'  . $url, true, 301);
					exit();
				}
			}
		}
		return parent::render($request, $e);
	}

}
