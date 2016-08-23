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
			if(strtolower($this->_multiOptions) == 'us_states')
			{
				return $this->getCountryStates('us');
			}
			if(strtolower($this->_multiOptions) == 'userroles')
			{
				return $this->getUserRoles();
			}
			if(strtolower($this->_multiOptions) == 'userstatus')
			{
				return $this->getUserStatusOptions();
			}
		}
		return $this->_multiOptions;
	}

	/**
	 * Return User Role Group
	 */
	public function getUserRoles()
	{
		$roles = \Zbase\Entity\Laravel\User\Role::listAllRoles();
		$options = [];
		foreach ($roles as $role)
		{
			$options[$role] = ucfirst($role);
		}
		return $options;
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
			$options[0] = '<span class="label label-danger">Hidden</span>';
			$options[3] = '<span class="label label-success">Published</span>';
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
	 * Return the Yes/No Options
	 * @return array
	 */
	public function getUserStatusOptions()
	{
		$options = [
			'ok' => 'Ok',
			'ban' => 'Banned',
			'locked' => 'Locked',
			'ban_no_auth' => 'Disabled'
		];
		return $options;
	}

	/**
	 * Return the Country States
	 * @param string $country ISO2 CountryName
	 * @return array [ISO2 => StateName]
	 */
	public function getCountryStates($country)
	{
		$file = zbase_path_library('Geo/' . strtoupper($country) . '/states.php');
		if(zbase_file_exists($file))
		{
			return require $file;
		}
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
