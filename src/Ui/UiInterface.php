<?php

namespace Zbase\Ui;

/**
 * Zbase-Form UiInterface
 *
 * UiInterface Base Model
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file UiInterface.php
 * @project Zbase
 * @package Zbase/Ui
 */
interface UiInterface
{

	public function __toString();

	/**
	 * The UI Id
	 */
	public function id();

	/**
	 * Prepare the UI
	 */
	public function prepare();
}
