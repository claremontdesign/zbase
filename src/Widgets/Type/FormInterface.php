<?php

namespace Zbase\Widgets\Type;

/**
 * Zbase-Interface Widget Interface
 *
 * FormInterface
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file FormInterface.php
 * @project Zbase
 * @package Zbase/Widgets
 */
interface FormInterface
{

	/**
	 * Set the form or the parent Form
	 * @param \Zbase\Widgets\Type\FormInterface $form
	 */
	public function setForm(\Zbase\Widgets\Type\FormInterface $form);

	/**
	 * Render the form
	 */
	public function __toString();
}
