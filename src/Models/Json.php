<?php

namespace Zbase\Models;

/**
 * Zbase-Model-Request
 *
 * Request Model
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Request.php
 * @project Zbase
 * @package Zbase/Model
 */
use Zbase\Models;

class Json
{

	/**
	 * The current module
	 * @var Models\Module
	 */
	protected $vars = null;

	public function __construct()
	{

	}

	/**
	 * Start
	 */
	public function start()
	{

	}

	public function addVariable($key, $val)
	{
		$this->vars[$key] = $val;
		return $this;
	}

	public function serve()
	{
		header('Content-Type: application/json');
		return json_encode($this->vars);
	}
}
