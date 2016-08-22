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
		$token = zbase_route_input('token');
		if(!empty($token))
		{
			return $this->reset();
		}
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
		$messages = [
			'email.exists' => 'Invalid token given.'
		];
		$this->validate($request, [
			'token' => 'required',
			'email' => 'required|email|exists:user_tokens,email,token,' . zbase_request_input('token', '_'),
			'password' => 'required|confirmed|min:6|same:password_confirmation',
		], $messages);

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

				zbase()->json()->setVariable('_redirect', $this->redirectPath());
				zbase()->json()->setVariable('password_reset_success', 1);
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
     * Get the Closure which is used to build the password reset email message.
     *
     * @return \Closure
     */
    protected function resetEmailBuilder()
    {
        return function (Message $message) {
            $message->subject($this->getEmailSubject());
			$noReply = zbase_messenger_sender('noreply');
			$message->from($noReply[0], $noReply[1]);
        };
    }

	/**
	 * Send a reset link to the given user.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function postEmail(Request $request)
	{
		// $this->validate($request, ['email' => 'required|email|exists:' . zbase_config_get('entity.user.table.name') . ',email']);
		$entity = zbase()->entity('user', [], true);
		$user = $entity->repo()->by('email', zbase_request_input('email'))->first();
		if(!empty($user))
		{
			$broker = $this->getBroker();
			$response = \Password::broker($broker)->sendResetLink(
				$this->getSendResetLinkEmailCredentials($request),
				$this->resetEmailBuilder()
			);
			$user->lostPassword();
		}
		zbase()->json()->setVariable('password_success', 1);
		zbase()->json()->setVariable('_redirect', zbase_url_previous());
		if(!zbase_is_json())
		{
			return redirect()->back()->with('status', trans(\Password::RESET_LINK_SENT));
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
