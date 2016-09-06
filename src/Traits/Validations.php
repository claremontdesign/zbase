<?php

namespace Zbase\Traits;

/**
 * Zbase-Validations
 *
 * Reusable Methods Validations
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Validations.php
 * @project Zbase
 * @package Zbase/Traits
 */
trait Validations
{

	/**
	 * Fix validations flag
	 * @var boolean
	 */
	protected $_fixValidation = false;

	/**
	 * Validation Rules
	 * @var array|
	 */
	protected $_validationMessages = [];

	/**
	 * Validation Messages
	 * @var array
	 */
	protected $_validationRules = [];

	/**
	 * Return all validation rules
	 * @return array
	 */
	public function getValidationRules($action = null)
	{
		if(empty($this->_fixValidation))
		{
			$this->_validation($action);
		}
		if($this->hasValidations())
		{
			return implode('|', $this->_validationRules);
		}
	}

	/**
	 * REturn the validation messages
	 * @return array
	 */
	public function getValidationMessages($action = null)
	{
		if(empty($this->_fixValidation))
		{
			$this->_validation($action);
		}
		return $this->_validationMessages;
	}

	/**
	 * Check if there are validations
	 * @return boolean
	 */
	public function hasValidations($action = null)
	{
		if(empty($this->_fixValidation))
		{
			$this->_validation($action);
		}
		return !empty($this->_validationRules);
	}

	/**
	 * Extract validation
	 * validations.type = configuration
	 * validations.required = configuration
	 *
	 * validations.required
	 * validations.required.message
	 */
	protected function _validation($action = null)
	{
		$section = zbase_section();
		if(empty($action))
		{
			$action = zbase_route_input('action');
		}
		$validations = $this->_v('validations.' . $action . '.' . $section, $this->_v('validations.' . $action, $this->_v('validations', [])));
		$this->_fixValidation = true;
		if(!empty($validations))
		{
			foreach ($validations as $type => $config)
			{
				$enable = zbase_data_get($config, 'enable');
				// $enable = $enable ? true : false;
				if(!empty($enable))
				{
					if(!empty($config['text']))
					{
						$this->_validationRules[] = zbase_data_get($config, 'text');
					}
					else
					{
						if(!in_array($type, $this->_validationRules))
						{
							$this->_validationRules[] = $type;
						}
					}
					if(!empty($config['message']))
					{
						$this->_validationMessages[$this->name() . '.' . $type] = zbase_data_get($config, 'message');
					}
				}
			}
		}
	}


	/**
	 * Check if a rule exists in the validation
	 * @param string $rule Rule NAme
	 *
	 * @return boolean
	 */
	public function hasValidation($ruleName)
	{
		if($this->hasValidations())
		{
			foreach($this->_validationRules as $rule)
			{
				if($ruleName == $rule || preg_match('/^'.$ruleName.':/i', $rule) > 0)
				{
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * Return a Validation Rule
	 * @param string $ruleName
	 *
	 * @return array|string|null
	 */
	public function getValidation($ruleName)
	{
		if($this->hasValidations())
		{
			foreach($this->_validationRules as $rule)
			{
				if($ruleName == $rule || preg_match('/^'.$ruleName.':/i', $rule) > 0)
				{
					if($ruleName == 'min')
					{
						return explode(':', $rule);
					}
					return $rule;
				}
			}
		}
		return null;
	}
}
