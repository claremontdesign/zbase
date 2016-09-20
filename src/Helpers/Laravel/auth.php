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
 * Check if duplex is enabled
 * @return boolean
 */
function zbase_auth_duplex_enable()
{
	return zbase_config_get('modules.users.duplex.enable', false);
}

/**
 * Make admin login like a user
 * 	Will set the needed session
 *
 * @param integer $userId The user Id
 * @return void
 */
function zbase_auth_duplex($userId)
{
	zbase_session_set('_duplexSession', $userId);
}

/**
 *
 * @param type $userId
 *
 * Unset Duplext Authed
 */
function zbase_auth_unset_duplex()
{
	zbase_session_forget('_duplexSession');
}

/**
 * Check if we are duplex
 *
 * @return boolean
 */
function zbase_auth_is_duplex()
{
	if(zbase_auth_duplex_enable())
	{
		if(zbase_auth_has())
		{
			if(\Auth::user()->isAdmin() && !empty(zbase_session_has('_duplexSession')))
			{
				return true;
			}
		}
	}
	return false;
}

/**
 * Check if we can Auth aonther user
 * @param type $userId
 */
function zbase_auth_can_duplex()
{
	return zbase_auth_real()->isAdmin();
}

/**
 * The Real authed User
 *
 * @return User
 */
function zbase_auth_real()
{
	return zbase_user_byid(\Auth::user()->id());
}

/**
 * Return the Current Authed User
 * @return \
 */
function zbase_auth_user()
{
	if(!zbase_auth_has())
	{
		return false;
	}
	if(\Auth::user()->isAdmin() && !empty(zbase_session_has('_duplexSession')))
	{
		return zbase_user_byId(zbase_session_get('_duplexSession'));
	}
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
	if(preg_match('/@/', $value) > 0)
	{
		return zbase_entity('user')->by('email', $value);
	}
	else
	{
		return zbase_entity('user')->by($attr, $value);
	}
}
