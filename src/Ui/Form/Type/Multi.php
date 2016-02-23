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
	 * @var array
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
		return $this->_multiOptions;
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
