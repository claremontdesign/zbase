<?php

/**
 * Packagename Helpers
 *
 * Functions and Helpers
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file helpers.php
 * @project Packagename
 * @package Packagename/Helpers
 */
$folders = [__DIR__ . '/', __DIR__ . '/' . zbase_framework() . '/'];
foreach ($folders as $folder)
{
	if(is_dir($folder))
	{
		$handle = opendir($folder);
		while (false !== ($filename = readdir($handle)))
		{
			if($filename !== '.' && $filename !== '..' && is_file($folder . $filename))
			{
				require_once $folder . $filename;
			}
		}
	}
}

/**
 * The Packagename Tag/Prefix
 *
 * @return string
 */
function packagename_tag()
{
	return strtolower('packagename');
}

/**
 * Return a Package name specific configuration
 * @param string $key The Key to return
 * @param mixed $default Default value
 * @return mixed
 */
function packagename_config_get($key, $default = null)
{
	$package = packagename_tag();
	return zbase_config_get($package . '.' . $key, $default);
}

/**
 * Set the value of the dot-notated key
 * @see https://laravel.com/docs/5.2/configuration
 *
 * @param string $key
 * @param mixed $value
 */
function packagename_config_set($key, $value)
{
	$package = packagename_tag();
	zbase_config_set($package . '.' . $key, $value);
}
