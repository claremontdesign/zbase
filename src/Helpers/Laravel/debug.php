<?php

/**
 * Zbase-Laravel Helpers-Debugging
 *
 * Functions and Helpers for Debugging
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file debug.php
 * @project Zbase
 * @package Zbase/Laravel/Helpers
 */

/**
 * Check if debugging is allowed
 * @return boolean
 */
function zbase_debug_enable()
{
	return env('APP_DEBUG', false);
}

function zbase_debug_message()
{

}
