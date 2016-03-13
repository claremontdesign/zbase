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
	 * The Entity Id
	 * @return integer
	 */
	public function id()
	{
		return $this->user_id;
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

	// <editor-fold defaultstate="collapsed" desc="CREATE">

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

	// </editor-fold>

	/**
	 * Return the current authed user
	 * @return User
	 */
	public function currentUser()
	{
		return $this->repository()->byId(zbase_auth_user()->id());
	}

	/**
	 *
	 * @param type $task
	 * @param type $msg
	 */
	public function log($task, $msg)
	{

	}

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
			if($action == 'index')
			{
				/**
				 * Check for changes in Username
				 */
				if(!empty($data['username']))
				{
					$this->updateUsername($data['username']);
				}
				if(!empty($data['email']))
				{
					$this->updateRequestEmailAddress($data['email']);
				}
				if(!empty($data['password']) && !empty($data['password_confirm']))
				{
					$this->updateRequestPassword($data['password_confirm']);
				}
			}
		}
	}

	// <editor-fold defaultstate="collapsed" desc="UPDATE Password">
	/**
	 * Password has been resetted
	 * @param string $password The RAW Password
	 * @param boolean $account Password was updated from the account section
	 */
	public function updatePassword($password, $account = false)
	{
		zbase_db_transaction_start();
		try
		{
			$oldPasswords = $this->getDataOption('password_old', []);
			$oldPasswords[] = [
				'old' => $this->password,
				'date' => zbase_date_now(),
				'ip' => zbase_ip()
			];
			$this->unsetDataOption('password_update_code');
			$this->unsetDataOption('password_new');
			$this->setDataOption('password_old', $oldPasswords);
			$this->password = zbase_bcrypt($password);
			$this->password_updated_at = zbase_date_now();
			$this->save();
			if(!empty($account))
			{
				zbase_alert('info', _zt('Password successfully updated.'));
			}
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
	 */
	public function checkNewPassword($password)
	{
		if(zbase_bcrypt_check($password, $this->password))
		{
			return false;
		}
		$oldPasswords = $this->getDataOption('password_old', []);
		if(!empty($oldPasswords))
		{
			foreach ($oldPasswords as $o)
			{
				if(zbase_bcrypt_check($password, $o['old']))
				{
					return false;
				}
			}
		}
		return true;
	}

	/**
	 * First step, Update password
	 * 	Will send an email with a link to complete the process of updating the password
	 * @param string $newPassword The new RAW password
	 * @return boolean
	 */
	public function updateRequestPassword($newPassword)
	{
		zbase_db_transaction_start();
		try
		{
			$code = zbase_generate_code();
			$updateCodes = $this->getDataOption('password_update_code', []);
			$updateCodes[] = $code;
			$this->setDataOption('password_update_code', $updateCodes);
			$this->setDataOption('password_new', zbase_bcrypt($newPassword));
			$this->save();
			zbase_alert('info', _zt('We sent an email to %email% with a link to complete the process of updating your password.', ['%email%' => $this->email()]));
			zbase_messenger_email($this->email(), 'account-noreply', _zt('New Password update request'), zbase_view_file_contents('contents.email.account.newPasswordRequest'), ['entity' => $this, 'code' => $code]);
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
			zbase_alert('success', _zt('Username updated!'));
			zbase_messenger_email($this->email(), 'account-noreply', _zt('Username was changed'), zbase_view_file_contents('contents.email.account.updateUsername'), ['entity' => $this, 'old' => $oldUsername, 'new' => $newUsername]);
		}
	}

	// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc="UPDATE Email Address">

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
				 * Send a request code to the old email address
				 * option: email_verification_code: code; new_email: $newEmail Address
				 */
				$code = zbase_generate_code();
				$this->setDataOption('email_updaterequest_code', $code);
				$this->setDataOption('email_new', $newEmailAddress);
				$this->setDataOption('email_new_request_date', zbase_date_now());
				$this->save();
				zbase_alert('info', _zt('We sent an email to %email% with a link to complete the process of updating your email address.', ['%email%' => $this->email()]));
				zbase_messenger_email($this->email(), 'account-noreply', _zt('New Email address update request'), zbase_view_file_contents('contents.email.account.newEmailAddressRequest'), ['entity' => $this, 'newEmailAddress' => $newEmailAddress, 'code' => $code]);
				zbase_db_transaction_commit();
			} catch (\Zbase\Exceptions\RuntimeException $e)
			{
				zbase_db_transaction_rollback();
				return false;
			}
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
				$oldEmails = $this->getDataOption('email_old', []);
				$oldEmails[] = [
					'old' => $this->email(),
					'date' => zbase_date_now(),
					'ip' => zbase_ip(),
					'new' => $newEmail
				];
				$this->setDataOption('email_old', $oldEmails);
				$emailVerificationEnabled = zbase_config_get('auth.emailverify.enable', true);
				$this->email = $newEmail;
				$this->email_verified = $emailVerificationEnabled ? 0 : 1;
				$this->email_verified_at = null;
				if(!empty($emailVerificationEnabled))
				{
					$code = zbase_generate_code();
					$this->setDataOption('email_verification_code', $code);
					zbase_alert('info', _zt('We sent an email to %email% to verify your new email address.', ['%email%' => $newEmail]));
					zbase_messenger_email($this->email(), 'account-noreply', _zt('Email address verification code'), zbase_view_file_contents('contents.email.account.newEmailAddressVerification'), ['entity' => $this, 'code' => $code]);
				}
				/**
				 * Remove options on updating email address
				 */
				$this->unsetDataOption('email_new');
				$this->unsetDataOption('email_new_request_date');
				$this->save();
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
			$this->setDataOption('email_old', $oldEmails);
			$this->unsetDataOption('email_verification_code');
			$this->email_verified = 1;
			$this->email_verified_at = zbase_date_now();
			$this->save();
			return true;
		}
		return false;
	}

// </editor-fold>
}
