<?php

namespace Zbase\Traits;

/**
 * Zbase-Auth
 *
 * ReUsable Traits - Auth
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Auth.php
 * @project Zbase
 * @package Zbase/Traits
 */
trait Auth
{

	/**
	 * Check if user has access
	 *
	 * @return boolean
	 */
	public function hasAccess()
	{
		if(property_exists($this, 'access'))
		{
			return $this->access;
		}
		if(property_exists($this, 'attributes'))
		{
			if(!empty($this->attributes['access']))
			{
				return $this->attributes['access'];
			}
		}
		return false;
	}

	/**
	 * Check if application user authentication is enabled
	 * @return boolean
	 */
	public function authEnabled()
	{
		return zbase_config_get('auth.enable', true);
	}

	/**
	 * If registration is enabled
	 * @return boolean
	 */
	public function registerEnabled()
	{
		if($this->authEnabled())
		{
			return zbase_config_get('auth.register.enable', true);
		}
		return false;
	}

	/**
	 * If email verification is enabled
	 * @return boolean
	 */
	public function emailVerificationEnabled()
	{
		return zbase_config_get('auth.emailverify.enable', true);
	}

	/**
	 * New registrant default status
	 * @return string
	 */
	public function defaultNewUserStatus()
	{
		return zbase_config_get('auth.register.defaultStatus', 'ok');
	}

	/**
	 * The Default new user Role
	 * @return string
	 */
	public function defaultNewUserRole()
	{
		return zbase_config_get('auth.role.default', 'user');
	}

}
