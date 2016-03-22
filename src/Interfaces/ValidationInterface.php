<?php

namespace Zbase\Interfaces;

/**
 * Zbase-Interface-ValidationInterface
 *
 * ValidationInterface
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file ValidationInterface.php
 * @project Zbase
 * @package Zbase/Interfaces
 */
interface ValidationInterface
{


	/**
	 * Return all validation rules
	 * @return array
	 */
	public function getValidationRules();

	/**
	 * REturn the validation messages
	 * @return array
	 */
	public function getValidationMessages();

	/**
	 * Check if there are validations
	 * @return boolean
	 */
	public function hasValidations();
}
