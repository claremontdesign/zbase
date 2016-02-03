<?php

/**
 * Zbase-Laravel Helpers-Routes
 *
 * Functions and Helpers for Accessing Routes
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file routes.php
 * @project Zbase
 * @package Zbase/Laravel/Helpers
 */

/**
 * Retrieve a route-parameter value
 *
 * @param string $key
 * @return string
 */
function zbase_route_input($key)
{
	return \Route::current()->parameter($key);
}

/**
 * Return all route parameters
 * @return array
 */
function zbase_route_inputs()
{
	return \Route::current()->parameters();
}
