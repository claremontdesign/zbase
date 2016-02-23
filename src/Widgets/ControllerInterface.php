<?php

namespace Zbase\Widgets;

/**
 * Zbase-Interface Controller Interface
 *
 * ControllerInterface
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file ControllerInterface.php
 * @project Zbase
 * @package Zbase/Widgets
 */
interface ControllerInterface
{

	/**
	 * Controller Actions|methods
	 */
	public function controller();

	/**
	 * Validate Inputs
	 */
	public function validate();
}
