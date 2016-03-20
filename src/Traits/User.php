<?php

namespace Zbase\Traits;

/**
 * Zbase-User
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
trait User
{
	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return User
	 */
	protected function userCreate(array $data)
	{
		$user = [
			'status' => $this->defaultNewUserStatus(),
			'username' => !empty($data['username']) ? $data['username'] : null,
			'name' => $data['name'],
			'email' => $data['email'],
			'email_verified' => $this->emailVerificationEnabled() ? 0 : 1,
			'email_verified_at' => null,
			'password' => zbase_bcrypt($data['password']),
			'password_updated_at' => null,
			'created_at' => zbase_date_now(),
			'updated_at' => zbase_date_now(),
			'deleted_at' => null,
		];
		unset($data['username']);
		unset($data['name']);
		unset($data['email']);
		unset($data['password']);
		$user = array_merge_recursive($user, $data);
		return zbase_entity('user')->create($user);
	}

	/**
	 * If email verification is enabled
	 * @return boolean
	 */
	public function emailVerificationEnabled()
	{
		return zbase_entity('user')->emailVerificationEnabled();
	}

	/**
	 * New registrant default status
	 * @return string
	 */
	public function defaultNewUserStatus()
	{
		return zbase_entity('user')->defaultNewUserStatus();
	}

	/**
	 * The Default new user Role
	 * @return string
	 */
	public function defaultNewUserRole()
	{
		return zbase_entity('user')->defaultNewUserRole();
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
}
