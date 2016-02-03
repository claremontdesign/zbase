<?php

/**
 * Zbase-Laravel Helpers-Forms
 *
 * Functions and Helpers for Accessing Form information
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file form.php
 * @project Zbase
 * @package Zbase/Laravel/Helpers
 */

/**
 * Retruive an input value by $key
 *
 * @param string $key
 * @param mixed $default
 * @return mixed
 */
function zbase_form_input($key, $default = null)
{
	return zbase_request_input($key, $default);
}

/**
 * Return all inputs
 *
 * @return array
 */
function zbase_form_inputs()
{
	return $_POST;
}
