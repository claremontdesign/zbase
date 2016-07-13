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
