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

	public function addVariables($vars)
	{
		if(!empty($vars))
		{
			foreach ($vars as $key => $val)
			{
				$this->addVariable($key, $val);
			}
		}
		return $this;
	}

	public function setVariable($key, $val)
	{
		$this->addVariable($key, $val);
	}

	public function getVariables()
	{
		return $this->vars;
	}

	public function serve()
	{
		header('Content-Type: application/json');
		return json_encode($this->vars);
	}

}
