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
 * Check if current user has access to the resource
 * @param string|array $access The Access needed
 * @param string $resource The resource
 * @param boolean
 */
function zbase_auth_check_access($access, $resource = null)
{
	if(strtolower($access) == 'guest')
	{
		return true;
	}
	if(zbase_auth_has())
	{
		return (bool) zbase_auth_user()->hasAccess(strtolower($access), $resource);
	}
	return false;
}

/**
 * Return the Minimum Access for the section
 * @return string
 */
function zbase_auth_minimum()
{
	if(zbase_is_back())
	{
		if(zbase_route_username())
		{
			return zbase_route_username_minimum_access();
		}
		return zbase_config_get('auth.access.minimum.back', 'admin');
	}
	return zbase_config_get('auth.access.minimum.front', 'guest');
}

/**
 * Check if user is of $role Role
 * @return boolean
 */
function zbase_auth_is($role)
{
	return zbase_auth_check_access($role);
}
