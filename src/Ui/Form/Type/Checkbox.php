<?php

namespace Zbase\Ui\Form\Type;

/**
 * Zbase-Form Element-Checkbox
 *
 * Element-Checkbox
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Checkbox.php
 * @project Zbase
 * @package Zbase/Ui/Form/Elements
 */
class Checkbox extends \Zbase\Ui\Form\Type\Radio
{

	/**
	 * Widget Type
	 * @var string
	 */
	protected $_type = 'checkbox';

	/**
	 * The view File to use
	 * @var string
	 */
	protected $_viewFile = 'ui.form.type.checkbox';

	/**
	 * If only to render an option
	 * @var boolean
	 */
	protected $_renderOption = false;

	/**
	 * Return the multi options
	 * @return array
	 */
//	public function getMultiOptions()
//	{
//		$multiOptions = parent::getMultiOptions();
//		if(empty($multiOptions))
//		{
//			return [
//				$this->id() => $this->label()
//			];
//		}
//	}
//
//	public function renderMultiOptions()
//	{
//		$multiOptions = $this->getMultiOptions();
//		if(!empty($multiOptions))
//		{
//			$options = [];
//			$counter = 0;
//			foreach ($multiOptions as $k => $v)
//			{
//				$options[] = '<label><input type="checkbox" value="' . $k . '">' . $v . '</label>';
//			}
//			return implode('', $options);
//		}
//		return '';
//	}

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
		$labelClass = $this->_v('html.attributes.label.class', []);
		$this->_prepared();
		$str = '<div id="' . $this->_type . '-' . $this->getHtmlId() . '-form-group" class="col-md-12 ' . $this->_type . '-form-group form-group">';
		$str .= '<label class="' . implode(' ', $labelClass) . '">' . $this->getLabel() . '</label>';
		$str .= '<div class="checkbox-list">';
		$str .= $this->renderMultiOptions();
		$str .= '</div>';
		$str .= \View::make(zbase_view_file_contents('ui.form.helpblock'), array('ui' => $this))->__toString();
		$str .= '</div>';
		return $str;
	}

}
