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

	/**
	 * The Entity Id
	 * @return integer
	 */
	public function id()
	{
		return $this->user_id;
	}

	/**
	 * The Entity Id
	 * @return integer
	 */
	public function alphaId()
	{
		return $this->alpha_id;
	}

	public function title()
	{
		return $this->displayName();
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
				$this->address = $address->first();
			}
		}
		return $this->address;
	}

	/**
	 * The Username
	 * @return string
	 */
	public function username()
	{
		return $this->username;
	}

	public function profile()
	{
		return zbase_entity('user_profile')->repo()->by('user_id', $this->id())->first();
	}

	/**
	 * Current Role
	 * @return type
	 */
	public function roleName()
	{
		if(is_null($this->roleName))
		{
			$this->roleName = $this->roles()->first()->name();
		}
		return $this->roleName;
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
			return $profile->first_name . ' ' . $profile->last_name;
		}
		return null;
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
		$logMsg = [];
		if(empty($attributes['profile']))
		{
			$attributes['profile'] = [];
			$attributesAddress = ['city' => ''];
			$profileColumns = zbase_entity('user_profile')->getColumns();
			$addressColumns = zbase_entity('user_address')->getColumns();

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
		$model->fill($attributes);
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
			$model->save();
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
				zbase_entity('user_profile')->fill($profileAttributes)->save();
				// $model->profile()->create(array_replace_recursive($attributesProfile, $profileAttributes));
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
				zbase_entity('user_address')->fill($attributesAddress)->save();
				$logMsg[] = 'Address saved!';
			}
			$model->toggleRelationshipMode();
			if($model->sendWelcomeMessage($attributes))
			{
				$logMsg[] = 'Welcome message sent!';
			}
			zbase_db_transaction_commit();
			$logMsg[] = 'User saved!';
			$model->log('Register');
			zbase_log(implode(PHP_EOL, $logMsg), null, __METHOD__);
			return $model;
		}
		else
		{
			$logMsg[] = 'Role is empty. zbase_db_transaction_rollback()';
			zbase_log(implode(PHP_EOL, $logMsg), null, __METHOD__);
			zbase_db_transaction_rollback();
			throw new \Zbase\Exceptions\RuntimeException(_zt('User Role given not found.'));
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
	public function log($task, $msg = null, $options = null)
	{
		$data = [
			'remarks' => !empty($msg) ? $msg : null,
			'task' => $task,
			'ip_address' => zbase_ip(),
			'user_id' => $this->id(),
			'type' => 1,
		];
		zbase_entity('user_logs')->fill($data)->save();
	}

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
				if(file_exists($folder . $this->profile->avatar))
				{
					unlink($folder . $this->profile->avatar);
				}
				return basename($uploadedFile);
			}
		} catch (\Zbase\Exceptions\RuntimeException $e)
		{
			if(zbase_is_dev())
			{
				dd($e);
			}
			zbase_abort(500);
		}
	}

	/**
	 * cATEGORY iMAGE uRL
	 * @return type
	 */
	public function avatarUrl($options = [])
	{
		if($this->profile()->isAvatarUrl())
		{
			return $this->profile->avatar;
		}
		$fullImage = false;
		$params['id'] = $this->alphaId();
		$params['image'] = $this->profile->avatar;
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
	public function serveImage($width, $height = null, $quality = null, $download = false)
	{
		$folder = zbase_storage_path() . '/' . zbase_tag() . '/user/' . $this->id() . '/';
		$path = $folder . $this->profile()->avatar;
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
				$this->updateProfile($data);
				return true;
			}
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
				}
			}
			$userProfile = zbase_entity('user_profile')->where('user_id', $this->id());
			foreach ($data as $key => $val)
			{
				if(in_array($key, $fillables))
				{
					$newData[$key] = $val;
				}
			}
			if(!empty($newData))
			{
				$saved = $userProfile->update($newData);
				if(!empty($saved))
				{
					zbase_alert('success', _zt('Profile Updated.'));
					$this->log('user::updateProfile');
					return true;
				}
			}
		}
		return false;
	}

	// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc="LOST Password">
	public function lostPassword()
	{
//		$response = \Password::sendResetLink(['email' => $this->email], function (\Illuminate\Mail\Message $message) {
//					$message->sender(zbase_config_get('email.noreply.email'), zbase_config_get('email.noreply.name'));
//					$message->subject('Your Password Reset Link');
//		});
		// $codeEntity = zbase_entity('user_tokens')->repo()->by('email', $this->email())->first();
//		$codeEntity = \DB::table('user_tokens')->where(['email' => $this->email()])->first();
//		if(!empty($codeEntity))
//		{
//			zbase_messenger_email($this->email(), 'account-noreply', _zt('Your Password Reset Link'),
//					zbase_view_file_contents('auth.password.email.password'), ['entity' => $this, 'token' => $codeEntity->token]);
			$this->log('user::lostPassword');
//		}
//
//		switch ($response)
//		{
//			case \Password::RESET_LINK_SENT:
				zbase_alert(\Zbase\Zbase::ALERT_INFO, 'A link to reset your password was sent to your email address. Kindly check.');
//				return true;
//		}
//		return false;
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
			$this->log('user::updatePassword');
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
			zbase_messenger_email($this->email(), 'account-noreply', _zt('New Password update request'), zbase_view_file_contents('email.account.newPasswordRequest'), ['entity' => $this, 'code' => $code]);
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
			$this->log('user::updateUsername');
			zbase_messenger_email($this->email(), 'account-noreply', _zt('Username was changed'), zbase_view_file_contents('email.account.updateUsername'), ['entity' => $this, 'old' => $oldUsername, 'new' => $newUsername]);
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
				zbase_messenger_email($this->email(), 'account-noreply', _zt('New Email address update request'), zbase_view_file_contents('email.account.newEmailAddressRequest'), ['entity' => $this, 'newEmailAddress' => $newEmailAddress, 'code' => $code]);
				$this->log('user::updateRequestEmailAddress');
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
					zbase_messenger_email($this->email(), 'account-noreply', _zt('Email address verification code'), zbase_view_file_contents('email.account.newEmailAddressVerification'), ['entity' => $this, 'code' => $code, 'newEmailAddress' => $newEmail]);
				}
				/**
				 * Remove options on updating email address
				 */
				$this->unsetDataOption('email_new');
				$this->unsetDataOption('email_new_request_date');
				$this->save();
				$this->log('user::updateEmailAddress');
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
			$this->log('user::verifyEmailAddress');
			$this->save();
			return true;
		}
		return false;
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
				'username' => 'sudo',
				'name' => 'sudo',
				'email' => 'sudo@zbase.com',
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
				'username' => 'admin',
				'name' => 'admin',
				'email' => 'admin@zbase.com',
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
				'username' => 'system',
				'name' => 'system',
				'email' => 'system@zbase.com',
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
				'username' => 'user',
				'name' => 'user',
				'email' => 'user@zbase.com',
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
				'username' => 'moderator',
				'name' => 'moderator',
				'email' => 'moderator@zbase.com',
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
		$sudo = \DB::table('users')->where(['username' => 'sudo'])->first();
		$sudoRole = \DB::table('user_roles')->where('role_name', 'sudo')->first();
		\DB::table('users_roles')->where('user_id', $sudo->user_id)->update(['role_id' => $sudoRole->role_id]);

		$admin = \DB::table('users')->where(['username' => 'admin'])->first();
		$adminRole = \DB::table('user_roles')->where('role_name', 'admin')->first();
		\DB::table('users_roles')->where('user_id', $admin->user_id)->update(['role_id' => $adminRole->role_id]);

		$system = \DB::table('users')->where(['username' => 'system'])->first();
		\DB::table('users_roles')->where('user_id', $system->user_id)->update(['role_id' => $adminRole->role_id]);

		$user = \DB::table('users')->where(['username' => 'user'])->first();
		$userRole = \DB::table('user_roles')->where('role_name', 'user')->first();
		\DB::table('users_roles')->where('user_id', $user->user_id)->update(['role_id' => $userRole->role_id]);
		$moderator = \DB::table('users')->where(['username' => 'moderator'])->first();
		$moderatorRole = \DB::table('user_roles')->where('role_name', 'moderator')->first();
		\DB::table('users_roles')->where('user_id', $moderator->user_id)->update(['role_id' => $moderatorRole->role_id]);
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
			]
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
}
