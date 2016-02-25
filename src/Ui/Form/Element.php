<?php

namespace Zbase\Ui\Form;

/**
 * Zbase-Form Element
 *
 * Element Base Model
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Element.php
 * @project Zbase
 * @package Zbase/Widgets
 *
 * $configuration.type
 * $configuration.subtype
 * $configuration.label
 * $configuration.title
 * $configuration.description
 * $configuration.help
 * $configuration.validation
 * $configuration.id
 * $configuration.multipOptions
 * $configuration.multipOptions.type
 * $configuration.value
 * $configuration.value.default
 * $configuration.value.post
 * $configuration.value.get
 * $configuration.html
 * $configuration.html.attributes
 * $configuration.html.attributes.input
 * $configuration.html.attributes.label
 * $configuration.html.attributes.wrapper
 * $configuration.viewFile
 * $configuration.elements
 *
 */
use Zbase\Traits;
use Zbase\Interfaces;
use Zbase\Exceptions;

class Element extends \Zbase\Ui\Ui implements \Zbase\Ui\Form\ElementInterface, Interfaces\IdInterface
{

	use Traits\Attribute,
	 Traits\Id,
	 Traits\Position,
	 Traits\Html;

	/**
	 * Widget Type
	 * @var string
	 */
	protected $_type = null;

	/**
	 * Child Elements
	 * @var \Zbase\Ui\Form\Element
	 */
	protected $_elements = null;

	/**
	 * Element Label
	 * @var string
	 */
	protected $_label = null;

	/**
	 * The view File to use
	 * @var string
	 */
	protected $_viewFile = 'ui.form.type.text';

	/**
	 * The Value
	 * @var string|integer
	 */
	protected $_value = null;

	/**
	 * Set the Value
	 * @param integer|string $value
	 * @return \Zbase\Ui\Form\Element
	 */
	public function setValue($value)
	{
		$this->_value = $value;
		return $this;
	}

	/**
	 * Return the Value
	 * @return integer|string
	 */
	public function getValue()
	{
		return $this->_value;
	}

	/**
	 * Return the Display Value
	 * @return string
	 */
	public function displayValue()
	{
		return $this->getValue();
	}

	/**
	 * Return the element type
	 * @return string
	 */
	public function getType()
	{
		return $this->_type;
	}

	/**
	 * Return the Element label
	 * @return string
	 */
	public function getLabel()
	{
		return $this->_label;
	}

	/**
	 * Set Label
	 * @param type $label
	 * @return \Zbase\Ui\Form\Element
	 */
	public function setLabel($label)
	{
		$this->_label = $label;
		return $this;
	}

	/**
	 * Return the Wrapper Attributes
	 * @return array
	 */
	public function wrapperAttributes()
	{
		$attr = $this->_v('html.attributes.wrapper', []);
		$attr['class'][] = 'zbase-ui-wrapper';
		$attr['class'][] = 'zbase-ui-wrapper-form-element';
		$attr['class'][] = 'zbase-ui-wrapper-form-element-' . $this->_type;
		$attr['class'][] = 'form-group';
		return $attr;
	}

	/**
	 * Return the Label Attributes
	 * @return array
	 */
	public function labelAttributes()
	{
		$attr = $this->_v('html.attributes.label', []);
		$attr['for'] = $this->getHtmlId();
		return $attr;
	}

	/**
	 * Return the Input Attributes
	 * @return array
	 */
	public function inputAttributes()
	{
		$attr = $this->_v('html.attributes.input', []);
		$attr['type'] = $this->getType();
		$attr['id'] = $this->getHtmlId();
		$attr['name'] = $this->name();
		$attr['class'][] = 'form-control';
		return $attr;
	}

	/**
	 * PreParation
	 * @return void
	 */
	protected function _pre()
	{
		$this->_elements();
	}

	/**
	 * Process child elements
	 */
	protected function _elements()
	{
		$elements = $this->_v('elements', null);
		if(!is_null($elements) && is_array($elements))
		{
			foreach ($elements as $name => $element)
			{
				$this->_elements[$name] = self::factory($name, $element);
			}
			$this->_elements = $this->sortPosition($this->_elements);
		}
	}

	/**
	 * Element Factory
	 * @param array $configuration
	 * @return \Zbase\Ui\Form\Element
	 */
	public static function factory($configuration)
	{
		$type = !empty($configuration['type']) ? $configuration['type'] : 'text';
		$id = !empty($configuration['id']) ? $configuration['id'] : null;
		if(is_null($id))
		{
			throw new Exceptions\ConfigNotFoundException('Index:id is not set on Form Element Factory');
		}
		if(!empty($type))
		{
			$className = zbase_model_name(null, 'class.ui.form.type.' . strtolower($type), '\Zbase\Ui\Form\Type\\' . ucfirst($type));
			$element = new $className($configuration);
			$element->prepare();
			return $element;
		}
		return null;
	}

}
