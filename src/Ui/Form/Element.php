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
 */
use Zbase\Traits;

class Element extends \Zbase\Ui\Ui implements \Zbase\Ui\Form\ElementInterface
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
	 * Constructor
	 * @param string $id
	 * @param array $configuration
	 */
	public function __construct($id, $configuration)
	{
		$this->id = $id;
		$this->setAttributes($configuration);
		$this->setName($id);
		if(empty($configuration['id']))
		{
			$this->setId($id);
		}
	}

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
	 * @param string $name
	 * @param array $configuration
	 * @return \Zbase\Ui\Form\Element
	 */
	public static function factory($name, $configuration)
	{
		$type = !empty($configuration['type']) ? $configuration['type'] : 'text';
		if(!empty($type))
		{
			$className = zbase_config_get('class.ui.form.element.' . strtolower($type), '\Zbase\Ui\Form\Type\\' . ucfirst($type));
			$element = new $className($name, $configuration);
			$element->prepare();
			return $element;
		}
		return null;
	}

}
