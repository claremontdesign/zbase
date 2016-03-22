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
	public function getValidationRules()
	{
		if(empty($this->_fixValidation))
		{
			$this->_validation();
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
	public function getValidationMessages()
	{
		if(empty($this->_fixValidation))
		{
			$this->_validation();
		}
		return $this->_validationMessages;
	}

	/**
	 * Check if there are validations
	 * @return boolean
	 */
	public function hasValidations()
	{
		if(empty($this->_fixValidation))
		{
			$this->_validation();
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
	protected function _validation()
	{
		$validations = $this->_v('validations', []);
		$this->_fixValidation = true;
		if(!empty($validations))
		{
			foreach ($validations as $type => $config)
			{
				$enable = !empty($config['enable']) ? true : false;
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
						$this->_validationMessages[$this->name() . '.' . $type] = $config['message'];
					}
				}
			}
		}
	}

}
