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
use Zbase\Widgets\EntityInterface as WidgetEntityInterface;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends BaseEntity implements
AuthenticatableContract, AuthorizableContract, CanResetPasswordContract, WidgetEntityInterface
{

	use Authenticatable,
	 Authorizable,
	 \Illuminate\Database\Eloquent\SoftDeletes,
	 CanResetPassword;

	const STATUS_OK = 'ok';
	const STATUS_BAN = 'ban';
	const STATUS_LOCK = 'lock';
	const STATUS_BAN_NO_AUTH = 'ban_no_auth';

	/**
	 * Entity name as described in the config
	 * @var string
	 */
	protected $entityName = 'user';

	/**
	 * Return the Role Name
	 * @var string
	 */
	protected $roleName = null;
	protected $address = null;
	protected $userProfile = null;
	protected $remember_token = null;

	/**
	 * LoginAs Button
	 * @return type
	 */
	public function loginAs()
	{
		if(zbase_auth_can_duplex())
		{
			return '<a class="btn btn-sm btn-danger" href="' . zbase_url_from_route('admin.duplex', ['action' => 'duplex', 'id' => $this->id()]) . '" title="Login As ' . $this->displayName() . '">Login As ' . $this->displayName() . '</a>';
		}
	}

	/**
	 * The Entity Id
	 * @return integer
	 */
	public function id()
	{
		return $this->user_id;
	}

	/**
	 * Check if user can create a personal URL
	 *
	 * return boolean
	 */
	public function hasUrl()
	{
		return false;
	}

	/**
	 * The Entity Id
	 * @return integer
	 */
	public function alphaId()
	{
		if(empty($this->alpha_id))
		{
			$this->alpha_id = zbase_generate_hash([$this->user_id, rand(1, 1000), time()], $this->getTable());
			$this->save();
			return $this->user_id;
		}
		return $this->alpha_id;
	}

	public function title()
	{
		return $this->displayName();
	}

	/**
	 * Return the value of the "roles" column
	 */
	public function rolesAttribute()
	{
		return $this->getAttribute('roles');
	}

	/**
	 * Return Default Address
	 * @return UserAddress
	 */
	public function address()
	{
		if(is_null($this->address))
		{
			$this->address = false;
			$cacheKey = zbase_cache_key(zbase_entity($this->entityName()), 'byrelation_address_' . $this->id());
			$this->address = zbase_cache($cacheKey, function(){
				$filter = [
					'is_default' => [
						'eq' => [
							'field' => 'is_default',
							'value' => 1,
						]
					],
					'is_active' => [
						'eq' => [
							'field' => 'is_active',
							'value' => 1,
						]
					],
					'user' => [
						'eq' => [
							'field' => 'user_id',
							'value' => $this->id(),
						]
					],
				];
				$address = zbase_entity('user_address')->repo()->all(['*'], $filter);
				if(!empty($address))
				{
					return $address->first();
				}
				return null;
			}, [$this->entityName()], (60 * 24), ['forceCache' => true, 'driver' => 'file']);
		}
		return $this->address;
	}

	/**
	 * REturn this User City State
	 * @return string
	 */
	public function cityState()
	{
		$address = $this->address();
		if(!empty($address))
		{
			return $address->city . ', ' . $address->state;
		}
		return null;
	}

	/**
	 * REturn this User City State, Country
	 * @return string
	 */
	public function cityStateCountry()
	{
		$address = $this->address();
		if(!empty($address))
		{
			return $address->city . ', ' . $address->state . ', ' . $address->country;
		}
		return null;
	}

	/**
	 * REturn this User Country
	 * @return string
	 */
	public function country()
	{
		$address = $this->address();
		if(!empty($address))
		{
			return $address->country;
		}
		return null;
	}

	/**
	 * Display name and Location
	 */
	public function displayNameLocation()
	{
		return $this->displayName() . ' of ' . $this->cityStateCountry();
	}

	/**
	 * Return the User Profile
	 * @return type
	 */
	public function profile()
	{
		if(!empty($this->id()))
		{
			if(is_null($this->userProfile))
			{
				$cacheKey = zbase_cache_key(zbase_entity($this->entityName()), 'byrelation_profile_' . $this->id());
				$id = $this->id();
				$this->userProfile = zbase_cache($cacheKey, function() use ($id){
					return zbase_entity('user_profile')->repo()->by('user_id', $id)->first();
				}, [$this->entityName()], (60 * 24), ['forceCache' => true, 'driver' => 'file']);
			}
		}
		return $this->userProfile;
	}

	/**
	 * Current Role
	 * @return type
	 */
	public function roleName()
	{
		if(!empty($this->roleName))
		{
			return $this->roleName;
		}
		$roles = $this->getAttribute('roles');
		if(!empty($roles))
		{
			$role = json_decode($roles, true);
			if(!empty($role[0]))
			{
				$this->roleName = $role[0];
				return $this->roleName;
			}
		}
		if(is_null($this->roleName))
		{
			$this->roleName = $this->roles()->first()->role_name;
		}
		return $this->roleName;
	}

	/**
	 * Current Role
	 * @return type
	 */
	public function displayRoleName()
	{
		return ucfirst($this->roleName());
	}

	/**
	 * Current Role Title
	 * @return type
	 */
	public function roleTitle()
	{
		return ucfirst($this->roleName());
	}

	/**
	 * The Username
	 * @return string
	 */
	public function username()
	{
		return $this->username;
	}

	/**
	 * Email address
	 * @return string
	 */
	public function email()
	{
		return $this->email;
	}

	public function displayName()
	{
		$profile = $this->profile();
		if(!empty($profile))
		{
			return ucwords(strtolower($profile->first_name . ' ' . $profile->last_name));
		}
		return null;
	}

	/**
	 * Usable method to displa user information
	 *
	 * @return string
	 */
	public function displayFullDetails($linkable = false)
	{
		if(!empty($linkable) && zbase_auth_is('admin'))
		{
			return '<a href="' . zbase_url_from_route('admin.users', ['action' => 'view', 'id' => $this->id()]) . '" target="_blank">' . $this->displayName() . ' [ID#' . $this->id() . '|' . $this->username() . '|' . $this->email() . '|' . $this->roleName() . ']</a>';
		}
		return '[ID#' . $this->id() . '|' . $this->username() . '|' . $this->email() . '|' . $this->roleName() . ']';
	}

	public function getFirstNameAttribute()
	{
		$profile = $this->profile();
		if(!empty($profile))
		{
			return $profile->first_name;
		}
		return null;
	}

	public function getLastNameAttribute()
	{
		$profile = $this->profile();
		if(!empty($profile))
		{
			return $profile->last_name;
		}
		return null;
	}

	/**
	 * Return a messages based on the Action made
	 * @param boolean $flag
	 * @param string $action create|update|delete|restore|ddelete
	 * @return array
	 */
	public function getActionMessages($action = null)
	{
		if(!empty($this->_actionMessages[$action]))
		{
			return $this->_actionMessages[$action];
		}
		return [];
	}

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
	 * Check if user Can do Authentication
	 * @return boolean
	 */
	public function canAuth()
	{
		if($this->status == self::STATUS_BAN_NO_AUTH)
		{
			zbase_alert('error', $this->getDataOption('status_ban_message', null));
			return false;
		}
		if($this->status == self::STATUS_LOCK)
		{
			zbase_alert('error', $this->getDataOption('status_lock_message', null));
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
		return $this->hasAccess('admin');
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
	 * @proxy $this->orderStatusText
	 * @return string
	 */
	public function statusText()
	{
		$status = zbase_model_name('', 'class.ui.userStatus', \Zbase\Ui\Data\UserStatus::class);
		$status = new $status(['value' => $this->status, 'id' => 'status' . $this->id()]);
		return $status->render();
	}

	/**
	 * @proxy $this->orderStatusText
	 * @return string
	 */
	public function emailVerifiedText()
	{
		$status = zbase_model_name('', 'class.ui.boolean', \Zbase\Ui\Data\Boolean::class);
		$status = new $status(['value' => $this->isEmailVerified(), 'id' => 'emailVerified' . $this->id()]);
		return $status->render();
	}

	/**
	 * Lock the account
	 * @param string $message
	 */
	public function lock($message)
	{
		$this->status = self::STATUS_LOCK;
		$this->setDataOption('status_lock_message', $message);
		$this->save();
	}

	/**
	 * Model-Level after authentication method
	 * @TODO Save a log
	 */
	public function authenticated()
	{

	}

	/**
	 * Return all User Roles in Descending
	 * @return Role[]
	 */
	public function getUserHighestRole()
	{
		return zbase_cache(zbase_cache_key(zbase_entity($this->entityName), 'getUserRoles_' . $this->id()), function(){
			return $this->roles()->orderBy('parent_id', 'DESC')->first();
		}, [zbase_entity($this->entityName)->getTable()], (60 * 24), ['forceCache' => true, 'driver' => 'file']);
	}

	// <editor-fold defaultstate="collapsed" desc="HasAccess">
	/**
	 * Check for access on the resource
	 * @param string|array $access The Access needed
	 * @param string $resource The resource
	 * @return boolean
	 */
	public function hasAccess($access, $resource = null)
	{
		if($access == 'users')
		{
			if(zbase_auth_has())
			{
				return true;
			}
		}
		if(preg_match('/\,/', $access) > 0)
		{
			$accesses = explode(',', $access);
			if(!empty($accesses))
			{
				foreach ($accesses as $access)
				{
					$check = $this->hasAccess($access);
					if(!empty($check))
					{
						return true;
					}
				}
				return false;
			}
		}
		$cacheKey = zbase_cache_key($this, 'hasAccess_' . $access . '_' . $this->id());
		return zbase_cache($cacheKey, function() use ($access){
//			if(!empty($this->attributes['roles']))
//			{
//				$roles = json_decode($this->attributes['roles'], true);
//				foreach ($roles as $role)
//				{
//					$role = zbase_entity('user_roles')->getRoleByName($role);
//					if(strtolower($role) == $access)
//					{
//						return 1;
//					}
//				}
//			}
			/**
			 * only::sudo,user,moderator
			 * comma separated values
			 *
			 * not::sudo,
			 * not for a given rolename
			 *
			 * only::sudo,
			 * will only be for rolename given access
			 *
			 * below::sudo
			 * will only be for users with role below given access
			 *
			 * above::sudo
			 * will only be for users with role above given access
			 *
			 * same::sudo
			 * will only be for users with same level as the given access
			 *
			 * user_id::123
			 */
			if(preg_match('/user_id\:\:/', $access) > 0)
			{
				$access = str_replace('user_id::', '', (int) $access);
				if(zbase_auth_user()->id() == $access)
				{
					return 1;
				}
				return 0;
			}
			if(preg_match('/only\:\:/', $access) > 0)
			{
				$access = str_replace('only::', '', $access);
				$role = zbase_entity('user_roles')->getRoleByName(trim($access));
				$roleClassname = get_class(zbase_entity('user_roles'));
				if($role instanceof $roleClassname)
				{
					$userHighestRole = $this->getUserHighestRole();
					if($userHighestRole->name() == $role->name())
					{
						return 1;
					}
				}
				return 0;
			}
			if(preg_match('/not\:\:/', $access) > 0)
			{
				$access = str_replace('not::', '', $access);
				$role = zbase_entity('user_roles')->getRoleByName(trim($access));
				$roleClassname = get_class(zbase_entity('user_roles'));
				if($role instanceof $roleClassname)
				{
					$userHighestRole = $this->getUserHighestRole();
					if($userHighestRole->name() == $role->name())
					{
						return 0;
					}
				}
				return 1;
			}
			if(preg_match('/below\:\:/', $access) > 0)
			{
				$access = str_replace('below::', '', $access);
				$role = zbase_entity('user_roles')->getRoleByName($access);
				$roleClassname = get_class(zbase_entity('user_roles'));
				if($role instanceof $roleClassname)
				{
					$userHighestRole = $this->getUserHighestRole();
					$roles = $role->below();
					if(!empty($roles))
					{
						foreach ($roles as $r)
						{
							if($r->name() == $userHighestRole->name())
							{
								return 1;
							}
						}
					}
				}
				return 0;
			}
			if(preg_match('/above\:\:/', $access) > 0)
			{
				$access = str_replace('above::', '', $access);
				$role = zbase_entity('user_roles')->getRoleByName($access);
				$roleClassname = get_class(zbase_entity('user_roles'));
				if($role instanceof $roleClassname)
				{
					$userHighestRole = $this->getUserHighestRole();
					$roles = $role->above();
					if(!empty($roles))
					{
						foreach ($roles as $r)
						{
							if($r->name() == $userHighestRole->name())
							{
								return 1;
							}
						}
					}
				}
				return 0;
			}
			if(preg_match('/same\:\:/', $access) > 0)
			{
				$access = str_replace('same::', '', $access);
				$role = zbase_entity('user_roles')->getRoleByName($access);
				$roleClassname = get_class(zbase_entity('user_roles'));
				if($role instanceof $roleClassname)
				{
					$userHighestRole = $this->getUserHighestRole();
					$roles = $role->same();
					if(!empty($roles))
					{
						foreach ($roles as $r)
						{
							if($r->name() == $userHighestRole->name())
							{
								return 1;
							}
						}
					}
				}
				return 0;
			}
			$role = zbase_entity('user_roles')->getRoleByName($access);
			$roleClassname = get_class(zbase_entity('user_roles'));
			if($role instanceof $roleClassname)
			{
				$userHighestRole = $this->getUserHighestRole();
				if($userHighestRole->name() == $role->name())
				{
					return 1;
				}
				$roles = $userHighestRole->same();
				if(!empty($roles))
				{
					foreach ($roles as $r)
					{
						if($r->name() == $role->name())
						{
							return 1;
						}
					}
				}
				$roles = $userHighestRole->below();
				if(!empty($roles))
				{
					foreach ($roles as $r)
					{
						if($r->name() == $role->name())
						{
							return 1;
						}
					}
				}
			}
			return 0;
		}, [$this->entityName], (60 * 24), ['forceCache' => true, 'driver' => 'file']);
	}

	// </editor-fold>

	/**
	 * Return the current authed user
	 * @return User
	 */
	public function currentUser()
	{
		return $this->byId(zbase_auth_user()->id());
	}

	/**
	 *
	 * @param type $task
	 * @param type $msg
	 */
	public function log($task, $msg = null, $options = null)
	{
		try
		{
			if(!empty($msg) || !empty($task))
			{
				zbase_db_transaction_start();
				$data = [
					'remarks' => !empty($msg) ? $msg : null,
					'task' => $task,
					'ip_address' => zbase_ip(),
					'user_id' => $this->id(),
					'type' => 1,
					'created_at' => zbase_date_now(),
					'updated_at' => zbase_date_now(),
					'options' => json_encode($options)
				];
				zbase_entity('user_logs')->insert($data);
				zbase_db_transaction_commit();
			}
		} catch (\Zbase\Exceptions\RuntimeException $e)
		{
			zbase_db_transaction_rollback();
			zbase_exception_throw($e);
		}
	}

	/**
	 * Delete this USER from
	 * DB
	 *
	 * This will is a soft delete
	 */
	public function deleteUser()
	{
		/**
		 * Delete from Addresses
		 * Delete from Profile
		 * Delete from Logs
		 * Delete from notifications
		 * Delete from Roles
		 * Delete from tokens
		 */
		$this->delete();
		$this->clearEntityCacheById();
		$this->clearEntityCacheByTableColumns();
	}

	/**
	 * SEt The User Profile
	 * @param object $userProfile \stdClass
	 * @return \Zbase\Entity\Laravel\User\User
	 */
	public function setUserProfile($userProfile)
	{
		$this->userProfile =  $userProfile;
		return $this;
	}

	/**
	 * SEt The User Profile
	 * @param object $address \stdClass
	 * @return \Zbase\Entity\Laravel\User\User
	 */
	public function setUserAddress($address)
	{
		$this->address =  $address;
		return $this;
	}

	/**
	 * NOT SAVED into DB
	 * Create a user Object based from Array
	 * @param type $user
	 * @return User
	 */
	public function createUserObject($user)
	{
		$this->fill($user);
		$userProfile = new \stdClass();
		$userProfile->first_name = !empty($user['profile']['first_name']) ? $user['profile']['first_name'] : null;
		$userProfile->last_name = !empty($user['profile']['last_name']) ? $user['profile']['last_name'] : null;
		$this->userProfile = $userProfile;
		$this->roleName = $user['role'];
		$address = new \stdClass();
		$address->city = !empty($user['address']['city']) ? $user['address']['city'] : null;
		$address->state = !empty($user['address']['state']) ? $user['address']['state'] : null;
		$address->country = !empty($user['address']['country']) ? $user['address']['country'] : null;
		$this->address = $address;
		return $this;
	}

	// <editor-fold defaultstate="collapsed" desc="Notifications">
	/**
	 * Notify this user
	 * @param string $msg
	 * @param array $options
	 */
	public function notify($msg, $type = 1, $options = [])
	{
		try
		{
			if(!empty($msg))
			{
				zbase_db_transaction_start();
				$data = [
					'remarks' => !empty($msg) ? $msg : null,
					'type' => $type,
					'is_new' => 1,
					'is_seen' => 0,
					'is_read' => 0,
					'user_id' => $this->id(),
					'is_notified' => 0,
					'created_at' => zbase_date_now(),
					'updated_at' => zbase_date_now(),
					'options' => json_encode($options)
				];
				zbase_entity('user_notifications')->insert($data);
				$this->notificationClearCache();
				if(empty($options['telegram_disabled']))
				{
					zbase()->telegram()->send($this, $msg);
				}
				zbase_db_transaction_commit();
			}
		} catch (\Zbase\Exceptions\RuntimeException $e)
		{
			zbase_db_transaction_rollback();
			zbase_exception_throw($e);
		}
	}

	/**
	 * Clear notification caches
	 *
	 * @return void
	 */
	public function notificationClearCache()
	{
		$cacheKey = zbase_cache_key(zbase_entity($this->entityName), 'notifications_latest_' . $this->id());
		zbase_cache_remove($cacheKey, [$this->entityName()], ['driver' => 'file']);
		$cacheKey = zbase_cache_key(zbase_entity($this->entityName), 'notifications_not_notified_' . $this->id());
		zbase_cache_remove($cacheKey, [$this->entityName()], ['driver' => 'file']);
	}

	/**
	 * Notifications all seen
	 *
	 * @return void
	 */
	public function notificationSeen()
	{
		zbase_entity('user_notifications')->where('user_id', $this->id())->update(['is_seen' => 1, 'is_notified' => 1]);
		$this->notificationClearCache();
	}

	/**
	 * Return 10 latests users' Notifications
	 *
	 * @return Notification[]
	 */
	public function notificationsLatest()
	{
		$cacheKey = zbase_cache_key(zbase_entity($this->entityName), 'notifications_latest_' . $this->id());
		return zbase_cache($cacheKey, function(){
			$filters = [
				'user_id' => [
					'eq' => [
						'field' => 'user_id',
						'value' => $this->id()
					]
				]
			];
			$joins = [];
			$sorting = ['created_at' => 'DESC'];
			$selects = ['*'];
			$paginate = 10;
			return zbase_entity('user_notifications')->repo()->all($selects, $filters, $sorting, $joins, $paginate);
		}, [zbase_entity($this->entityName)->getTable()], (60 * 24), ['forceCache' => true, 'driver' => 'file']);
	}

	/**
	 * Return the NotNotified notifications
	 * These are the notification counts that are displayed
	 *
	 * @return Notification[]
	 */
	public function notificationsNotNotified()
	{
		//$cacheKey = zbase_cache_key(zbase_entity($this->entityName), 'notifications_not_notified_' . $this->id());
		//return zbase_cache($cacheKey, function(){
			$filters = [
				'user_id' => [
					'eq' => [
						'field' => 'user_id',
						'value' => $this->id()
					]
				],
				'is_notified' => [
					'eq' => [
						'field' => 'is_notified',
						'value' => 0
					]
				],
			];
			$rows = zbase_entity('user_notifications')->repo()->all(['*'],$filters);
			if(count($rows) > 0)
			{
				$this->notificationClearCache();
			}
			return $rows;
		//}, [zbase_entity($this->entityName)->getTable()], (60 * 24), ['forceCache' => true, 'driver' => 'file']);
	}

	// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc="CREATE">

	/**
	 * Save a new model and return the instance.
	 *
	 * @param  array  $attributes
	 * @return static
	 */
	public static function create(array $attributes = [])
	{

		try
		{
			$logMsg = [];
			if(empty($attributes['profile']))
			{
				$userAttributes = [];
				$attributes['profile'] = [];
				$attributesAddress = ['city' => ''];
				$profileColumns = zbase_entity('user_profile')->getColumns();
				$addressColumns = zbase_entity('user_address')->getColumns();
				$userColumns = zbase_entity('user')->getColumns();

				foreach ($attributes as $attName => $attValue)
				{
					if(array_key_exists($attName, $profileColumns))
					{
						$attributes['profile'][$attName] = $attValue;
						unset($attributes[$attName]);
					}
					if(array_key_exists($attName, $addressColumns))
					{
						$attributesAddress[$attName] = $attValue;
						unset($attributes[$attName]);
					}
					if(array_key_exists($attName, $userColumns))
					{
						$userAttributes[$attName] = $attValue;
					}
				}
			}
			if(!empty($attributes['email']))
			{
				$logMsg[] = 'Email: ' . $attributes['email'];
			}
			zbase_db_transaction_start();
			if(!empty($attributes['profile']))
			{
				$logMsg[] = 'Attribute profile found';
				$attributesProfile = $attributes['profile'];
				unset($attributes['profile']);
			}
			$model = zbase_entity('user');
			$model->fill($userAttributes);
			$model->toggleRelationshipMode();
			if(!empty($attributes['password']))
			{
				$logMsg[] = 'Attribute password found';
				$model->password = $attributes['password'];
			}
			if(!empty($attributes['status']))
			{
				$logMsg[] = 'Attribute status found';
				$model->status = $attributes['status'];
			}
			$role = self::roles()->getRelated()->repository()->by('role_name', !empty($attributes['role']) ? $attributes['role'] : zbase_config_get('auth.role.default', 'user'))->first();
			$model->save();
			if(!empty($role))
			{
				$model->roles()->save($role);
				$logMsg[] = 'Role: ' . $role->name() . ' saved!';
				$model->alpha_id = zbase_generate_hash([$model->user_id, rand(1, 1000), time()], $model->getTable());
				$model->roles = json_encode([$role->role_name]);
				if(!empty($attributesProfile))
				{
					\Eloquent::unguard();
					$profileAttributes = [
						'first_name' => !empty($attributesProfile['first_name']) ? $attributesProfile['first_name'] : null,
						'last_name' => !empty($attributesProfile['last_name']) ? $attributesProfile['last_name'] : null,
						'middle_name' => !empty($attributesProfile['middle_name']) ? $attributesProfile['middle_name'] : null,
						'dob' => !empty($attributesProfile['dob']) ? $attributesProfile['dob'] : null,
						'gender' => !empty($attributesProfile['gender']) ? $attributesProfile['gender'] : null,
						'avatar' => !empty($attributesProfile['avatar']) ? $attributesProfile['avatar'] : 'http://api.adorable.io/avatars/285/' . $model->alpha_id . '.png'
					];
					$profileAttributes = array_replace_recursive($attributesProfile, $profileAttributes);
					$profileAttributes['user_id'] = $model->id();
					$model->avatar = $profileAttributes['avatar'];
					zbase_entity('user_profile')->fill($profileAttributes)->save();
					$logMsg[] = 'Profile saved!';
				}
				/**
				 * Save Addresses
				 */
				if(!empty($attributesAddress))
				{
					$attributesAddress['is_active'] = 1;
					$attributesAddress['is_default'] = 1;
					$attributesAddress['type'] = 'home';
					$attributesAddress['user_id'] = $model->id();
					if(!empty($attributes['cityb']))
					{
						$attributesAddress['city'] = $attributes['cityb'];
					}
					if(!empty($attributesAddress['state']) && !empty($attributesAddress['country']) && !empty($attributesAddress['city']))
					{
						$model->location = $attributesAddress['city'] . ', ' . $attributesAddress['state'] . ', ' . $attributesAddress['country'];
					}
					zbase_entity('user_address')->fill($attributesAddress)->save();
					$logMsg[] = 'Address saved!';
				}
				$model->save();
				$model->toggleRelationshipMode();
				if($model->sendWelcomeMessage($attributes))
				{
					$logMsg[] = 'Welcome message sent!';
				}
				$logMsg[] = 'User saved!';
				$model->log('Register');
				zbase_log(implode(PHP_EOL, $logMsg), null, __METHOD__);
				zbase_db_transaction_commit();
				return $model;
			}
			else
			{
				$logMsg[] = 'Role is empty. zbase_db_transaction_rollback()';
				zbase_log(implode(PHP_EOL, $logMsg), null, __METHOD__);
				zbase_db_transaction_rollback();
				throw new \Zbase\Exceptions\RuntimeException(_zt('User Role given not found.'));
			}
		} catch (\Zbase\Exceptions\RuntimeException $e)
		{
			zbase_exception_throw($e);
		}
		return false;
	}

	/**
	 * Generate Password for this user
	 * @return string
	 */
	public function generatePassword()
	{
		return zbase_generate_password();
	}

	/**
	 * SEnd the welcome message
	 * @param array $attributes The Original Attributes
	 */
	public function sendWelcomeMessage($attributes)
	{
		zbase_db_transaction_start();
		try
		{
			$code = null;
			if($this->passwordAutoGenerate())
			{
				zbase_alert('info', _zt('We sent an email to %email% with your login information.', ['%email%' => $this->email()]));
			}
			else
			{
				if($this->emailVerificationEnabled())
				{
					$code = zbase_generate_code();
					$this->setDataOption('email_verification_code', $code);
					zbase_alert('info', _zt('We sent an email to %email% with a link to complete your registration.', ['%email%' => $this->email()]));
					$this->save();
				}
			}
			$subject = zbase_config_get('email.account.new.subject', _zt('Welcome to ' . zbase_site_name() . '!'));
			zbase_messenger_email($this->email(), 'account-noreply', $subject, zbase_view_file_contents('email.account.new'), ['entity' => $this, 'code' => $code, 'attributes' => $attributes]);
			zbase_db_transaction_commit();
			return true;
		} catch (\Zbase\Exceptions\RuntimeException $e)
		{
			zbase_db_transaction_rollback();
			return false;
		}
	}

	/**
	 * default new user status
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

	/**
	 * If email verification is enabled
	 * @return boolean
	 */
	public function emailVerificationEnabled()
	{
		return zbase_config_get('auth.emailverify.enable', true);
	}

	/**
	 * Check if system has to generate the password
	 * for the user.
	 * When signing up, we don't require password from the user
	 * @return boolean
	 */
	public function passwordAutoGenerate()
	{
		return zbase_config_get('auth.register.password.required', false);
	}

	/**
	 * Check if to login user after successfull registration
	 *
	 * @return boolean
	 */
	public function loginAfterRegister()
	{
		return zbase_config_get('auth.register.login', true);
	}

	/**
	 * If username is enabled
	 * @return boolean
	 */
	public function usernameEnabled()
	{
		return zbase_config_get('auth.username.enable', true);
	}

	// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc="Image">

	public function profileFolder()
	{
		return zbase_storage_path() . '/' . zbase_tag() . '/user/' . $this->id() . '/';
	}

	/**
	 * Upload a file for this node
	 * @param string $index The Upload file name/index or the URL to file to download and save
	 * @return void
	 */
	public function uploadProfileImage($index = 'file')
	{
		try
		{
			$folder = $this->profileFolder();
			zbase_directory_check($folder, true);
			$filename = md5($this->alphaId() . time());
			$uploadedFile = zbase_file_upload_image($index, $folder, $filename, zbase_config_get('node.files.image.format', 'png'));
			if(!empty($uploadedFile) && zbase_file_exists($uploadedFile))
			{
				if(!empty($this->avatar) && is_dir($folder) && file_exists($folder . $this->avatar))
				{
					unlink($folder . $this->avatar);
				}
				return basename($uploadedFile);
			}
		} catch (\Zbase\Exceptions\RuntimeException $e)
		{
			zbase_exception_throw($e);
		}
	}

	/**
	 * cATEGORY iMAGE uRL
	 * @return type
	 */
	public function avatarUrl($options = [])
	{
		if(empty($this->avatar))
		{
			return 'http://api.adorable.io/avatars/150/' . $this->email();
		}
		if(preg_match('/http/', $this->avatar) == 1)
		{
			return str_replace('http://', '//', $this->avatar);
		}
		$fullImage = false;
		$params['id'] = $this->alphaId();
		$params['image'] = $this->avatar;
		if(empty($options) || !empty($options['full']))
		{
			$fullImage = true;
		}
		$params['w'] = !empty($options['w']) ? $options['w'] : 150;
		$params['h'] = !empty($options['h']) ? $options['h'] : 0;
		$params['q'] = !empty($options['q']) ? $options['q'] : 80;
		if(!empty($options['thumbnail']))
		{
			$params['w'] = !empty($options['w']) ? $options['w'] : (property_exists($this, 'thWidth') ? $this->thWidth : 150);
			$params['h'] = !empty($options['h']) ? $options['h'] : (property_exists($this, 'thHeight') ? $this->thHeight : 0);
			$params['q'] = !empty($options['q']) ? $options['q'] : (property_exists($this, 'thQuality') ? $this->thQuality : 80);
		}
		$params['ext'] = zbase_config_get('node.files.image.format', 'png');
		return zbase_url_from_route('userImage', $params);
	}

	/**
	 * Serve the File
	 * @param integer $width
	 * @param integer $height
	 * @param integer $quality Image Quality
	 * @param boolean $download If to download
	 * @return boolean
	 */
	public function serveImage($width, $height = null, $quality = null, $download = false, $image = null)
	{
		$folder = zbase_storage_path() . '/' . zbase_tag() . '/user/' . $this->id() . '/';
		if(!empty($image))
		{
			$path = $folder . $image;
		}
		else
		{
			$path = $folder . $this->avatar;
		}

		if(!class_exists('\Image'))
		{
			$image = zbase_file_serve_image($path, $width, $height, $quality, $download);
			if(!empty($image))
			{
				return \Response::make(readfile($image['src'], $image['size']))->header('Content-Type', $image['mime']);
			}
			return zbase_abort(404);
		}

		// dd($this, $path, file_exists($path));
		if(file_exists($path))
		{
			$cachedImage = \Image::cache(function($image) use ($width, $height, $path){
						if(empty($width))
						{
							$size = getimagesize($path);
							$width = $size[0];
							$height = $size[1];
						}
						if(!empty($width) && empty($height))
						{
							return $image->make($path)->resize($width, null, function($constraint)
						{
										$constraint->upsize();
										$constraint->aspectRatio();
						});
						}
						if(empty($width) && !empty($height))
						{
							return $image->make($path)->resize(null, $height, function($constraint)
						{
										$constraint->upsize();
										$constraint->aspectRatio();
						});
						}
						return $image->make($path)->resize($width, $height);
				});
			return \Response::make($cachedImage, 200, array('Content-Type' => 'image/png'));
		}
		return false;
	}

	// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc="WidgetController">
	/**
	 * Widget entity interface.
	 * 	Data should be validated first before passing it here
	 * @param string $method post|get
	 * @param string $action the controller action
	 * @param array $data validated; assoc array
	 * @param Zbase\Widgets\Widget $widget
	 */
	public function widgetController($method, $action, $data, \Zbase\Widgets\Widget $widget)
	{
		if(strtolower($method) == 'post')
		{
			$isAdmin = zbase_auth_user()->isAdmin();
			if($action == 'status' && !empty($isAdmin))
			{
				if(!empty($data['role']))
				{
					$this->updateRole($data['role']);
				}
				if(!empty($data['status']))
				{
					$this->status = $data['status'];
					$this->save();
					zbase()->json()->setVariable('_html_selector_replace', ['#status' . $this->id() => $this->statusText()], true);
				}
				$this->clearEntityCacheById();
				$this->clearEntityCacheByTableColumns();
				zbase_alert('info', _zt('User Account Updated.'));
				return true;
			}
			if($action == 'image')
			{
				$profileImage = $this->uploadProfileImage();
				if(!empty($profileImage))
				{
					$this->profile()->avatar = $profileImage;
					$this->profile()->save();
					$this->avatar = $profileImage;
					$this->save();
					$this->clearEntityCacheById();
					$this->clearEntityCacheByTableColumns();
					zbase_alert('info', _zt('Profile image saved.'));
					return true;
				}
				return false;
			}
			if($action == 'username')
			{
				if(!empty($data['username']))
				{
					if($this->username() != $data['username'])
					{
						$this->updateUsername($data['username']);
						$this->clearEntityCacheById();
						$this->clearEntityCacheByTableColumns();
						return true;
					}
				}
				return false;
			}
			if($action == 'email')
			{
				if(!empty($data['email']))
				{
					if($this->email() != $data['email'])
					{
						$this->updateRequestEmailAddress($data['email']);
						$this->clearEntityCacheById();
						$this->clearEntityCacheByTableColumns();
						return true;
					}
				}
				return false;
			}
			if($action == 'password')
			{
				if(!empty($data['password']) && !empty($data['password']) && zbase_auth_user()->id() == $this->id())
				{
					if(zbase_bcrypt_check($data['password'], $this->password))
					{
						$this->updateRequestPassword();
						zbase()->json()->addVariable('redirect', zbase_url_from_route('logout'));
						$this->clearEntityCacheById();
						$this->clearEntityCacheByTableColumns();
						return true;
					}
				}
				if(!empty($data['password']) && !empty($data['password_confirmation']) && zbase_auth_is('admin'))
				{
					$this->updatePassword($data['password']);
					$this->clearEntityCacheById();
					$this->clearEntityCacheByTableColumns();
					return true;
				}
				return false;
			}
			if($action == 'profile')
			{
				$this->updateProfile($data);
				return true;
			}
			if($action == 'phone')
			{
				$this->updateAddress($data);
				zbase_alert('info', _zt('Contact Info saved.'));
				return true;
			}
			if($action == 'address')
			{
				$this->updateAddress($data);
				zbase_alert('info', _zt('Address Info saved.'));
				return true;
			}
		}
	}

	// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc="QuerySearchFilters">

	/**
	 * Return SELECTs
	 * @param array $filters
	 */
	public function querySelects($filters)
	{
		return ['*'];
	}

	/**
	 * Join Query
	 * @param array $filters Array of Filters
	 * @param array $sorting Array of Sorting
	 * @param array $options some options
	 * @return array
	 */
	public function queryJoins($filters, $sorting = [], $options = [])
	{
		$orderEntityName = $this->getOrderEntityName();
		$joins = [];
		$joins[] = [
			'type' => 'join',
			'model' => 'users_address as address',
			'foreign_key' => 'users.user_id',
			'local_key' => 'address.user_id',
		];
		$joins[] = [
			'type' => 'join',
			'model' => 'users_profile as profile',
			'foreign_key' => 'users.user_id',
			'local_key' => 'profile.user_id',
		];
		$joins[] = [
			'type' => 'join',
			'model' => 'users_roles as role',
			'foreign_key' => 'users.user_id',
			'local_key' => 'role.user_id',
		];
		$joins[] = [
			'type' => 'join',
			'model' => 'user_roles as rolename',
			'foreign_key' => 'role.role_id',
			'local_key' => 'rolename.role_id',
		];
		return $joins;
	}

	public function queryFilters($filters, $sorting, $options = [])
	{
		if($options['widget']->id() == 'mlmdirectreferrals-admin')
		{

		}
		if($options['widget']->id() != 'admin-users')
		{
			if(!empty($filters))
			{
				foreach ($filters as $index => $filter)
				{
					foreach ($filter as $wher => $op)
					{
						if(!empty($op['field']) && preg_match('/\./', $op['field']) == 0)
						{
							$filters[$index][$wher]['field'] = 'users.' . $op['field'];
						}
					}
				}
			}
			return $filters;
		}
		return [];
	}

	/**
	 * Sorting Query
	 * @param array $sorting Array of Sorting
	 * @param array $filters Array of Filters
	 * @param array $options some options
	 * @return array
	 */
	public function querySorting($sorting, $filters = [], $options = [])
	{
		$sort = ['users.created_at' => 'DESC'];
		return $sort;
	}

	/**
	 * Join Query
	 * @param array $filters Array of Filters
	 * @param array $sorting Array of Sorting
	 * @param array $options some options
	 * @return array
	 */
	public function querySearchFilters($filters, $options = [])
	{
		$query = zbase_request_input('adminUsersSearchQuery', (!empty($options['query']) ? $options['query'] : null));
		if(!empty($query))
		{
			$queries = [];
			if(preg_match('/\,/', $query) > 0)
			{
				$queries = explode(',', $query);
			}
			else
			{
				$queries[] = $query;
			}
			foreach ($queries as $query)
			{
				/**
				 * Searching for Role
				 */
				if(preg_match('/role\:/', $query) > 0)
				{
					$stringFound = true;
					$filters['rolename.role_name'] = [
						'like' => [
							'field' => 'rolename.role_name',
							'value' => '%' . trim(str_replace('role:', '', $query)) . '%'
						]
					];
				}
				/**
				 * Searching for City
				 */
				if(preg_match('/city\:/', $query) > 0)
				{
					$stringFound = true;
					$filters['address.city'] = [
						'like' => [
							'field' => 'address.city',
							'value' => '%' . trim(str_replace('city:', '', $query)) . '%'
						]
					];
				}
				/**
				 * Searching for State
				 */
				if(preg_match('/state\:/', $query) > 0)
				{
					$stringFound = true;
					$filters['address.state'] = [
						'like' => [
							'field' => 'address.state',
							'value' => '%' . trim(str_replace('state:', '', $query)) . '%'
						]
					];
				}
				/**
				 * Searching for Country
				 */
				if(preg_match('/country\:/', $query) > 0)
				{
					$country = trim(str_replace('country:', '', $query));
					if(strlen($country) > 2)
					{
						$country = \Zbase\Utility\Geo::countryNameToCountryCode($country);
					}
					$stringFound = true;
					$filters['address.country'] = [
						'like' => [
							'field' => 'address.country',
							'value' => '%' . $country . '%'
						]
					];
				}
				/**
				 * Searching for Name
				 */
				if(preg_match('/name\:/', $query) > 0)
				{
					$stringFound = true;
					$filters['name'] = function($q) use ($query){
						$name = trim(str_replace('name:', '', $query));
						return $q->orWhere('profile.first_name', 'LIKE', '%' . $name . '%')
										->orWhere('profile.last_name', 'LIKE', '%' . $name . '%');
				 };
				}
				/**
				 * Searching for Email
				 */
				if(preg_match('/\@/', $query) > 0)
				{
					$stringFound = true;
					$filters['users.email'] = [
						'eq' => [
							'field' => 'users.email',
							'value' => $query
						]
					];
				}
				/**
				 * Searching Id
				 */
				if(is_numeric($query))
				{
					$stringFound = true;
					$filters['users.user_id'] = [
						'eq' => [
							'field' => 'users.user_id',
							'value' => intval($query)
						]
					];
				}
				$country = \Zbase\Utility\Geo::countryNameToCountryCode($query);
				if(!empty($country))
				{
					$stringFound = true;
					$filters['address.country'] = [
						'like' => [
							'field' => 'address.country',
							'value' => '%' . $country . '%'
						]
					];
				}
				if(empty($stringFound))
				{
					$filters['users.name'] = function($q) use ($query){
						return $q->orWhere('users.name', 'LIKE', '%' . $query . '%')
										->orWhere('users.location', 'LIKE', '%' . $query . '%')
										->orWhere('users.email', 'LIKE', '%' . $query . '%')
										->orWhere('users.username', 'LIKE', '%' . $query . '%');
					};
				}
			}
		}
		return $filters;
	}

	// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc="Address">
	public function updateAddress($data)
	{
		try
		{
			zbase_db_transaction_start();
			if(!empty($data))
			{
				$address = $this->address();
				$saved = $address->update($data);
				if(!empty($saved))
				{
					$this->clearEntityCacheByTableColumns();
					$this->clearEntityCacheById();
					$this->log('user::updateAddress');
					zbase_db_transaction_commit();
					return true;
				}
			}
		} catch (\Zbase\Exceptions\RuntimeException $e)
		{
			zbase_db_transaction_rollback();
			return false;
		}
	}

	// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc="UPDATE Role">
	/**
	 * Update role
	 * @param string $role The Role Name
	 */
	public function updateRole($role)
	{
		$role = $this->roles()->getRelated()->repository()->by('role_name', $role)->first();
		if(!empty($role))
		{
			\DB::table('users_roles')->where('user_id', $this->id())->delete();
			\DB::table('users_roles')->insert(['user_id' => $this->id(), 'role_id' => $role->id()]);
			$userRoles = [$role->role_name];
			$this->roles = json_encode($userRoles);
			$this->save();
			$this->notify('Role changed into ' . $role->role_name);
			zbase()->json()->setVariable('_html_selector_replace', ['.userDisplayName' . $this->id() => $this->roleTitle() . ' - ' . $this->id() . ': ' . $this->displayName()], true);
			$this->clearEntityCacheByTableColumns();
			$this->clearEntityCacheById();
		}
	}

	// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc="UPDATE PRofile">

	/**
	 * Update Profile
	 * @param $data
	 */
	public function updateProfile($data)
	{
		try
		{
			zbase_db_transaction_start();
			$fillables = $this->profile()->getFillable();
			if(!empty($fillables))
			{
				$newData = [];
				if(!empty($data['avatar']))
				{
					$newData['avatar'] = $data['avatar'];
				}
				else
				{
					$profileImage = $this->uploadProfileImage();
					if(!empty($profileImage))
					{
						$newData['avatar'] = $profileImage;
						$this->avatar = $profileImage;
						$this->save();
					}
				}
				$userProfile = $this->profile();
				foreach ($data as $key => $val)
				{
					if(in_array($key, $fillables))
					{
						$newData[$key] = $val;
					}
				}
				if(!empty($newData))
				{
					if(!empty($newData['first_name']))
					{
						$this->name = $newData['first_name'] . ' ' . $newData['last_name'];
						$this->save();
					}
					$saved = $userProfile->update($newData);
					if(!empty($saved))
					{
						$this->alertProfileUpdated();
						$this->clearEntityCacheByTableColumns();
						$this->clearEntityCacheById();
						$this->log('user::updateProfile');
						zbase()->json()->setVariable('_html_selector_replace', ['.userDisplayName' . $this->id() => $this->displayName()], true);
						zbase_db_transaction_commit();
						return true;
					}
				}
			}
		} catch (\Zbase\Exceptions\RuntimeException $e)
		{
			zbase_db_transaction_rollback();
			return false;
		}
	}

	/**
	 * Profile Was Updated,. Alerrt
	 */
	public function alertProfileUpdated()
	{
		if(zbase_auth_user()->id() !== $this->id())
		{
			zbase_alert('success', '<strong>' . _zt($this->displayName() . '</strong> profile was updated successfully.'));
		}
		else
		{
			zbase_alert('success', _zt('Your profile was updated successfully.'));
		}
	}

	// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc="LOST Password">
	public function lostPassword()
	{
		$this->log('user::lostPassword');
		zbase_alert(\Zbase\Zbase::ALERT_INFO, 'A link to reset your password was sent to your email address. Kindly check.');
		if(zbase_is_dev())
		{
			$code = \DB::table('user_tokens')->where('email', $this->email())->first();
			if(!empty($code))
			{
				$url = zbase_url_from_route('password-reset', ['token' => $code->token]);
				zbase()->json()->setVariable('password_reset_url', $url);
			}
		}
	}

	// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc="UPDATE Password">
	/**
	 * Password has been resetted
	 * @param string $password The RAW Password
	 * @param boolean $account Password was updated from the account section
	 */
	public function updatePassword($password, $account = false)
	{
		try
		{
			zbase_db_transaction_start();
			$this->password = zbase_bcrypt($password);
			$this->password_updated_at = zbase_date_now();
			$this->save();
			if(zbase_auth_is('admin'))
			{
				zbase_alert('info', _zt($this->displayFullDetails() . ' Password successfully updated.'));
			}
			else
			{
				zbase_alert('info', _zt('Password successfully updated.'));
			}
			$this->log('user::updatePassword', null, ['password' => $this->password]);
			zbase_db_transaction_commit();
			return true;
		} catch (\Zbase\Exceptions\RuntimeException $e)
		{
			zbase_db_transaction_rollback();
			return false;
		}
	}

	/**
	 * Check that the password was not used from the past
	 * @param string $password The RAW password
	 * @return boolena True was not used; false already used before
	 *
	 * @TODO Check from the logs
	 */
	public function checkNewPassword($password)
	{
		return true;
	}

	/**
	 * First step, Update password
	 * 	Will send an email with a link to complete the process of updating the password
	 * @param string $newPassword The new RAW password
	 * @return boolean
	 */
	public function updateRequestPassword()
	{
		zbase_db_transaction_start();
		try
		{
			$code = zbase_generate_code(64);
			\DB::table('user_tokens')->insert(['email' => $this->email(), 'token' => $code, 'created_at' => zbase_date_now(), 'user_id' => $this->id()]);
			$this->save();
			$url = zbase_url_from_route('password-reset', ['token' => $code]);
			$urlText = null;
			if(zbase_is_dev())
			{
				$urlText = '<a href="' . $url . '">' . $url . '</a>';
				zbase()->json()->setVariable('updateRequestPassword', $url);
			}
			zbase_alert('info', _zt('We sent an email to <strong>%email%</strong> with a link to complete the process of updating your password. ' . $urlText, ['%email%' => $this->email()]));
			zbase_messenger_email($this->email(), 'account-noreply', _zt('New Password update request'), zbase_view_file_contents('email.account.newPasswordRequest'), ['entity' => $this, 'code' => $code, 'url' => $url]);
			$this->log('user::updateRequestPassword', null, ['email' => $this->email()]);
			zbase_db_transaction_commit();
			return true;
		} catch (\Zbase\Exceptions\RuntimeException $e)
		{
			zbase_db_transaction_rollback();
			return false;
		}
	}

	/**
	 * Check Update Request Password code
	 * @param string $code The Update request for password code
	 * @return boolean
	 */
	public function checkUpdateRequestPasswordCode($code)
	{
		$updateCodes = $this->getDataOption('password_update_code', []);
		if(!empty($updateCodes))
		{
			return in_array($code, $updateCodes);
		}
		return false;
	}

	// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc="UPDATE Username">

	/**
	 * Update Username
	 * @param string $newUsername
	 * @return boolean
	 */
	public function updateUsername($newUsername)
	{
		if($this->username() != $newUsername)
		{
			$oldUsernames = $this->getDataOption('username_old', []);
			$oldUsernames[] = [
				'old' => $this->username(),
				'date' => zbase_date_now(),
				'ip' => zbase_ip(),
				'new' => $newUsername
			];
			$oldUsername = $this->username();
			$this->setDataOption('username_old', $oldUsernames);
			$this->username = $newUsername;
			$this->save();
			/**
			 * Admin is changing the email Address
			 */
			if(zbase_auth_user()->id() != $this->id())
			{
				zbase_alert('success', _zt('<strong>' . $this->displayFullDetails() . '</strong> username updated successfully!'));
			}
			else
			{
				zbase_alert('success', _zt('Username updated!'));
				zbase_messenger_email($this->email(), 'account-noreply', _zt('Username was changed'), zbase_view_file_contents('email.account.updateUsername'), ['entity' => $this, 'old' => $oldUsername, 'new' => $newUsername]);
			}
			$this->log('user::updateUsername');
		}
	}

	// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc="UPDATE Email Address">
	public function resendEmailVerificationCode()
	{
		if($this->emailVerificationEnabled())
		{
			$code = zbase_generate_code();
			$this->setDataOption('email_verification_code', $code);
			$this->save();
			$msg = _zt('We sent an email to %email% to verify your new email address.', ['%email%' => $this->email()]);
			zbase_messenger_email($this->email(), 'account-noreply', _zt('Email address verification code'), zbase_view_file_contents('email.account.newEmailAddressVerification'), ['entity' => $this, 'code' => $code, 'newEmailAddress' => $this->email()]);
			if(zbase_is_dev())
			{
				$url = zbase_url_from_route('email-verify', ['email' => $this->email(), 'code' => $code]);
				$msg .= ' <a href="' . $url . '">' . $url . '</a>';
			}
			zbase_alert('info', $msg);
			$this->log('user::resendEmailVerificationCode', null, ['email' => $this->email()]);
			$this->clearEntityCacheById();
			$this->clearEntityCacheByTableColumns();
			return true;
		}
		return false;
	}

	/**
	 * First step in updating email address.
	 * 	- Code will be sent to the old email address with a verification code
	 * @param string $newEmailAddress
	 * @return boolean
	 */
	public function updateRequestEmailAddress($newEmailAddress)
	{
		if(!empty($newEmailAddress) && $this->email() != $newEmailAddress)
		{
			zbase_db_transaction_start();
			try
			{
				/**
				 * Admin is changing the email Address
				 */
				if(zbase_auth_user()->id() != $this->id() && zbase_auth_user()->isAdmin())
				{
					/**
					 * Send a request code to the old email address
					 * option: email_verification_code: code; new_email: $newEmail Address
					 */
					$oldEmail = $this->email();
					$this->email = $newEmailAddress;
					$this->email_verified = 0;
					$this->save();
					zbase_alert('info', _zt($this->displayFullDetails() . ' email address was updated to <strong>%email%</strong> from <strong>' . $oldEmail . '</strong>.', ['%email%' => $this->email()]));
					$this->log('user::updateRequestEmailAddress', null, ['new_email' => $newEmailAddress, 'admin_id' => zbase_auth_user()->id()]);
				}
				else
				{
					/**
					 * Send a request code to the old email address
					 * option: email_verification_code: code; new_email: $newEmail Address
					 */
					$code = zbase_generate_code();
					$this->setDataOption('email_updaterequest_code', $code);
					$this->setDataOption('email_new', $newEmailAddress);
					$this->setDataOption('email_new_request_date', zbase_date_now());
					$this->save();
					$url = zbase_url_from_route('update-email-request', ['email' => $this->email(), 'token' => $code]);
					$urlText = null;
					if(zbase_is_dev())
					{
						$urlText = '<a href="' . $url . '">' . $url . '</a>';
						zbase()->json()->setVariable('updateRequestEmailAddress', $url);
					}
					zbase_alert('info', _zt('We sent an email to %email% with a link to complete the process of updating your email address.' . $urlText, ['%email%' => $this->email()]));
					zbase_messenger_email($this->email(), 'account-noreply', _zt('New Email address update request'), zbase_view_file_contents('email.account.newEmailAddressRequest'), ['entity' => $this, 'newEmailAddress' => $newEmailAddress, 'code' => $code, 'url' => $url]);
					$this->log('user::updateRequestEmailAddress', null, ['new_email' => $newEmailAddress]);
				}
				zbase_db_transaction_commit();
				return true;
			} catch (\Zbase\Exceptions\RuntimeException $e)
			{
				zbase_db_transaction_rollback();
				return false;
			}
		}
		else
		{
			zbase_alert('info', _zt('We sent an email to %email% with a link to complete the process of updating your email address.', ['%email%' => $this->email()]));
		}
	}

	/**
	 * Second step in updating email address
	 * Check if code and email matched
	 * @see $this->updateRequestEmailAddress
	 *
	 * @param string $code The request code
	 * @return boolean
	 */
	public function checkEmailRequestUpdate($code)
	{
		$verification = $this->getDataOption('email_updaterequest_code', null);
		if(!is_null($verification))
		{
			if($verification == $code)
			{
				$this->unsetDataOption('email_updaterequest_code');
				return $this->updateEmailAddress();
			}
		}
		return false;
	}

	/**
	 * Third/Final step in updating email address
	 *
	 */
	public function updateEmailAddress()
	{
		$newEmail = $this->getDataOption('email_new', null);
		if(!is_null($newEmail))
		{
			zbase_db_transaction_start();
			try
			{
				$oldEmail = $this->email();
				$oldEmails = $this->getDataOption('email_old', []);
				$oldEmails[] = [
					'old' => $this->email(),
					'date' => zbase_date_now(),
					'ip' => zbase_ip(),
					'new' => $newEmail
				];
				//$this->setDataOption('email_old', $oldEmails);
				$emailVerificationEnabled = zbase_config_get('auth.emailverify.enable', true);
				$this->email = $newEmail;
				$this->email_verified = $emailVerificationEnabled ? 0 : 1;
				$this->email_verified_at = null;
				if(!empty($emailVerificationEnabled))
				{
					$code = zbase_generate_code();
					$this->setDataOption('email_verification_code', $code);
					zbase_alert('info', _zt('Successfully updated your email address. We sent an email to <strong>%email%</strong> to verify your new email address.', ['%email%' => $newEmail]));
					zbase_messenger_email($this->email(), 'account-noreply', _zt('Email address verification code'), zbase_view_file_contents('email.account.newEmailAddressVerification'), ['entity' => $this, 'code' => $code, 'newEmailAddress' => $newEmail]);
				}
				else
				{
					zbase_alert('info', _zt('Successfully updated your email address to <strong>' . $newEmail . '</strong>', ['%email%' => $newEmail]));
				}
				/**
				 * Remove options on updating email address
				 */
				$this->unsetDataOption('email_new');
				$this->unsetDataOption('email_new_request_date');
				$this->save();
				$this->log('user::updateEmailAddress', null, ['old_email' => $oldEmail]);
				zbase_db_transaction_commit();
				return true;
			} catch (\Zbase\Exceptions\RuntimeException $e)
			{
				zbase_db_transaction_rollback();
				return false;
			}
		}
		return false;
	}

	/**
	 * Verify email address
	 * @param string $code
	 * @return boolean
	 */
	public function verifyEmailAddress($code)
	{
		try
		{
			$verificationCode = $this->getDataOption('email_verification_code', null);
			if(!is_null($code) && $code == $verificationCode)
			{
				$oldEmails = $this->getDataOption('email_old');
				if(is_array($oldEmails))
				{
					$i = 0;
					foreach ($oldEmails as $e)
					{
						if($e['new'] == $this->email())
						{
							$e['verify'] = zbase_date_now();
							$e['verify_ip'] = zbase_ip();
							$oldEmails[$i] = $e;
						}
						$i++;
					}
				}
				if(!empty($oldEmails))
				{
					$this->setDataOption('email_old', $oldEmails);
				}
				$this->unsetDataOption('email_verification_code');
				$this->email_verified = 1;
				$this->email_verified_at = zbase_date_now();
				$this->log('user::verifyEmailAddress');
				$this->save();
				zbase_alert('info', _zt('Your email address <strong>%email%<strong> is now verified.', ['%email%' => $this->email()]));
				zbase_session_flash('user_verifyEmailAddress', true);
				return true;
			}
		} catch (\Zbase\Exceptions\RuntimeException $e)
		{
			zbase_exception_throw($e);
		}
		return false;
	}

	/**
	 * Check if email address is verified
	 * @return boolean
	 */
	public function isEmailVerified()
	{
		return (bool) $this->email_verified;
	}

	// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc="STATIC API">
	/**
	 * Static API
	 */

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
			$user = $entity->repo()->byId($id);
			return ['user' => $user, 'user_profile' => $user->profile()];
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
			$user = $entity->repo()->by('email', $email)->first();
			return ['user' => $user, 'user_profile' => $user->profile()];
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

	// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc="TableDefiniations">
	/**
	 * Default Data
	 * @param array $defaultData Configuration default data
	 * @return array
	 */
	public static function tableDefaultData($defaultData = [])
	{
		$defaultData = [
			[
				'status' => 'ok',
				'username' => 'sudox',
				'name' => 'Super User',
				'email' => 'sudox@' . zbase_domain(),
				'email_verified' => 1,
				'email_verified_at' => \Zbase\Models\Data\Column::f('timestamp'),
				'password' => \Zbase\Models\Data\Column::f('string', 'password'),
				'password_updated_at' => \Zbase\Models\Data\Column::f('timestamp'),
				'created_at' => \Zbase\Models\Data\Column::f('timestamp'),
				'updated_at' => \Zbase\Models\Data\Column::f('timestamp'),
				'alpha_id' => zbase_generate_hash([rand(1, 1000), time(), rand(1, 1000)], 'sudo'),
				'deleted_at' => null
			],
			[
				'status' => 'ok',
				'username' => 'adminx',
				'name' => 'Admin Istrator',
				'email' => 'adminx@' . zbase_domain(),
				'email_verified' => 1,
				'email_verified_at' => \Zbase\Models\Data\Column::f('timestamp'),
				'password' => \Zbase\Models\Data\Column::f('string', 'password'),
				'password_updated_at' => \Zbase\Models\Data\Column::f('timestamp'),
				'created_at' => \Zbase\Models\Data\Column::f('timestamp'),
				'updated_at' => \Zbase\Models\Data\Column::f('timestamp'),
				'alpha_id' => zbase_generate_hash([rand(1, 1000), time(), rand(1, 1000)], 'admin'),
				'deleted_at' => null
			],
			[
				'status' => 'ok',
				'username' => 'systemx',
				'name' => 'Mr. System',
				'email' => 'systemx@' . zbase_domain(),
				'email_verified' => 1,
				'email_verified_at' => \Zbase\Models\Data\Column::f('timestamp'),
				'password' => zbase_generate_code(),
				'password_updated_at' => \Zbase\Models\Data\Column::f('timestamp'),
				'created_at' => \Zbase\Models\Data\Column::f('timestamp'),
				'updated_at' => \Zbase\Models\Data\Column::f('timestamp'),
				'alpha_id' => zbase_generate_hash([rand(1, 1000), time(), rand(1, 1000)], 'admin'),
				'deleted_at' => null
			],
			[
				'status' => 'ok',
				'username' => 'userx',
				'name' => 'Just User',
				'email' => 'userx@' . zbase_domain(),
				'email_verified' => 1,
				'email_verified_at' => \Zbase\Models\Data\Column::f('timestamp'),
				'password' => \Zbase\Models\Data\Column::f('string', 'password'),
				'password_updated_at' => \Zbase\Models\Data\Column::f('timestamp'),
				'created_at' => \Zbase\Models\Data\Column::f('timestamp'),
				'updated_at' => \Zbase\Models\Data\Column::f('timestamp'),
				'alpha_id' => zbase_generate_hash([rand(1, 1000), time(), rand(1, 1000)], 'user'),
				'deleted_at' => null
			],
			[
				'status' => 'ok',
				'username' => 'moderatorx',
				'name' => 'Moody Moderator',
				'email' => 'moderator@' . zbase_domain(),
				'email_verified' => 1,
				'email_verified_at' => \Zbase\Models\Data\Column::f('timestamp'),
				'password' => \Zbase\Models\Data\Column::f('string', 'password'),
				'password_updated_at' => \Zbase\Models\Data\Column::f('timestamp'),
				'created_at' => \Zbase\Models\Data\Column::f('timestamp'),
				'updated_at' => \Zbase\Models\Data\Column::f('timestamp'),
				'alpha_id' => zbase_generate_hash([rand(1, 1000), time(), rand(1, 1000)], 'moderator'),
				'deleted_at' => null
			]
		];
		return $defaultData;
	}

	/**
	 * POST-Seeding event
	 */
	public static function seedingEventPost($entity)
	{
		$sudo = \DB::table('users')->where(['username' => 'sudox'])->first();
		$sudoRole = \DB::table('user_roles')->where('role_name', 'sudo')->first();
		\DB::table('users_roles')->where('user_id', $sudo->user_id)->update(['role_id' => $sudoRole->role_id]);
		\DB::table('users')->whereIn('username', array('sudox', 'systemx'))->update(['roles' => json_encode([$sudoRole->role_name])]);

		$admin = \DB::table('users')->where(['username' => 'adminx'])->first();
		$adminRole = \DB::table('user_roles')->where('role_name', 'admin')->first();
		\DB::table('users_roles')->where('user_id', $admin->user_id)->update(['role_id' => $adminRole->role_id]);
		\DB::table('users')->where('user_id', $admin->user_id)->update(['roles' => json_encode([$adminRole->role_name])]);

		$system = \DB::table('users')->where(['username' => 'systemx'])->first();
		\DB::table('users_roles')->where('user_id', $system->user_id)->update(['role_id' => $adminRole->role_id]);
		\DB::table('users')->where('user_id', $admin->user_id)->update(['roles' => json_encode([$adminRole->role_name])]);

		$user = \DB::table('users')->where(['username' => 'userx'])->first();
		$userRole = \DB::table('user_roles')->where('role_name', 'user')->first();
		\DB::table('users_roles')->where('user_id', $user->user_id)->update(['role_id' => $userRole->role_id]);
		\DB::table('users')->where('user_id', $user->user_id)->update(['roles' => json_encode([$userRole->role_name])]);

		$moderator = \DB::table('users')->where(['username' => 'moderatorx'])->first();
		$moderatorRole = \DB::table('user_roles')->where('role_name', 'moderator')->first();
		\DB::table('users_roles')->where('user_id', $moderator->user_id)->update(['role_id' => $moderatorRole->role_id]);
		\DB::table('users')->where('user_id', $moderator->user_id)->update(['roles' => json_encode([$moderatorRole->role_name])]);
	}

	/**
	 * Return table minimum columns requirement
	 * @param array $columns Some columns
	 * @param array $entity Entity Configuration
	 * @return array
	 */
	public static function tableColumns($columns = [], $entity = [])
	{
		$columns = [
			'user_id' => [
				'filterable' => [
					'name' => 'userid',
					'enable' => true
				],
				'sortable' => [
					'name' => 'userid',
					'enable' => true
				],
				'label' => 'User ID',
				'hidden' => false,
				'fillable' => false,
				'type' => 'integer',
				'unique' => true,
				'unsigned' => true,
				'length' => 16,
				'comment' => 'User Id'
			],
			/**
			 * User Statuses:
			 *
			 * 40 - 50 - Banned - Cannot Login the site
			 * 20 - 30 - Banned - Can Login the site but with notice
			 * 1 - 20 - Account OK; Default status
			 *
			 */
			'status' => [
				'filterable' => [
					'name' => 'status',
					'enable' => true
				],
				'sortable' => [
					'name' => 'status',
					'enable' => true
				],
				'hidden' => true,
				'fillable' => true,
				'type' => 'string',
				'valueMap' => [
					'ban_no_auth' => 'Banned cannot Login',
					'ban_can_auth' => 'Banned can Login',
					'ok' => 'Ok'
				],
				'nullable' => false,
				'unsigned' => false,
				'comment' => 'Account Status'
			],
			'username' => [
				'filterable' => [
					'name' => 'username',
					'enable' => true
				],
				'sortable' => [
					'name' => 'username',
					'enable' => true
				],
				'hidden' => false,
				'length' => 32,
				'fillable' => true,
				'type' => 'string',
				'subType' => 'userName',
				'unique' => true,
				'comment' => 'Unique user Name'
			],
			'name' => [
				'filterable' => [
					'name' => 'name',
					'enable' => true
				],
				'sortable' => [
					'name' => 'name',
					'enable' => true
				],
				'hidden' => false,
				'length' => 64,
				'fillable' => true,
				'type' => 'string',
				'subtype' => 'personDisplayName',
				'comment' => 'Display name'
			],
			'email' => [
				'filterable' => [
					'name' => 'email',
					'enable' => true
				],
				'sortable' => [
					'name' => 'email',
					'enable' => true
				],
				'name' => 'email',
				'length' => 64,
				'hidden' => false,
				'fillable' => true,
				'type' => 'string',
				'subtype' => 'email',
				'unique' => true,
				'comment' => 'User email address'
			],
			'email_verified' => [
				'filterable' => [
					'name' => 'emailverified',
					'enable' => true
				],
				'sortable' => [
					'name' => 'emailverified',
					'enable' => true
				],
				'hidden' => false,
				'fillable' => false,
				'nullable' => true,
				'type' => 'boolean',
				'subtype' => 'yesno',
				'default' => 0,
				'comment' => 'Is email verified'
			],
			'email_verified_at' => [
				'filterable' => [
					'name' => 'emailverifieddate',
					'enable' => true
				],
				'sortable' => [
					'name' => 'emailverifieddate',
					'enable' => true
				],
				'hidden' => false,
				'fillable' => false,
				'type' => 'timestamp',
				'nullable' => true,
				'comment' => 'Date email verified'
			],
			'password' => [
				'hidden' => true,
				'fillable' => false,
				'type' => 'string',
				'subType' => 'password',
				'length' => 60,
				'comment' => 'User crypted password'
			],
			'password_updated_at' => [
				'hidden' => false,
				'fillable' => false,
				'type' => 'timestamp',
				'nullable' => true,
				'comment' => 'Date password updated'
			],
			'avatar' => [
				'hidden' => false,
				'fillable' => false,
				'nullable' => true,
				'type' => 'string',
				'subType' => 'avatarurl',
				'length' => 255,
				'comment' => 'Avatar URL'
			],
			'roles' => [
				'hidden' => false,
				'fillable' => false,
				'nullable' => true,
				'type' => 'text',
				'length' => 255,
				'comment' => 'User Roles'
			],
			'location' => [
				'hidden' => false,
				'fillable' => false,
				'nullable' => true,
				'type' => 'string',
				'length' => 255,
				'comment' => 'User Location'
			],
		];
		return $columns;
	}

	/**
	 * Table Relations
	 * @param array $relations Configuration default data
	 * @return array
	 */
	public static function tableRelations($relations = [])
	{
		$relations = [
			'profile' => [
				'entity' => 'user_profile',
				'type' => 'onetoone',
				'class' => [
					'method' => 'profile'
				],
				'keys' => [
					'local' => 'user_id',
					'foreign' => 'user_id'
				],
			],
			'address' => [
				'entity' => 'user_address',
				'type' => 'onetomany',
				'class' => [
					'method' => 'address'
				],
				'keys' => [
					'local' => 'user_id',
					'foreign' => 'user_id'
				],
			],
			'roles' => [
				'entity' => 'user_roles',
				'type' => 'manytomany',
				'class' => [
					'method' => 'roles'
				],
				'pivot' => 'users_roles', // The Pivot Entity Index
				'keys' => [
					'local' => 'role_id', // the foreign key name of the model on which you are defining the relationship
					'foreign' => 'user_id' // the foreign key name of the model that you are joining to
				],
			],
		];
		return $relations;
	}

	// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc="ClearCache">
	/**
	 * Clear entity cache by Id
	 *
	 * @return void
	 */
	public function clearEntityCacheById()
	{
		parent::clearEntityCacheById();
		$cacheKey = zbase_cache_key(zbase_entity($this->entityName()), 'byrelation_address_' . $this->id());
		zbase_cache_remove($cacheKey, [$this->entityName()], ['driver' => 'file']);
		$cacheKey = zbase_cache_key(zbase_entity($this->entityName()), 'byrelation_profile_' . $this->id());
		zbase_cache_remove($cacheKey, [$this->entityName()], ['driver' => 'file']);
	}

// </editor-fold>
}
