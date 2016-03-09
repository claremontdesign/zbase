<?php

/**
 * Zbase-Laravel Helpers-Auth
 *
 * Functions and Helpers auth/session or current users
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file auth.php
 * @project Zbase
 * @package Zbase/Laravel/Helpers
 *
 */

/**
 * Return the Current Authed User
 * @return \
 */
function zbase_auth_user()
{
	return \Auth::user();
}

/**
 * Check if user is authenticated
 * @return boolean
 */
function zbase_auth_has()
{
	return \Auth::check();
}
