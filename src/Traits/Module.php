<?php

namespace Zbase\Traits;

/**
 * Zbase-Module
 *
 * Reusable Methods Module
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Module.php
 * @project Zbase
 * @package Zbase/Traits
 */
trait Module
{

	/**
	 * The Attributes
	 *
	 * @var array
	 */
	protected $module;


	/**
	 * Set the Module
	 * @param \Zbase\Http\Controllers\Laravel\Module\ModuleInterface $module
	 * @return \Zbase\Http\Controllers\Laravel\BackendModuleController
	 */
	public function setModule(\Zbase\Module\ModuleInterface $module)
	{
		$this->module = $module;
		return $this;
	}

	/**
	 *
	 * @return \Zbase\Module\ModuleInterface
	 */
	public function getModule()
	{
		return $this->module;
	}
}
