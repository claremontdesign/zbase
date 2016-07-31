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
function zbase_url_from_route($name, $params = [], $relative = false)
{
	$prefix = '';
	$usernameRouteParameterName = zbase_route_username_prefix();
	$usernameRoute = zbase_route_username_get();
	if(!empty($usernameRoute))
	{
		$prefix = $usernameRouteParameterName;
		$params[$usernameRouteParameterName] = $usernameRoute;
	}
	$name = $prefix . $name;
	if(!empty($relative))
	{
		$home = route('index');
		return str_replace($home, '', route($name, $params));
	}
	return route($name, $params);
}

/**
 * Create URL based from current
 *
 * @param array $params Array of params to place/replace to the url
 * @param boolean $addReplace If replace, will add and replace params, else, will create new based on params
 * @return string
 */
function zbase_url_from_current($params = [], $replace = true, $add = false)
{
	if(!empty($replace) && !empty($add))
	{
		$queryStrings = array_replace_recursive(zbase_request_query_inputs(), $params);
	}
	else
	{
		$queryStrings = $params;
	}
	if(!empty($replace))
	{
		$qs = zbase_request_query_inputs();
		foreach ($params as $pK => $pV)
		{
			if(array_key_exists($pK, $qs))
			{
				unset($qs[$pK]);
			}
		}
		$queryStrings = array_replace_recursive($qs, $params);
	}
	$urlQ = [];
	foreach ($queryStrings as $k => $v)
	{
		if(is_array($v))
		{
			foreach ($v as $vK => $vV)
			{
				$urlQ[] = $k . '[' . $vK . ']=' . $vV;
			}
		}
		else
		{
			$urlQ[] = $k . '=' . $v;
		}
	}
	if(zbase_is_angular_template())
	{
		$home = route('index');
		return '#' . str_replace($home, '', zbase_url() . '?' . implode('&', $urlQ));
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
 * @param array $params Some parameters
 * @return string
 */
function zbase_url_from_config($config, $params = [], $relative = false)
{
	if(is_string($config))
	{
		if(zbase_is_angular_template())
		{
			return '#' . $config;
		}
		return $config;
	}
	if(is_array($config))
	{
		if(!empty($config['route']) && !empty($config['route']['name']))
		{
			$name = $config['route']['name'];
			$params = !empty($config['route']['params']) ? $config['route']['params'] : [];
			return zbase_url_from_route($name, $params, $relative);
		}
		if(!empty($config['link']) && is_string($config['link']))
		{
			if(zbase_is_angular_template())
			{
				return '#' . $config['link'];
			}
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

/**
 * 'url' => '/api/{username}/{key}/{format}/{module}/{object}/{method}/{paramOne?}/{paramTwo?}/{paramThree?}/{paramFour?}/{paramFive?}/{paramSix?}',
 * Create an API URL
 * @param array $params
 * @return string
 */
function zbase_api_url($params)
{
	$array = [];
	$array['username'] = !empty($params['username']) ? $params['username'] : 'username';
	$array['key'] = !empty($params['key']) ? $params['key'] : 'key';
	$array['format'] = !empty($params['format']) ? $params['format'] : 'json';
	$array['module'] = !empty($params['module']) ? $params['module'] : null;
	$array['object'] = !empty($params['object']) ? $params['object'] : null;
	$array['method'] = !empty($params['method']) ? $params['method'] : null;
	$array['paramOne'] = !empty($params['paramOne']) ? $params['paramOne'] : null;
	$array['paramTwo'] = !empty($params['paramTwo']) ? $params['paramTwo'] : null;
	$array['paramThree'] = !empty($params['paramThree']) ? $params['paramThree'] : null;
	$array['paramFour'] = !empty($params['paramFour']) ? $params['paramFour'] : null;
	$array['paramFive'] = !empty($params['paramFive']) ? $params['paramFive'] : null;
	$array['paramSix'] = !empty($params['paramSix']) ? $params['paramSix'] : null;

	return zbase_url_from_route('api', $array);
}
