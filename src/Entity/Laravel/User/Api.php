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
	 * Update User Profile
	 * @param array $data
	 */
	public static function updateProfile($data)
	{
		$ret = ['success' => false];
		if(!empty($data['userId']))
		{
			$userId = $data['userId'];
			unset($data['userId']);
		}
		else
		{
			if(zbase_auth_has())
			{
				$userId = zbase_auth_user()->id();
			}
		}
		if(!empty($userId))
		{
			$user = static::findUserById($userId, true);
		}
		if(!empty($user) && $user instanceof User)
		{
			$user->updateProfile($data);
			$ret['success'] = true;
		}
		$ret['user'] = static::userApi(static::findUserById($userId, true));
		return $ret;
	}

	/**
	 * Update Email Address
	 * @param strin $data
	 */
	public static function updateEmail($data)
	{
		$ret = ['success' => false];
		if(!empty($data['userId']))
		{
			$userId = $data['userId'];
			unset($data['userId']);
		}
		else
		{
			if(zbase_auth_has())
			{
				$userId = zbase_auth_user()->id();
			}
		}
		if(!empty($userId))
		{
			$user = static::findUserById($userId, true);
		}
		if(!empty($user) && $user instanceof User && !empty($data['email']))
		{
			$user->updateRequestEmailAddress($data['email']);
			$ret['success'] = true;
		}
		$ret['user'] = static::userApi(static::findUserById($userId, true));
		return $ret;
	}


	/**
	 * Update Email Address
	 * @param strin $data
	 */
	public static function updatePassword($data)
	{
		$ret = ['success' => false];
		if(!empty($data['userId']))
		{
			$userId = $data['userId'];
			unset($data['userId']);
		}
		else
		{
			if(zbase_auth_has())
			{
				$userId = zbase_auth_user()->id();
			}
		}
		if(!empty($userId))
		{
			$user = static::findUserById($userId, true);
		}
		if(!empty($user) && $user instanceof User && !empty($data['password']) && !empty($data['passwordConfirm']))
		{
			$user->updateRequestPassword($data['password']);
			$ret['success'] = true;
		}
		$ret['user'] = static::userApi(static::findUserById($userId, true));
		return $ret;
	}


	/**
	 * Login a User
	 * @param string|aray $username
	 * @param string $password
	 *
	 * @return array
	 */
	public static function login($username, $password = '')
	{
		$ret = ['success' => false];
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
					$ret['success'] = true;
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
			'username',
			'created_at',
			'user_id',
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
		unset($arr['options']);
		$arr['accountPassword'] = null;
		$arr['id'] = $arr['alpha_id'];
		unset($arr['alpha_id']);
		return $arr;
	}

	/**
	 * Return the Current User
	 * @return aray
	 */
	public static function current()
	{
		if(zbase_auth_has())
		{
			return ['user' => self::userApi(static::findUserById(zbase_auth_user()->id(), true))];
		}
		return [];
	}

	/**
	 * User By Id
	 * @param string|integer|array $id
	 * @return object|array
	 */
	public static function findUserById($id, $object = false)
	{
		if(is_array($id) && !empty($id['userId']))
		{
			$id = $id['userId'];
		}
		if(!empty($id) && is_numeric($id))
		{
			$entity = zbase()->entity('user', [], true);
			if(!empty($object))
			{
				return $entity->repo()->byId($id);
			}
			else
			{
				return ['user' => self::userApi($entity->repo()->byId($id))];
			}
		}
	}

	/**
	 * User By Email
	 * @param string $email
	 * @return object|array
	 */
	public static function findUserByEmail($email, $object = false)
	{
		if(is_array($email) && !empty($email['email']))
		{
			$email = $email['email'];
		}
		if(!empty($email))
		{
			$entity = zbase()->entity('user', [], true);
			return ['user' => self::userApi($entity->repo()->by('email', $email)->first())];
		}
	}
}
