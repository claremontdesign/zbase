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
 * Retrieve an input value by $key
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

/**
 * Flash a message for the form input.
 * Usable if you want to check if there are error on an input
 *
 * @param string $key
 * @param string $msg
 * @param string $type error|succes|danger|info
 */
function zbase_form_message_flash($key, $msg, $type = 'error')
{
	zbase_session_flash('_form_' . $type . '_' . $key, $msg);
}

/**
 * Check if form input has error
 *
 * @param string $key
 * @return boolean
 */
function zbase_form_input_has_error($key)
{
	return zbase_session_has('_form_error_' . $key);
}

/**
 * Eetrieves an old input value flashed into the session:
 * @param type $name
 * @return integer|string
 */
function zbase_form_old($name, $default = null)
{
	return old($name, $default);
}

// <editor-fold defaultstate="collapsed" desc="CSRF">
/**
 * CSRF TOKEN
 * @return string
 */
function zbase_csrf_token()
{
	return csrf_token();
}

/**
 * CSRF Hidden Element
 * @param string $formId optional formId
 * @return string
 */
function zbase_csrf_token_field($formId = null)
{
	$string = csrf_field();
	if(!empty($formId))
	{
		$string .= '<input type="hidden" id="_formId" name="_formid" value="' . $formId . '" />';
	}
	return $string;
}

// </editor-fold>
