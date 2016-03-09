<?php

namespace Zbase\Http\Controllers\Laravel;

/**
 * BackendController
 *
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file BackendController.php
 * @project Zbase
 * @package Zbase\Http\Controllers
 */
use Zbase\Http\Controllers\Laravel\Controller;

class BackendController extends Controller
{

	/**
	 * Create a new authentication controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		zbase_in_back();
		zbase_view_pageTitle('Admin');
	}

	public function index()
	{
		return $this->view(zbase_view_file('index.home'));
	}

}
