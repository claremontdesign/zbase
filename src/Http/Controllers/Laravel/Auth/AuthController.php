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
	ThrottlesLogins;

	/**
	 * Where to redirect users after login / registration.
	 *
	 * @var string
	 */
	protected $redirectTo = '/home';

	/**
	 * Show the application login form.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getLogin()
	{
		return $this->view(zbase_view_file('auth.login'));
	}

	/**
	 * Create a new authentication controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest', ['except' => 'logout']);
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
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return User
	 */
	protected function create(array $data)
	{
		return zbase_entity('user')->create([
					'name' => $data['name'],
					'email' => $data['email'],
					'password' => bcrypt($data['password']),
		]);
	}

}
