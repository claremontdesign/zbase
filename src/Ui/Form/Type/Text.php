<?php

namespace Zbase\Ui\Form\Type;

/**
 * Zbase-Form Element-Text
 *
 * Element-Type
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Type.php
 * @project Zbase
 * @package Zbase/Ui/Form/Elements
 */
class Text extends \Zbase\Ui\Form\Element
{

	/**
	 * Widget Type
	 * @var string
	 */
	protected $_type = 'text';

	/**
	 * Prepend Text
	 * http://getbootstrap.com/2.3.2/base-css.html#forms
	 * @var string
	 */
	protected $_inputPrepend = null;

	/**
	 * Append text
	 * http://getbootstrap.com/2.3.2/base-css.html#forms
	 * @var string
	 */
	protected $_inputAppend = null;

	public function setInputPrepend($value)
	{
		$this->_inputPrepend = zbase_value_get($value);
	}

	public function setInputAppend($value)
	{
		$this->_inputAppend = zbase_value_get($value);
	}

	public function getInputPrepend()
	{
		return $this->_inputPrepend;
	}

	public function getInputAppend()
	{
		return $this->_inputAppend;
	}

	/**
	 * Return the Wrapper Attributes
	 * @return array
	 */
	public function wrapperAttributes()
	{
		$attr = parent::wrapperAttributes();
		if(!empty($this->_inputPrepend) || !empty($this->_inputAppend))
		{
			$attr['class'][] = 'span2';
		}
		return $attr;
	}
}
