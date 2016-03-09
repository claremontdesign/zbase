<?php

namespace Zbase\Entity\Laravel\User;

/**
 * Zbase-User Entity
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
use Zbase\Entity\Laravel\Entity as BaseEntity;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends BaseEntity implements
AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{

	use Authenticatable,
	 Authorizable,
	 CanResetPassword;

	const STATUS_OK = 'ok';
	const STATUS_BAN = 'ban';
	const STATUS_BAN_NO_AUTH = 'ban_no_auth';

	/**
	 * Entity name as described in the config
	 * @var string
	 */
	protected $entityName = 'user';

	/**
	 * Fix/Manipulate entity data
	 *
	 * @param array $data
	 * @param string $mode The data mode insert|update|delete
	 * @return array
	 */
	public function fixDataArray(array $data, $mode = null)
	{
		if(empty($data['email_verified']))
		{
			$data['email_verified_at'] = null;
		}
		if(!empty($data['user_id']))
		{
			unset($data['user_id']);
		}
		return $data;
	}

	/**
	 * Password has been resetted
	 * @param string $password The Password
	 */
	public function passwordResetted($password)
	{
		$this->password_updated_at = new \Datetime('now');
		$this->save();
	}

	/**
	 * Check if user Can do Authentication
	 * @return boolean
	 */
	public function canAuth()
	{
		if($this->status == self::STATUS_BAN_NO_AUTH)
		{
			return false;
		}
		return true;
	}

	/**
	 * Check if user is an admin
	 * @return type
	 */
	public function isAdmin()
	{
		return $this->hasAccess(zbase_auth_minimum());
	}

	/**
	 * Check if user is Banned
	 * @return boolean
	 */
	public function isBanned()
	{
		if($this->status == self::STATUS_BAN_NO_AUTH)
		{
			return true;
		}
		if($this->status == self::STATUS_BAN)
		{
			return true;
		}
		return false;
	}

	/**
	 * Model-Level after authentication method
	 * @TODO Save a log
	 */
	public function authenticated()
	{

	}

	/**
	 * Check for access on the resource
	 * @param string|array $access The Access needed
	 * @param string $resource The resource
	 * @return boolean
	 */
	public function hasAccess($access, $resource = null)
	{
		$role = $this->roles()->getModel()->repository()->by('role_name', $access)->first();
		$roleClassname = get_class(zbase_entity('user_roles'));
		if($role instanceof $roleClassname)
		{
			$userHighestRole = $this->roles()->orderBy('parent_id', 'DESC')->first();
			if($userHighestRole->name() == $role->name())
			{
				return true;
			}
			$roles = $userHighestRole->children();
			if(!empty($roles))
			{
				foreach ($roles as $r)
				{
					if($r->name() == $role->name())
					{
						return true;
					}
				}
			}
		}
		return false;
	}

	/**
	 * Save a new model and return the instance.
	 *
	 * @param  array  $attributes
	 * @return static
	 */
	public static function create(array $attributes = [])
	{
		\DB::beginTransaction();
		$model = parent::create($attributes);
		$model->toggleRelationshipMode();
		if(!empty($attributes['password']))
		{
			$model->password = $attributes['password'];
		}
		if(!empty($attributes['status']))
		{
			$model->status = $attributes['status'];
		}
		$role = self::roles()->getRelated()->repository()->by('role_name', !empty($attributes['role']) ? $attributes['role'] : zbase_config_get('auth.role.default', 'user'))->first();
		if(!empty($role))
		{
			$model->roles()->save($role);
			$model->alpha_id = alphaID($model->user_id, false, strlen($model->getKeyName()), $model->getTable());
			$model->save();
		}
		else
		{
			\Db::rollback();
			throw new \Zbase\Exceptions\RuntimeException('User Role given not found.');
		}
		$profileAttributes = [
			'user_id' => $model->user_id,
			'first_name' => !empty($attributes['first_name']) ? $attributes['first_name'] : null,
			'last_name' => !empty($attributes['last_name']) ? $attributes['last_name'] : null,
			'middle_name' => !empty($attributes['middle_name']) ? $attributes['middle_name'] : null,
			'dob' => !empty($attributes['dob']) ? $attributes['dob'] : null,
			'gender' => !empty($attributes['gender']) ? $attributes['gender'] : null,
			'avatar' => !empty($attributes['avatar']) ? $attributes['avatar'] : null,
		];
		$model->profile()->create($profileAttributes);
		$model->toggleRelationshipMode();
		\DB::commit();
		return $model;
	}

}
