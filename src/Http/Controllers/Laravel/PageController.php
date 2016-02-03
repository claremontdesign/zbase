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

class PageController extends Controller
{

	public function index()
	{
		return $this->view(zbase_view_file('index'));
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
				'email.required' => 'Email Address is required.',
				'email.email' => 'Invalid email address.'
			];
			$valid = $this->validateInputs(zbase()->request()->inputs(), ['email' => 'required|email'], $validatorMessages);
			if(!empty($valid))
			{
				$this->message('success', 'Successfull!!!');
			}
		}
		return $this->view('form');
	}

}
