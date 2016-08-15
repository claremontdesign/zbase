<?php

/**
 * Dx
 *
 * @link http://dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2015 ClaremontDesign/MadLabs-Dx
 * @version 0.0.0.1
 * @since Jul 5, 2016 12:49:12 PM
 * @file string.php
 * @project Expression project.name is undefined on line 13, column 15 in Templates/Scripting/EmptyPHP.php.
 * @package Expression package is undefined on line 14, column 15 in Templates/Scripting/EmptyPHP.php.
 */

/**
 * Convert a dot.notated string to an multiDimensional array
 *
 * @param type $arr
 * @param type $path
 * @param type $value
 * @return array
 */
function zbase_string_dot_to_array(&$arr, $path, $value)
{
	$keys = explode('.', $path);
	foreach ($keys as $key)
	{
		if(isset($arr[$key]))
		{

		}
		else
		{
			$arr = &$arr[$key];
		}
	}
	$arr = $value;
}

/**
 * Format address object to a proper string
 *
 * @param object $obj/array Address Object
 * @param string prefix like shipping_ or billing_
 * @param string $separator
 * @return string|array
 */
function zbase_string_from_address($obj, $prefix = null, $separator = ',<br />')
{
	if(empty($obj))
	{
		return null;
	}
	if(is_object($obj))
	{
		$array = $obj->toArray();
	}
	if(!empty($array))
	{
		/**
		 * 	CHRIS NISWANDEE
		  SMALLSYS INC
		  795 E DRAGRAM
		  TUCSON AZ 85705
		  USA
		 */
		$properties = ['address', 'address_two', 'city', 'state', 'zip', 'country'];
		$strings = [];
		$propName = $prefix . 'address';
		if(!empty($array[$propName]))
		{
			$strings[] = $array[$propName];
		}
		$propName = $prefix . 'address_two';
		if(!empty($array[$propName]))
		{
			$strings[] = $array[$propName];
		}
		$propName = $prefix . 'city';
		if(!empty($array[$propName]))
		{
			$s = [];
			$s[] = $array[$propName];
			$propName = $prefix . 'state';
			if(!empty($array[$propName]))
			{
				$s[] = $array[$propName];
			}
			$propName = $prefix . 'zip';
			if(!empty($array[$propName]))
			{
				$s[] = $array[$propName];
			}
			$propName = $prefix . 'country';
			if(!empty($array[$propName]))
			{
				$s[] = $array[$propName];
			}
			$strings[] = implode(', ', $s);
		}
		$propName = $prefix . 'phone';
		if(!empty($array[$propName]))
		{
			$strings[] = 'T: ' . $array[$propName];
		}
		$propName = $prefix . 'F: ';
		if(!empty($array[$propName]))
		{
			$strings[] = 'F: ' . $array[$propName];
		}
		if(!empty($strings) && !empty($separator))
		{
			return implode($separator, $strings);
		}
		if(is_array($strings))
		{
			return implode($separator, $strings);
		}
	}
}

/**
 * Split a string for City and State
 * @param string $cityState combination of City, State
 *
 * @return array
 */
function zbase_string_split_city_state($cityState)
{
	if(!empty($cityState))
	{
		$ret = [];
		$cityEx = explode(',', $cityState);
		if(!empty($cityEx[0]))
		{
			$ret['city'] = trim($cityEx[0]);
		}
		if(!empty($cityEx[1]))
		{
			$ret['state'] = trim($cityEx[1]);
		}
		return $ret;
	}
	return null;
}
