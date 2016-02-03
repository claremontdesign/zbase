<?php

/**
 * Zbase-Laravel Helpers-Controller
 *
 * Functions and Helpers for Controller
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file controller.php
 * @project Zbase
 * @package Zbase/Laravel/Helpers
 */

/**
 * Create a controller class name based from a given controller name
 * zbase_controller_create_name(Zbase\Http\Controllers\__FRAMEWORK__\PageController::class);
 * 	output: Zbase\Http\Controllers\Laravel\PageController::class
 *
 * @param string $name
 * @return string
 */
function zbase_controller_create_name($name)
{
	return zbase_class_name($name);
}
