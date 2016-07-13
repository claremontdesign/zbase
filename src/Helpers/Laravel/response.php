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
	$errorResponse = false;
	$xmlResponse = false;
	$responseFormat = zbase_response_format();
	if($responseFormat == 'json' || zbase_request_is_ajax())
	{
		$jsonResponse = true;
	}
	if($responseFormat == 'xml')
	{
		$xmlResponse = true;
	}
	if(!empty($jsonResponse))
	{
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
		$forceResponse = zbase_request_input('forceResponse', zbase_request_query_input('forceResponse', false));
		/**
		 * JSONP Callback
		 */
		$jsonCallback = zbase_request_query_input('callback', false);
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
function zbase_exception_throw()
{

}

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
	return redirect($to);
}

/**
 * Redirect
 * @return redirect
 */
function zbase_redirect()
{
	return redirect();
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
