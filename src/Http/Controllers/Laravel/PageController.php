<?php

namespace Zbase\Http\Controllers\Laravel;

/**
 * PageController
 *
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file PageController.php
 * @project Zbase
 * @package Zbase\Http\Controllers
 */
use Zbase\Http\Controllers\Laravel\Controller;
use Zbase\Traits\Auth as ZbaseAuth;

class PageController extends Controller
{

	use ZbaseAuth;

	public function index()
	{
		return $this->view(zbase_view_file('index.index'));
	}

	public function home()
	{
		return $this->view(zbase_view_file('index.home'));
	}

	public function maintenance()
	{
		return $this->view(zbase_view_file('maintenance'));
	}

	public function contact()
	{
		$this->_contactUs();
		return $this->view(zbase_view_file('page.contact'));
	}

	public function js()
	{
		return $this->view(zbase_view_file('page.js'));
	}

	protected function _contactUs()
	{
		$success = false;
		if($this->isPost())
		{
			if(!zbase_captcha_verify())
			{
				return $this->buildFailedValidationResponse(zbase_request(), ['ReCAPTCHA Validation failed.']);
			}
			$validatorMessages = [
				'email.required' => _zt('Email Address is required.'),
				'email.email' => _zt('Invalid email address.'),
				'comment.required' => _zt('Message is required.'),
				'name.required' => _zt('Name is required.'),
			];
			$rules = [
				'email' => 'required|email',
				'comment' => 'required',
				'name' => 'required',
			];
			$valid = $this->validateInputs(zbase_request_inputs(), $rules, $validatorMessages);
			if(!empty($valid))
			{
				$data = zbase_request_inputs();
				$success = zbase_messenger_email(
						'contactus', zbase_request_input('email'), _zt(zbase_site_name() . ' - Contact Us Form - ' . zbase_request_input('name')), zbase_view_file_contents('email.contactus'), $data);
				if(!empty($success))
				{
					zbase_alert('success', _zt('Message sent!'));
					zbase()->json()->setVariable('contact_success', 1);
					if(!zbase_is_json())
					{
						return redirect(zbase_url_previous());
					}
				}
				else
				{
					zbase_alert('error', _zt('There was a problem sending your message. Kindly try again!'));
				}
			}
		}
	}

	/**
	 * Render a View File
	 * @param string $view The View File
	 * @return view
	 */
	public function renderViewFile($view)
	{
		return $this->view($view);
	}

	/**
	 * Used only for testing
	 * @return view
	 */
	public function form()
	{
		if($this->isPost())
		{
			$validatorMessages = [
				'email.required' => _zt('Email Address is required.'),
				'email.email' => _zt('Invalid email address.'),
			];
			$valid = $this->validateInputs(zbase()->request()->inputs(), ['email' => 'required|email'], $validatorMessages);
			if(!empty($valid))
			{
				$this->message('success', _zt('Successfull!'));
			}
		}
		return $this->view('form');
	}

}
