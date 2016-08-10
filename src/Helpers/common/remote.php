<?php

/**
 * Dx
 *
 * @link http://dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2015 ClaremontDesign/MadLabs-Dx
 * @version 0.0.0.1
 * @since Aug 10, 2016 7:40:18 PM
 * @file remote.php
 * @project Zbase
 * @package Expression package is undefined on line 14, column 15 in Templates/Scripting/EmptyPHP.php.
 */

/**
 * Call Remote URL by Post
 * @param string $url The URL to call
 * @param array $data The data to post
 * @param array $options some options
 *
 * @return string
 */
function zbase_remote_post($url, $data, $options = [])
{
	$dataString = '';
	foreach ($data as $key => $value)
	{
		$dataString .= $key . '=' . $value . '&';
	}
	rtrim($dataString, '&');

	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_POST, count($fields));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}

/**
 * Call Remote URL by Post
 * @param string $url The URL to call
 * @param array $data The data to post
 * @param array $options some options
 *
 * @return string
 */
function zbase_remote_post_json($url, $data, $options = [])
{
	$dataString = '';
	foreach ($data as $key => $value)
	{
		$dataString .= $key . '=' . $value . '&';
	}
	rtrim($dataString, '&');
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_POST, count($data));
	curl_setopt($ch, CURLOPT_COOKIEJAR, zbase_storage_path() . 'cookie.txt');
	curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Content-Length: ' . strlen($dataString))
	);
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}
