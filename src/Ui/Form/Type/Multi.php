<?php

namespace Zbase\Ui\Form\Type;

/**
 * Zbase-Form Element-Select
 *
 * Element-Type
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Multi.php
 * @project Zbase
 * @package Zbase/Ui/Form/Elements
 */
class Multi extends \Zbase\Ui\Form\Element
{

	/**
	 * The MultiOptions
	 * @var string|array [value => label]|publishStatus|enabledisable|enable|disable|yesno|yes|no
	 */
	protected $_multiOptions = null;

	/**
	 * Set the Multi Options
	 * @param array $multiOptions
	 * @return \Zbase\Ui\Form\Type\Multi
	 */
	public function setMultiOptions($multiOptions)
	{
		$this->_multiOptions = zbase_data_get($multiOptions, null, $multiOptions);
		return $this;
	}

	/**
	 * Return the multi options
	 * @return array
	 */
	public function getMultiOptions()
	{
		if(is_string($this->_multiOptions))
		{
			if(strtolower($this->_multiOptions) == 'publishstatus')
			{
				return $this->getPublishStatusOptions();
			}
			if(strtolower($this->_multiOptions) == 'enabledisable')
			{
				return $this->getEnableDisableOptions();
			}
			if(strtolower($this->_multiOptions) == 'yesno')
			{
				return $this->getYesNoOptions();
			}
		}
		return $this->_multiOptions;
	}

	/**
	 * Return the Publish Status Options
	 * @return array
	 */
	public function getPublishStatusOptions()
	{
		$options = [
			0 => 'Hide',
			1 => 'Draft',
			2 => 'Publish'
		];
		if($this->_mode == 'display')
		{
			$options[0] = 'Hidden';
			$options[3] = 'Published';
		}
		return $options;
	}

	/**
	 * Return the Enable/Disable Options
	 * @return array
	 */
	public function getEnableDisableOptions()
	{
		$options = [
			1 => 'Enable',
			0 => 'Disable',
		];
		if($this->_mode == 'display')
		{
			$options[0] = 'Enabled';
			$options[1] = 'Disabled';
		}
		return $options;
	}

	/**
	 * Return the Yes/No Options
	 * @return array
	 */
	public function getYesNoOptions()
	{
		$options = [
			1 => 'Yes',
			0 => 'No',
		];
		return $options;
	}

	/**
	 * Render the multioptions
	 * @return string
	 */
	public function renderMultiOptions()
	{
		return '';
	}

}
