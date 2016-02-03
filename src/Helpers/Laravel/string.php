<?php

/**
 * Zbase-Laravel Helpers-String
 *
 * Functions and Helpers for String Manipulation
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file string.php
 * @project Zbase
 * @package Zbase/Laravel/Helpers
 */

/**
 * Camel-Case a string
 * @param string $value
 * @return string
 */
function zbase_string_camel_case($value)
{
	return camel_case($value);
}

/**
 * Convert a string to snake case
 *
 * @param string $value
 * @return string
 */
function zbase_string_snake_case($value)
{
	return snake_case($value);
}

/**
 * Determine if a given string starts with a given substring
 *
 * @param string $string
 * @param string $needle
 * @return boolean
 */
function zbase_string_starts_with($string, $needle)
{
	return starts_with($string, $needle);
}

/**
 * Determine if a given string ends with a given substring
 *
 * @param string $string
 * @param string $needle
 * @return boolean
 */
function zbase_string_ends_with($string, $needle)
{
	return ends_with($string, $needle);
}

/**
 * Create a string based from array
 * @param array $array
 * @param string $glue
 * @return string
 */
function zbase_string_from_array($array, $glue = '_')
{
	$newArgs = [];
	if(empty($array))
	{
		return null;
	}
	foreach ($array as $k => $v)
	{
		if(is_array($v))
		{
			$newArgs[] = zbase_string_from_array($v);
		}
		else
		{
			$newArgs[] = $k . $glue . $v;
		}
	}
	return implode($glue, $newArgs);
}
