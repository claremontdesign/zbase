<?php

namespace Zbase\Entity\Laravel\User;

/**
 * Zbase-User Entity API
 *
 * User Entity Base Model
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file User.php
 * @project Zbase
 * @package Zbase/Entity/User
 */
class Api
{

	/**
	 * Login a User
	 * @param string|aray $username
	 * @param string $password
	 *
	 * @return array
	 */
	public static function login($username, $password = '')
	{
		$ret = ['login' => false];
		if(is_array($username) && !empty($username['username']) && !empty($username['password']))
		{
			$password = $username['password'];
			$username = $username['username'];
			$entity = zbase()->entity('user', [], true);
			$user = $entity->repo()->by('email', $username)->first();
			if(!empty($user))
			{
				$same = zbase_bcrypt_check($password, $user->password);
				if(!empty($same))
				{
					\Auth::login($user);
					$ret['login'] = true;
				}
			}
		}
		return $ret;
	}

	/**
	 * Logout User
	 */
	public static function logout()
	{
		$ret = ['logout' => true];
		\Auth::logout();
		return $ret;
	}

	/**
	 *
	 * @param User $user
	 */
	public static function userApi($user)
	{
		$hiddenColumns = [
			'email_verified',
			'email_verified_at',
			'password_updated_at',
			'updated_at',
			'deleted_at',
			'remember_token'
		];
		$arr = [];
		$arr = $user->toArray();
		foreach ($arr as $k => $v)
		{
			if(in_array($k, $hiddenColumns))
			{
				unset($arr[$k]);
			}
		}
		$arr['profile'] = $user->profile()->toArray();
		return ['user' => $arr];
	}

	/**
	 * Return the Current User
	 * @return aray
	 */
	public static function current()
	{
		if(zbase_auth_has())
		{
			return static::findUserById(zbase_auth_user()->id());
		}
		return [];
	}

	/**
	 * User By Id
	 * @param string|integer|array $id
	 * @return object|array
	 */
	public static function findUserById($id)
	{
		if(is_array($id) && !empty($id['userId']))
		{
			$id = $id['userId'];
		}
		if(!empty($id) && is_numeric($id))
		{
			$entity = zbase()->entity('user', [], true);
			return self::userApi($entity->repo()->byId($id));
		}
	}

	/**
	 * User By Email
	 * @param string $email
	 * @return object|array
	 */
	public static function findUserByEmail($email)
	{
		if(is_array($email) && !empty($email['email']))
		{
			$email = $email['email'];
		}
		if(!empty($email))
		{
			$entity = zbase()->entity('user', [], true);
			return self::userApi($entity->repo()->by('email', $email)->first());
		}
	}

	/**
	 * User By Username
	 * @param string $username
	 * @return object|array
	 */
	public static function findUserByUsername($username)
	{

	}

}
