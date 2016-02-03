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
 * @return string
 */
function zbase_url_from_current($params = [])
{
	return url(zbase_uri(), $params);
}

/**
 * Create a URL
 * @param string $path
 * @param array $parameters
 * @param boolean $secure
 * @return string
 */
function zbase_url_create($path, $parameters, $secure = false)
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
	if(!empty($config['route']) && !empty($config['route']['name']))
	{
		$name = $config['route']['name'];
		$params = !empty($config['route']['params']) ? $config['route']['params'] : [];
		return zbase_url_from_route($name, $params);
	}
}
