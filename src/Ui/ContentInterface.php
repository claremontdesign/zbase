<?php

namespace Zbase\Ui\Form;

/**
 * Zbase-Form ContentInterface
 *
 * ContentInterface Base Model
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file ContentInterface.php
 * @project Zbase
 * @package Zbase/Ui
 */
interface ContentInterface
{

	public function __toString();

	/**
	 * The UI Id
	 */
	public function id();
}
