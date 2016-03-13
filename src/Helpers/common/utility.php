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


