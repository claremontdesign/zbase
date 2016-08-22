<?php

namespace Zbase\Http\Controllers\Laravel\Auth;

/**
 *
 * AuthController
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file AuthController.php
 * @project Zbase
 * @package Zbase\Models\View
 */

/**
 * Abstract Controller
 */
use Zbase\Http\Controllers\Laravel\Controller;
use Validator;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Zbase\Traits\Auth as ZbaseAuth;
use Zbase\Traits\User as ZbaseUser;

class AuthController extends Controller
{
	/*
	  |--------------------------------------------------------------------------
	  | Registration & Login Controller
	  |--------------------------------------------------------------------------
	  |
	  | This controller handles the registration of new users, as well as the
	  | authentication of existing users. By default, this controller uses
	  | a simple trait to add these behaviors. Why don't you explore it?
	  |
	 */

use AuthenticatesAndRegistersUsers,
	ThrottlesLogins,
	ZbaseUser,
	ZbaseAuth;

	protected $username = 'email';

	/**
	 * Where to redirect users after login / registration.
	 *
	 * @var string
	 */
	protected $redirectTo = '/home';
	protected $redirectAfterLogout = '/';

	/**
	 * Create a new authentication controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest', ['except' => 'logout']);
		if(zbase_is_back())
		{
			$this->redirectTo = zbase_url_from_route('admin');
			$this->redirectAfterLogout = zbase_url_from_route('admin');
		}
		else
		{
			$this->redirectTo = zbase_url_from_route('home');
			$this->redirectAfterLogout = zbase_url_from_route('index');
		}
	}

	// <editor-fold defaultstate="collapsed" desc="Registration">

	/**
	 * Return the Registration Redirect Path
	 * @return string
	 */
	public function getRegisterRedirectPath($user)
	{
		return $this->redirectPath();
	}

	public function register()
	{
		try
		{
			if(!$this->registerEnabled())
			{
				return $this->notfound('User registration is disabled.');
			}
			if($this->isPost())
			{
				return $this->postRegister(zbase_request());
			}
			return $this->view(zbase_view_file('auth.register'));
		} catch (\Zbase\Exceptions\RuntimeException $e)
		{
			zbase_exception_throw($e);
			return $this->error();
		}
	}

	/**
	 * Handle a registration request for the application.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function postRegister(Request $request)
	{
		try
		{
			$validator = $this->registerValidator($request->all());

			if($validator->fails())
			{
				$this->throwValidationException(
						$request, $validator
				);
			}
			zbase_alerts_reset();
			$user = $this->userCreate($request->all());
			if($user instanceof \Zbase\Entity\Laravel\User\User)
			{
				zbase()->json()->setVariable('_redirect', $this->getRegisterRedirectPath($user));
				zbase()->json()->setVariable('register_success', 1);
				if(!zbase_is_json())
				{
					if($user->loginAfterRegister())
					{
						\Auth::login($user);
						return zbase_response(redirect($this->getRegisterRedirectPath($user)));
					}
				}
			}
			else
			{
				zbase()->json()->setVariable('register_success', 0);
				if(!zbase_is_json())
				{
					return zbase_response(redirect(zbase_url_from_route('register')));
				}
			}
		} catch (\Zbase\Exceptions\RuntimeException $e)
		{
			zbase_exception_throw($e);
			return $this->error();
		}
	}

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @TODO check if role is given, check it against the list of ROLES from DB
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	protected function registerValidator(array $data)
	{
		$userEntity = zbase_entity('user');
		$messages = [];
		$rules = [
			'name' => 'required|max:255',
			'email' => 'required|email|max:255|unique:' . zbase_config_get('entity.user.table.name'),
		];
		if(!isset($data['name']))
		{
			unset($rules['name']);
		}
		$messages['email.unique'] = 'Email address used already.';
		if($userEntity->usernameEnabled())
		{
			$notAllowedUsernames = require_once zbase_path_library('notallowedusernames.php');
			$rules['username'] = 'required|min:5|max:32|regex:/^[a-z][a-z0-9]{5,32}$/|unique:' . zbase_config_get('entity.user.table.name') . '|not_in:' . implode(',', $notAllowedUsernames);
			$messages['username.unique'] = 'Username already exists.';
			$messages['username.regex'] = 'Username should be of alphanumeric in small letters';
			$messages['username.not_in'] = 'Username already exists.';
		}
		if($userEntity->passwordAutoGenerate())
		{
			$data['password'] = $userEntity->generatePassword();
			$data['raw_password'] = $data['password'];
		}
		else
		{
			$rules['password'] = 'required|confirmed|min:6';
			$rules['password_confirmation'] = 'same:password';
			$messages['password.min'] = 'Password too short.';
			$messages['password_confirmation.same'] = 'Passwords not the same. Kindly verify password.';
		}
		if(!empty($data['role']))
		{
			$roles = zbase()->entity('user_roles', [], true)->listAllRoles();
			if(!empty($roles))
			{
				$rules['role'] = 'in:' . implode(',', $roles);
				$messages['role.in'] = 'Kindly select a role in the given list.';
			}
		}
		$moreValidations = $this->getRegistrationValidation();
		if(!empty($moreValidations['rules']))
		{
			$rules = array_merge($rules, $moreValidations['rules']);
		}
		if(!empty($moreValidations['messages']))
		{
			$messages = array_merge($messages, $moreValidations['messages']);
		}
		return Validator::make($data, $rules, $messages);
	}

	/**
	 * Return more validation
	 *
	 * @return []
	 */
	public function getRegistrationValidation()
	{
		return [];
	}

	// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc="Logins">

	/**
	 * Login Redirect Path
	 * @return string
	 */
	public function getLoginRedirectPath($user)
	{
		return $this->redirectPath();
	}

	/**
	 * Login
	 * @return
	 */
	public function login()
	{
		if(!$this->authEnabled())
		{
			return $this->notfound('User authentication is disabled.');
		}
		if($this->isPost())
		{
			return $this->postLogin(zbase_request());
		}
		return $this->view(zbase_view_file('auth.login'));
	}

	/**
	 * Handle a login request to the application.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function postLogin(Request $request)
	{
		try
		{
			if(!$this->authEnabled())
			{
				return $this->notfound('User authentication is disabled.');
			}
			$rules = [
				$this->loginUsername() => 'required', 'password' => 'required',
			];
			$messages = [];
			$this->validate($request, $rules, $messages);

			// If the class is using the ThrottlesLogins trait, we can automatically throttle
			// the login attempts for this application. We'll key this by the username and
			// the IP address of the client making these requests into this application.
			$throttles = $this->isUsingThrottlesLoginsTrait();

			if($throttles && $this->hasTooManyLoginAttempts($request))
			{
				if(zbase_is_json())
				{
					zbase()->json()->setVariable('login_lock', 1);
				}
				return $this->sendLockoutResponse($request);
			}

			$credentials = $this->getCredentials($request);

			if(\Auth::attempt($credentials, $request->has('remember')))
			{
				$redirect = zbase_request_input('redirect', zbase_session_get('__loginRedirect', $this->getLoginRedirectPath(zbase_auth_user())));
				zbase()->json()->setVariable('_redirect', $redirect);
				zbase()->json()->setVariable('login_success', 1);
				if(zbase_is_back())
				{
					if(\Auth::guard($this->getGuard())->user()->isAdmin())
					{
						if(zbase_is_json())
						{
							zbase()->json()->setVariable('_redirect', zbase_url_from_route('admin'));
						}
						return $this->handleUserWasAuthenticated($request, $throttles);
					}
				}
				if(!empty($redirect))
				{
					$this->redirectTo = $redirect;
				}
				else
				{
					$this->redirectTo = zbase_url_from_route('home');
				}
				return $this->handleUserWasAuthenticated($request, $throttles);
			}
			if($throttles)
			{
				$this->incrementLoginAttempts($request);
			}
			$this->message('error', $this->getFailedLoginMessage());
			return redirect($this->loginPath())
							->withInput($request->only($this->loginUsername(), 'remember'))
							->withErrors([
								$this->loginUsername() => $this->getFailedLoginMessage(),
			]);
		} catch (\Zbase\Exceptions\RuntimeException $e)
		{
			zbase_exception_throw($e);
			return $this->error();
		}
	}

	/**
	 * Process initial authentication
	 * Checking if user can Auth
	 */
	public function authenticated(Request $request, $user)
	{
		if(!$user->canAuth())
		{
			\Auth::logout();
		}
		$user->log('user::authenticated');
		$user->authenticated();
		return redirect()->intended($this->getLoginRedirectPath($user));
	}

	// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc="EmailVerification">
	/**
	 * routename: email-verify, expect: email and token
	 * Verify Email Address
	 */
	public function emailVerify()
	{
		$email = $this->getRouteParameter('email', false);
		$token = $this->getRouteParameter('token', false);
		if(!empty($email) && !empty($token))
		{
			$user = zbase_user_by('email', $email);
			if($user instanceof \Zbase\Entity\Laravel\User\User)
			{
				if(zbase_auth_has())
				{
					$verified = $user->verifyEmailAddress($token);
					if(empty($verified))
					{
						zbase_alert('error', 'There was an error verifying your email address. Kindly try again.');
					}
					return redirect()->to(zbase_url_from_route('home'));
				}
				else
				{
					zbase_session_set('__loginRedirect', zbase_url_from_current());
					return redirect()->to(zbase_url_from_route('login'));
				}
			}
		}
		return $this->notfound();
	}

	/**
	 * Update email Address Request
	 * Process the link that was sent into the email when
	 * a user wanted to update his email address to a new email address
	 *
	 * routename: update-email-request, expect: email and token
	 * @return
	 */
	public function emailUpdateRequestVerify()
	{
		$email = $this->getRouteParameter('email', false);
		$token = $this->getRouteParameter('token', false);
		if(!empty($email) && !empty($token))
		{
			$user = zbase_user_by('email', $email);
			if($user instanceof \Zbase\Entity\Laravel\User\User)
			{
				if(zbase_auth_has())
				{
					$updated = $user->checkEmailRequestUpdate($token);
					if(!empty($updated))
					{
						zbase_session_set('update-email-address', true);
						return redirect()->to(zbase_url_from_route('home'));
					}
					else
					{
						zbase_alert('error', 'There was an error updating your email address. Kindly try again.');
					}
				}
				else
				{
					zbase_session_set('__loginRedirect', zbase_url_from_current());
					return redirect()->to(zbase_url_from_route('login'));
				}
			}
		}
		return $this->notfound();
	}

	// </editor-fold>

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	protected function validator(array $data)
	{
		return Validator::make($data, [
					'name' => 'required|max:255',
					'email' => 'required|email|max:255|unique:users',
					'password' => 'required|confirmed|min:6',
		]);
	}

	/**
	 * Get the path to the login route.
	 *
	 * @return string
	 */
	public function loginPath()
	{
		if(zbase_is_back())
		{
			return zbase_url_from_route('admin.login');
		}
		return zbase_url_from_route('login');
	}

	/**
	 * Get the failed login message.
	 *
	 * @return string
	 */
	protected function getFailedLoginMessage()
	{
		return zbase_config_get('auth.messages.failed', 'These credentials do not match our records');
	}

	/**
	 * Log the user out of the application.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function logout()
	{
		\Auth::guard($this->getGuard())->logout();
		return zbase_redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
	}

}
