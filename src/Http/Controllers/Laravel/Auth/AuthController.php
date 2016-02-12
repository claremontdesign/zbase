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
	ZbaseAuth;

	protected $username = 'email';

	/**
	 * Where to redirect users after login / registration.
	 *
	 * @var string
	 */
	protected $redirectTo = '/home';

	/**
	 * Create a new authentication controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest', ['except' => 'logout']);
	}

	public function register()
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
	}

	/**
	 * Handle a registration request for the application.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function postRegister(Request $request)
	{
		$validator = $this->registerValidator($request->all());

		if($validator->fails())
		{
			$this->throwValidationException(
					$request, $validator
			);
		}

		\Auth::login($this->create($request->all()));

		return redirect($this->redirectPath());
	}

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	protected function registerValidator(array $data)
	{
		return Validator::make($data, [
					'name' => 'required|max:255',
					'email' => 'required|email|max:255|unique:' . zbase_config_get('entity.user.table.name'),
					'username' => 'required|min:3|max:32|unique:' . zbase_config_get('entity.user.table.name'),
					'password' => 'required|confirmed|min:6',
		]);
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return User
	 */
	protected function create(array $data)
	{
		$user = [
			'status' => $this->defaultNewUserStatus(),
			'username' => !empty($data['username']) ? $data['username'] : null,
			'name' => $data['name'],
			'email' => $data['email'],
			'email_verified' => $this->emailVerificationEnabled() ? 0 : 1,
			'email_verified_at' => null,
			'password' => bcrypt($data['password']),
			'password_updated_at' => null,
			'created_at' => \Zbase\Models\Data\Column::f('timestamp'),
			'updated_at' => \Zbase\Models\Data\Column::f('timestamp'),
			'deleted_at' => null,
		];
		return zbase_entity('user')->create($user);
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
		if(!$this->authEnabled())
		{
			return $this->notfound('User authentication is disabled.');
		}
		$this->validate($request, [
			$this->loginUsername() => 'required', 'password' => 'required',
		]);

		// If the class is using the ThrottlesLogins trait, we can automatically throttle
		// the login attempts for this application. We'll key this by the username and
		// the IP address of the client making these requests into this application.
		$throttles = $this->isUsingThrottlesLoginsTrait();

		if($throttles && $this->hasTooManyLoginAttempts($request))
		{
			return $this->sendLockoutResponse($request);
		}

		$credentials = $this->getCredentials($request);

		if(\Auth::attempt($credentials, $request->has('remember')))
		{
			return $this->handleUserWasAuthenticated($request, $throttles);
		}

		// If the login attempt was unsuccessful we will increment the number of attempts
		// to login and redirect the user back to the login form. Of course, when this
		// user surpasses their maximum number of attempts they will get locked out.
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
		$user->authenticated();
	}

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
		return zbase_url('login');
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

}
