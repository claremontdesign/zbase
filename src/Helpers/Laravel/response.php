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
	if(zbase_request_is_ajax())
	{
		zbase()->json()->setVariable('_token', zbase_csrf_token());
		return response()->json(zbase()->json()->getVariables());
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
