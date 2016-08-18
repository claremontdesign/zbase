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
	if(!empty(\Auth::user()))
	{
		return zbase_user_byid(\Auth::user()->id());
	}
	return false;
}

/**
 * Check if user is authenticated
 * @return boolean
 */
function zbase_auth_has()
{
	return \Auth::check();
}

/**
 * Return a User By Id
 * @param integer $userId
 * @return null|false|Zbase\Entity\Laravel\User\User
 */
function zbase_user_byid($userId)
{
	return zbase_entity('user')->byId($userId);
}

/**
 * Retur the system user
 *
 * @return null|false|Zbase\Entity\Laravel\User\User
 */
function zbase_user_system()
{
	return zbase_user_byid(3);
}

/**
 * Search user by ATtribute
 * @param string $attr
 * @param string $value
 * @return \Zbase\Entity\Laravel\User|null
 */
function zbase_user_by($attr, $value)
{
	return zbase_entity('user')->by($attr, $value);
}
