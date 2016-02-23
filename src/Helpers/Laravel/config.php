<?php

/**
 * Zbase-Laravel Helpers-Configuration
 *
 * Functions and Helpers for Configuration
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file config.php
 * @project Zbase
 * @package Zbase/Laravel/Helpers
 */

/**
 * Retrieve the value of the dot-notated key from the application configuration
 *
 * First, it will check for ENV value,
 * 	then the configuration
 * 	else will return the value of the $default
 *
 *  example:		view.config.name = theValue
 * 				Config: [view => [
 * 							config => [
 * 									name => theConfigValue
 * 								]
 * 							]
 * 						]
 * 				ENV: VIEW_CONFIG_NAME = theEnvValue
 *
 * @param string $key dot-notated key
 * @param mixed $default The Default value to return
 * @return mixed
 */
function zbase_config_get($key, $default = null)
{
	$path = zbase_tag() . '.' . $key;
	$envPath = strtoupper(str_replace('.', '_', $path));
	return env($envPath, zbase_data_get(null, $path, $default));
}

/**
 * Set the value of the dot-notated key
 * @see https://laravel.com/docs/5.2/configuration
 *
 * @param string $key
 * @param mixed $value
 */
function zbase_config_set($key, $value)
{
	$k = zbase_tag() . '.' . $key;
	app()['config'][$k] = $value;
}
