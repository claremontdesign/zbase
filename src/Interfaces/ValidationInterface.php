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
	 * @param string $action Current Action
	 * @return array
	 */
	public function getValidationRules($action = null);

	/**
	 * REturn the validation messages
	 * @param string $action Current Action
	 * @return array
	 */
	public function getValidationMessages($action = null);

	/**
	 * Check if there are validations
	 * @param string $action Current Action
	 * @return boolean
	 */
	public function hasValidations($action = null);
}
