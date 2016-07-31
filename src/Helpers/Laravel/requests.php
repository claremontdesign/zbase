<?php

/**
 * Zbase-Laravel Helpers-Requests
 *
 * Functions and Helpers for Accessing Requests information
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file requests.php
 * @project Zbase
 * @package Zbase/Laravel/Helpers
 */

/**
 * Return the Request Object
 * @return \Illuminate\Http\Request
 */
function zbase_request()
{
	return app('request');
}

/**
 * Prefix all request name
 *
 * @param string $key
 * @return string
 */
function zbase_request_name($key)
{
	return zbase_tag() . '_' . $key;
}

/**
 * Return the current URL
 * example: http://zbase.com/x/y/z?a=b
 * returns: http://zbase.com/x/y/z
 *
 * @return string
 */
function zbase_url()
{
	return \Request::url();
}

/**
 * Return the request path
 * example: http://zbase.com/x/y/z?a=b
 * returns: /x/y/z
 *
 * @return string
 */
function zbase_url_path()
{
	return \Request::path();
}

/**
 * Return the request URI
 * example: http://zbase.com/x/y/z?a=b
 * returns: /x/y/z?a=b
 *
 * @return string
 */
function zbase_url_uri()
{
	return \Request::getRequestUri();
}

/**
 * Return the current URI
 * example: http://zbase.com/x/y/z?a=b
 * returns: http://zbase.com/x/y/z?a=b
 *
 * @return string
 */
function zbase_uri()
{
	return \Request::getUri();
}

/**
 * Returns the query strng
 * example: http://zbase.com/x/y/z?a=b&a=b&c=d
 * returns: a=b || a=b&c=d
 *
 * @return type
 */
function zbase_query_string()
{
	return \Request::getQueryString();
}

/**
 * Return the IP Address
 *
 * @return string IP Address
 */
function zbase_ip()
{
	return \Request::getClientIp();
}

/**
 * Returns the query strng
 * example: http://zbase.com/x/y/z?a=b&a=b&c=d
 * returns: 80
 *
 * @return integer
 */
function zbase_request_port()
{
	return \Request::getPort();
}

/**
 * Return value of an input data
 *
 * @param type $key
 * @param type $default
 * @return type
 */
function zbase_request_input($key, $default = null)
{
	return \Request::input($key, $default);
}

/**
 * Return all inputs
 * If form was POSTed, will return the POSTed data
 * If query string data is available, will return also the queryString data
 *
 * @return array
 */
function zbase_request_inputs()
{
	return \Request::all();
}

/**
 * Return a queryString input by $key
 *
 * @param string $key
 * @param string|integer $default
 * @return string|integer
 */
function zbase_request_query_input($key, $default = null)
{
	return isset($_GET[$key]) ? $_GET[$key] : $default;
}

/**
 * Return all the queryString inputs
 *
 * @return array
 */
function zbase_request_query_inputs()
{
	return isset($_GET) ? $_GET : [];
}

/**
 * Get a segment from the URI
 * example: http://zbase.com/x/y/z?a=b&a=b&c=d
 * zbase_request_segment(1) = x
 * zbase_request_segment(2) = y
 * zbase_request_segment(3) = z
 * zbase_request_segment(4) = null
 * zbase_request_segment(5) = null
 *
 * @param type $index
 * @return type
 */
function zbase_request_segment($index)
{
	return \Request::segment($index);
}

/**
 * Determine if the current request URI matches a pattern
 * zbase_request_is('foo/*');
 *
 * @param string $path
 * @return boolean
 */
function zbase_request_is($path)
{
	return \Request::is($path);
}

/**
 * Determine if the request is over HTTPS
 *
 * @return boolean
 */
function zbase_request_is_secure()
{
	return \Request::secure();
}

/**
 * Return the current request method
 * post|get|put|delete|update|head
 *
 * @return string
 */
function zbase_request_method()
{
	return strtolower(\Request::method());
}

/**
 * Check if we are POSTing
 *
 * @return boolean
 */
function zbase_is_post()
{
	return \Request::isMethod('post');
}

/**
 * Get raw POST data
 *
 * @return string
 */
function zbase_request_raw_post()
{
	return \Request::instance()->getContent();
}

/**
 * true if HTTP Accept header is application/json
 *
 * @return boolean
 */
function zbase_is_json()
{
	if(zbase_request_query_input('jsonp', false))
	{
		return true;
	}
	return \Request::wantsJson();
}

/**
 * Get requested response format
 */
function zbase_request_format()
{
	return \Request::format();
}

/**
 * Check if the request is the result of an AJAX call
 *
 * @return boolean
 */
function zbase_request_is_ajax()
{
	return \Request::ajax();
}

/**
 * Is Post?
 * @return boolean
 */
function zbase_request_is_post()
{
	return strtolower(zbase_request_method()) == 'post';
}

/**
 * Retrieve a server variable from the request
 * @param string $key Key to retrieve
 *
 * @return string
 */
function zbase_request_server($key)
{
	return \Request::server(strtoupper($key));
}

/**
 * Retrieve a header from the request
 *
 * @param string $key
 * @return string
 */
function zbase_request_header($key)
{
	return \Request::header($key);
}

/**
 * Return the current controller name
 * @return string
 */
function zbase_request_controller()
{
	return zbase()->controller();
}
