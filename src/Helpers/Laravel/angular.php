<?php

/**
 * Dx
 *
 * @link http://dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2015 ClaremontDesign/MadLabs-Dx
 * @version 0.0.0.1
 * @since Jul 11, 2016 6:42:51 PM
 * @file angular.php
 * @project Expression project.name is undefined on line 13, column 15 in Templates/Scripting/EmptyPHP.php.
 * @package Expression package is undefined on line 14, column 15 in Templates/Scripting/EmptyPHP.php.
 */

/**
 * Create Angular URL/Link
 * @param string $name
 * @param array $params
 * @return string
 */
function zbase_angular_url($name, $params = [])
{
	$home = route('index');
	$url = str_replace($home, '', route($name, $params));
	return '#' . str_replace('/admin/', '/', $url);
}

/**
 * Create angular Route
 * @param string $name
 * @param array $params
 * @return string
 */
function zbase_angular_route($name, $params)
{
	$home = route('index');
	$url = str_replace($home, '', route($name, $params));
	return str_replace('/admin/', '/', $url);
}

/**
 * Create Route TEMPLATE Url
 * @param string $name
 * @param array $params
 * @return string
 */
function zbase_angular_template_url($name, $params)
{
	$url = zbase_url_from_route($name, [], true);
	return str_replace('#/', '/', $url);
}
