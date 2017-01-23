<?php

/**
 * Zbase-Laravel Helpers-Utility
 *
 * Functions and Helpers and other Utility
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file utility.php
 * @project Zbase
 * @package Zbase/Helpers
 */

/**
 * Generate a random code
 * @param integer $length
 * @return string
 */
function zbase_generate_code($length = 32)
{
	return zbase_generate_password($length, 'lud');
}

/**
 * https://gist.github.com/tylerhall/521810
 * Generate Password
 * @param integer $length
 * @param string $available_sets
 * @return string
 */
function zbase_generate_password($length = 9, $available_sets = 'luds')
{
	$add_dashes = false;
	$sets = array();
	if(strpos($available_sets, 'l') !== false)
	{
		$sets[] = 'abcdefghjkmnpqrstuvwxyz';
	}
	if(strpos($available_sets, 'u') !== false)
	{
		$sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
	}
	if(strpos($available_sets, 'd') !== false)
	{
		$sets[] = '23456789';
	}
	if(strpos($available_sets, 's') !== false)
	{
		$sets[] = '!@#$%&*?';
	}
	$all = '';
	$password = '';
	foreach ($sets as $set)
	{
		$password .= $set[array_rand(str_split($set))];
		$all .= $set;
	}
	$all = str_split($all);
	for ($i = 0; $i < $length - count($sets); $i++)
	{
		$password .= $all[array_rand($all)];
	}
	$password = str_shuffle($password);
	if(!$add_dashes)
	{
		return $password;
	}
	$dash_len = floor(sqrt($length));
	$dash_str = '';
	while (strlen($password) > $dash_len)
	{
		$dash_str .= substr($password, 0, $dash_len) . '-';
		$password = substr($password, $dash_len);
	}
	$dash_str .= $password;
	return $dash_str;
}

/**
 * GEnerate a hash string based on the given argument
 * @param string $value The value to hash
 * @param string $salt Salt/Key
 * @return string
 */
function zbase_generate_hash($value, $salt = null)
{
	$hash = new \Zbase\Utility\Hash\Hash($salt);
	return $hash->encode($value);
}

/**
 * Reverse of zbase_generate_hash
 * @param string $hashedValue The hashedValue to decode
 * @param string $salt Salt/Keys
 * @return mixed
 */
function zbase_generate_hash_reverse($hashedValue, $salt = null)
{
	$hash = new \Zbase\Utility\Hash\Hash($salt);
	return $hash->decode($hashedValue);
}

/**
 * Function that converts an array into the JSON Object to be used for Javascript
 *
 * @param array $var
 * @return string
 */
function zbase_json_to_javascript($var)
{
	return str_ireplace(
			array("'function", '"function', "}'", '}"','"data.data"','"data.title"','"@@','@@"'),
			array("function", 'function', "}", '}','data.data','data.title','',''),
			json_encode($var, JSON_UNESCAPED_SLASHES));
}
