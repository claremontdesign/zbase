<?php

namespace Zbase\Ui\Form\Type;

/**
 * Zbase-Form Element-Text
 *
 * Element-Select
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Select.php
 * @project Zbase
 * @package Zbase/Ui/Form/Elements
 */
class Select extends \Zbase\Ui\Form\Type\Multi
{

	/**
	 * Widget Type
	 * @var string
	 */
	protected $_type = 'select';

	/**
	 * The view File to use
	 * @var string
	 */
	protected $_viewFile = 'ui.form.type.select';

	/**
	 * The empty option
	 * null
	 * boolean: true|false
	 * array: [value => the Value, label => The Empty Label]
	 * @var boolean|array
	 */
	protected $_emptyOption = null;

	/**
	 * SEt the Empty Option
	 * @param boolean|array|null $emptyOption
	 * @return \Zbase\Ui\Form\Type\Select
	 */
	public function setEmptyOption($emptyOption)
	{
		$this->_emptyOption = $emptyOption;
		return $this;
	}

	/**
	 * Return the empty options
	 * @return boolean|array|null
	 */
	public function getEmptyOption()
	{
		return $this->_emptyOption;
	}

	/**
	 * Render the multioptions
	 * @return string
	 */
	public function renderMultiOptions()
	{
		$multiOptions = $this->getMultiOptions();
		if(!empty($multiOptions))
		{
			$options = [];
			$emptyOption = $this->getEmptyOption();
			if(!empty($emptyOption))
			{
				if(is_array($emptyOption) && !empty($emptyOption['enable']))
				{
					$options[] = '<option value="' . (!empty($emptyOption['value']) ? $emptyOption['value'] : '') . '">' . (!empty($emptyOption['label']) ? $emptyOption['label'] : '') . '</option>';
				}
				if(is_bool($emptyOption))
				{
					$options[] = '<option value="">Select...</option>';
				}
			}
			foreach ($multiOptions as $k => $v)
			{
				$selected = $this->getValue() == $k ? ' selected="selected"' : '';
				$options[] = '<option value="' . $k . '"' . $selected . '>' . $v . '</option>';
			}
			return implode('', $options);
		}
		return '';
	}

}
