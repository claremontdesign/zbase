<?php

namespace Zbase\Widgets\Type;

/**
 * Zbase-Widgets Widget-Type Form
 *
 * https://developer.mozilla.org/en-US/docs/Web/Accessibility/ARIA/ARIA_Techniques/Using_the_aria-labelledby_attribute
 * http://v4-alpha.getbootstrap.com/components/forms/#form-controls
 * Process and Displays a dynamic form
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Form.php
 * @project Zbase
 * @package Zbase/Widgets/Type
 */
use Zbase\Interfaces;
use Zbase\Exceptions;
use Zbase\Traits;
use Zbase\Widgets;

class Form extends Widgets\Widget implements Widgets\WidgetInterface
{

	protected $_type = 'form';

	/**
	 * Element
	 * @var \Zbase\Ui\Form\Element[]
	 */
	protected $_elements = null;

	/**
	 * PreParation
	 * @return void
	 */
	protected function _pre()
	{
		$this->_elements();
	}

	/**
	 * Process all elements
	 * @return void
	 */
	protected function _elements()
	{
		if(is_null($this->_elements))
		{
			$elements = $this->_v('config.elements', null);
			if(!is_null($elements) && is_array($elements))
			{
				foreach ($elements as $name => $element)
				{
					$this->_elements[] = \Zbase\Ui\Form\Element($name, $element);
				}
			}
			$this->_elements = zbase_sort($this->_elements);
		}
	}

	/**
	 * Return the form elements
	 * @return \Zbase\Ui\Form\Element[]
	 */
	public function elements()
	{
		return $this->_elements;
	}

	/**
	 * Create element
	 * @param string $name
	 * @param array $element
	 */
	protected function _elementFactory($name, $element)
	{
		$children = !empty($element['children']) ? $element['children'] : false;
		if(!empty($children))
		{

		}
		$type = !empty($element['type']) ? $element['type'] : 'element';
		$eleClass = '\Zbase\Ui\Form\\' . $type;
	}

}
