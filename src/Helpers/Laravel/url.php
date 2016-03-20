<?php

/**
 * Zbase-Laravel Helpers-Routes
 *
 * Functions and Helpers for URL
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file url.php
 * @project Zbase
 * @package Zbase/Laravel/Helpers
 */

/**
 * Create a URL Based from a route $name
 * @param type $name
 * @param type $params
 */
function zbase_url_from_route($name, $params = [])
{
	return route($name, $params);
}

/**
 * Create URL based from current
 *
 * @param array $params Array of params to place/replace to the url
 * @param boolean $addReplace If replace, will add and replace params, else, will create new based on params
 * @return string
 */
function zbase_url_from_current($params = [], $addReplace = true)
{
	if(!empty($addReplace))
	{
		$queryStrings = array_replace_recursive(zbase_request_query_inputs(), $params);
	}
	else
	{
		$queryStrings = $params;
	}
	$urlQ = [];
	foreach ($queryStrings as $k => $v)
	{
		$urlQ[] = $k . '=' . $v;
	}
	return zbase_url() . '?' . implode('&', $urlQ);
}

/**
 * Return the Previous URL
 * @return string
 */
function zbase_url_previous()
{
	return app(\Illuminate\Routing\UrlGenerator::class)->previous();
}

/**
 * Create a URL
 * @param string $path
 * @param array $parameters
 * @param boolean $secure
 * @return string
 */
function zbase_url_create($path, array $parameters = null, $secure = false)
{
	return url($path, $parameters, $secure);
}

/**
 * Create a URL based from configuration
 *
 * url.route.name
 * url.route.name.params
 *
 * @param array $config
 * @return string
 */
function zbase_url_from_config($config)
{
	if(is_string($config))
	{
		return $config;
	}
	if(is_array($config))
	{
		if(!empty($config['route']) && !empty($config['route']['name']))
		{
			$name = $config['route']['name'];
			$params = !empty($config['route']['params']) ? $config['route']['params'] : [];
			return zbase_url_from_route($name, $params);
		}
		if(!empty($config['link']) && is_string($config['link']))
		{
			return $config['link'];
		}
	}
}

/**
 * Convert an array to GET parameters
 * @param array $array Assoc Array
 * @return string
 */
function zbase_url_array_to_get($array)
{
	if(!empty($array))
	{
		$a = [];
		foreach ($array as $k => $v)
		{
			$a[] = $k . '=' . $v;
		}
		return implode('&', $a);
	}
	return null;
}
