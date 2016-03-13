<?php

namespace Zbase\Http\Controllers\Laravel\Auth;

/**
 *
 * PasswordController
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file PasswordController.php
 * @project Zbase
 * @package Zbase\Models\View
 */
use Zbase\Http\Controllers\Laravel\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Zbase\Traits\Auth as ZbaseAuth;

class PasswordController extends Controller
{

	use ResetsPasswords,
	 ZbaseAuth;

	/**
	 * Create a new password controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest');
	}

	public function index()
	{
		if(!$this->authEnabled())
		{
			return $this->notfound('User authentication is disabled.');
		}
		if($this->isPost())
		{
			return $this->postEmail(zbase_request());
		}
		return $this->getEmail();
	}

	public function reset()
	{
		if(!$this->authEnabled())
		{
			return $this->notfound('User authentication is disabled.');
		}
		if($this->isPost())
		{
			return $this->postReset(zbase_request());
		}
		return $this->getReset(zbase_route_input('token', null));
	}

	/**
	 * Display the password reset view for the given token.
	 *
	 * @param  string  $token
	 * @return \Illuminate\Http\Response
	 */
	public function getReset($token = null)
	{
		if(is_null($token))
		{
			return $this->notfound();
		}
		$email = zbase_request_query_input('email', null);
		return $this->view(zbase_view_file('auth.password.reset'), array('token' => $token, 'email' => $email));
	}

	/**
	 * Reset the given user's password.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function postReset(Request $request)
	{
		$this->validate($request, [
			'token' => 'required',
			'email' => 'required|email',
			'password' => 'required|confirmed|min:6',
		]);

		$credentials = $request->only(
				'email', 'password', 'password_confirmation', 'token'
		);

		$response = \Password::reset($credentials, function ($user, $password) {
					$user->updatePassword($password);
					$this->resetPassword($user, $password);
		});


		switch ($response)
		{
			case \Password::PASSWORD_RESET:
				if($this->loginAfterReset())
				{
					zbase_alert(\Zbase\Zbase::ALERT_SUCCESS, 'You successfully updated your password.');
				}
				else
				{
					zbase_alert(\Zbase\Zbase::ALERT_SUCCESS, 'You successfully updated your password. You can login now.');
				}
				return redirect($this->redirectPath())->with('status', trans($response));
			case 'passwords.token':
				zbase_alert(\Zbase\Zbase::ALERT_ERROR, 'Token doesn\'t match, expired or not found. Kindly check again.');
			default:
				return redirect()->back()
								->withInput($request->only('email'))
								->withErrors(['email' => trans($response)]);
		}
	}

	/**
	 * Display the form to request a password reset link.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getEmail()
	{
		return $this->view(zbase_view_file('auth.password.email'));
	}

	/**
	 * Send a reset link to the given user.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function postEmail(Request $request)
	{
		$this->validate($request, ['email' => 'required|email|exists:' . zbase_config_get('entity.user.table.name') . ',' . zbase_config_get('entity.user.table.columns.email.name')]);

		$response = \Password::sendResetLink($request->only('email'), function (Message $message) {
					$message->sender(zbase_config_get('email.noreply.email'), zbase_config_get('email.noreply.name'));
					$message->subject($this->getEmailSubject());
		});

		switch ($response)
		{
			case \Password::RESET_LINK_SENT:
				zbase_alert(\Zbase\Zbase::ALERT_INFO, 'A link to reset your password was sent to your email address. Kindly check.');
				return redirect()->back()->with('status', trans($response));

			case \Password::INVALID_USER:
				return redirect()->back()->withErrors(['email' => trans($response)]);
		}
	}

	/**
	 * Reset the given user's password.
	 *
	 * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
	 * @param  string  $password
	 * @return void
	 */
	protected function resetPassword($user, $password)
	{
		if($this->loginAfterReset())
		{
			Auth::login($user);
		}
	}

	/**
	 * If to login after password reset
	 * @return boolean
	 */
	protected function loginAfterReset()
	{
		return zbase_config_get('auth.password.loginAfterReset', false);
	}

}
