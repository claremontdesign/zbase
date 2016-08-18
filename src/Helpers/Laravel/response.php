<?php

/**
 * Zbase-Laravel Helpers-Response
 *
 * Functions and Helpers for Response
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file response.php
 * @project Zbase
 * @package Zbase/Laravel/Helpers
 */

/**
 * Return the Response
 * @param mixed $response
 * @return mixed
 */
function zbase_response($response)
{
	// HTTP/1.1 204 No Content
//	$apiResponse = zbase()->json()->getVariable('api');
//	if(!empty($apiResponse) && $apiResponse instanceof \Zbase\Exceptions\HttpException && $apiResponse->getStatusCode() == 204)
//	{
//		$response->header('HTTP/1.1 204 No Content');
//		return $response;
//	}
	$returnNoContent = '';
	$errorResponse = false;
	$xmlResponse = false;
	$responseFormat = zbase_response_format();
	if(zbase_is_json())
	{
		$responseFormat = 'json';
	}
	if($responseFormat == 'json' || zbase_request_is_ajax())
	{
		$jsonResponse = true;
	}
	if($responseFormat == 'xml')
	{
		$xmlResponse = true;
	}
	if(zbase_is_angular_template())
	{
		$responseFormat = 'html';
		$jsonResponse = false;
	}
	if(!empty($jsonResponse))
	{
		zbase()->json()->setVariable('_route', zbase_route_name());
		zbase()->json()->setVariable('_package', zbase_view_template_package());
		$code = 200;
		if($response instanceof \RuntimeException)
		{
			$code = $response->getStatusCode();
			zbase()->json()->setVariable('statusCode', $code);
			if($code !== 200)
			{
				$errorResponse = true;
				zbase()->json()->setVariable('statusMessage', $response->getStatusMessage());
			}
		}
		/**
		 * its ajax, but method is GET
		 */
		if(empty($errorResponse))
		{
			$tokenResponse = zbase_request_input('token', zbase_request_query_input('token', false));
			if(!$tokenResponse)
			{
				zbase()->json()->setVariable('_token', zbase_csrf_token());
			}
		}
		zbase()->json()->setVariable('_alerts', [
			'errors' => zbase_alerts('error'),
			'messages' => zbase_alerts('success'),
			'info' => zbase_alerts('info'),
			'warning' => zbase_alerts('warning'),
		]);
		$forceResponse = zbase_request_input('forceResponse', zbase_request_query_input('forceResponse', false));
		/**
		 * JSONP Callback
		 */
		$jsonCallback = zbase_request_query_input('callback', zbase_request_query_input('jsonp', false));
		if(!$forceResponse)
		{
			zbase_alerts_render();
			if(!empty($jsonCallback))
			{
				return response()->json(zbase()->json()->getVariables(), $code)->setCallback($jsonCallback);
			}
			else
			{
				return response()->json(zbase()->json()->getVariables(), $code);
			}
		}
	}
	if($response instanceof \RuntimeException)
	{
		if($response->getStatusCode() == '302')
		{
			if(zbase_is_json())
			{
				zbase_alerts_render();
				if(!empty($jsonCallback))
				{
					return response()->json(zbase()->json()->getVariables(), 302)->setCallback($jsonCallback);
				}
				else
				{
					return response()->json(zbase()->json()->getVariables(), 302);
				}
			}
		}
		return $response->render(zbase_request(), $response);
	}
	/**
	 * REsponse with a javascript code
	 */
	if($responseFormat == 'javascript')
	{
		$response = \Response::make($response, 200);
		$response->header('Content-Type', 'application/javascript');
	}
	return $response;
}

/**
 * Return a JSON Response
 *
 * @param array $array
 * @return Illuminate\Http\JsonResponse
 */
function zbase_response_json($array)
{
	return \Response::json($array);
}

/**
 * Response with a file to download
 *
 * @param string $filepath Path to the file
 * @param string $filename The filename for the download
 * @param array $headers Content-Headers
 * @return Illuminate\Http\Response
 */
function zbase_response_file($filepath, $filename, $headers)
{
	return \Response::download($filepath, $filename, $headers);
}

/**
 * Throw an exception
 */
function zbase_exception_throw(\Exception $e)
{
	if(zbase_is_dev())
	{
		dd($e);
	}
	zbase_abort(500);
}

//try
//{
//} catch (\Zbase\Exceptions\RuntimeException $e)
//{
//	zbase_exception_throw($e);
//}

//try
//{
//	zbase_db_transaction_start();
//
//	zbase_db_transaction_commit();
//} catch (\Zbase\Exceptions\RuntimeException $e)
//{
//	zbase_db_transaction_rollback();
//	zbase_exception_throw($e);
//}

/**
 * Redirect with message
 * @param string $to
 * @param string $message
 * @TODO Add message
 * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
 */
function zbase_redirect_with_message($to, $message)
{
	zbase_alert('error', $message);
	return zbase_redirect($to);
}

/**
 * Redirect
 * @return redirect
 */
function zbase_redirect($to = null, $status = 302, $headers = [], $secure = null)
{
	if(zbase_is_angular_template())
	{
		zbase()->json()->setVariable('_redirect', $to);
		$response = new \Zbase\Exceptions\HttpException('Redirecting to ' . $to);
		$response->setStatusCode($status);
		return $response;
	}
	else
	{
		return redirect($to, $status, $headers, $secure);
	}
}

/**
 * SEt the REsponse Format
 * @param type $responseFormat The response format xml|json|html
 * @return void
 */
function zbase_response_format_set($responseFormat = 'html')
{
	zbase()->setResponseFormat($responseFormat);
}

/**
 * Retur the response format
 * @return string
 */
function zbase_response_format()
{
	return zbase()->getResponseFormat();
}
