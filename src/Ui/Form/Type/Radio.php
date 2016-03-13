<?php

namespace Zbase\Ui\Form\Type;

/**
 * Zbase-Form Element-Text
 *
 * Element-Radio
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Radio.php
 * @project Zbase
 * @package Zbase/Ui/Form/Elements
 */
class Radio extends \Zbase\Ui\Form\Type\Multi
{

	/**
	 * Widget Type
	 * @var string
	 */
	protected $_type = 'radio';

	/**
	 * The view File to use
	 * @var string
	 */
	protected $_viewFile = 'ui.form.type.radio';

	/**
	 * If only to render an option
	 * @var boolean
	 */
	protected $_renderOption = false;

	/**
	 * Inline Layout
	 * @var boolean
	 */
	protected $_inline = false;

	/**
	 * Return the Wrapper Attributes
	 * @return array
	 */
	public function wrapperAttributes()
	{
		$attr = parent::wrapperAttributes();
		foreach ($attr['class'] as $k => $v)
		{
			if($v == 'form-group')
			{
				unset($attr['class'][$k]);
			}
		}
		if(!empty($this->_renderOption) && !empty($this->_inline))
		{
			$attr['class'][] = $this->_type . '-inline';
		}
		else
		{
			$attr['class'][] = $this->_type;
		}
		return $attr;
	}

	/**
	 * Return the Input Attributes
	 * @return array
	 */
	public function inputAttributes()
	{
		$attr = parent::inputAttributes();
		foreach ($attr['class'] as $k => $v)
		{
			if($v == 'form-control')
			{
				unset($attr['class'][$k]);
			}
		}
		return $attr;
	}

	/**
	 * Render an Option only
	 * @param type $renderOption
	 * @return \Zbase\Ui\Form\Type\Radio
	 */
	public function setRenderOption($renderOption)
	{
		$this->_renderOption = $renderOption;
		return $this;
	}

	/**
	 * Set the layout inline mode
	 * @param boolean $flag
	 * @return \Zbase\Ui\Form\Type\Radio
	 */
	public function setInline($flag)
	{
		$this->_inline = $flag;
		return $this;
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
			$counter = 0;
			foreach ($multiOptions as $k => $v)
			{
				$elementName = $this->name();
				$elementOptions = [
					'type' => $this->_type,
					'id' => $elementName . $counter,
					'label' => $v,
					'name' => $elementName,
					'renderOption' => true,
					'inline' => $this->_inline,
					'value' => $k
				];
				if($k == $this->getValue())
				{
					$elementOptions['html']['attributes']['input']['checked'] = 'checked';
				}
				$options[] = self::factory($elementOptions)->__toString();
				$counter++;
			}
			return implode('', $options);
		}
		return '';
	}

	/**
	 * HTML the widget
	 * @return string
	 */
	public function __toString()
	{
		if(!empty($this->_renderOption))
		{
			return parent::__toString();
		}
		$this->_prepared();
		$str = '<div id="' . $this->_type . '-' . $this->getHtmlId() . '-form-group" class="' . $this->_type . '-form-group">';
		$str .= $this->renderMultiOptions();
		$str .= \View::make(zbase_view_file_contents('ui.form.helpblock'), array('ui' => $this))->__toString();
		$str .= '</div>';
		return $str;
	}

}
