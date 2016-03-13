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

class Element extends \Zbase\Ui\Ui implements \Zbase\Ui\Form\ElementInterface, Interfaces\IdInterface, \Zbase\Ui\UiInterface, \Zbase\Widgets\Type\FormInterface
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
	 * Display Mode
	 * input|display
	 * @var string
	 */
	protected $_mode = 'input';

	/**
	 * The form
	 * @var \Zbase\Widgets\Type\FormInterface
	 */
	protected $_form = null;

	/**
	 * The entity
	 * @var Zbase\Widgets\EntityInterface
	 */
	protected $_entity = null;

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
	 * Fix validations flag
	 * @var boolean
	 */
	protected $_fixValidation = false;

	/**
	 * Validation Rules
	 * @var array|
	 */
	protected $_validationMessages = [];

	/**
	 * Validation Messages
	 * @var array
	 */
	protected $_validationRules = [];

	/**
	 * Array of Error Messages
	 * @var array
	 */
	protected $_errors = [];

	/**
	 * The Tab that this element belongs
	 * @var string
	 */
	protected $_tab = null;

	/**
	 * Add Errors
	 * @param array $errors
	 * @return \Zbase\Ui\Form\ElementInterface
	 */
	public function setErrors($errors)
	{
		$this->_errors = $errors;
		return $this;
	}

	/**
	 * Check if there is an error
	 * @return boolean
	 */
	public function hasError()
	{
		$currentTab = zbase_session_get('sessiontab', false);
		if(!empty($currentTab))
		{
			if($this->getTab() != $currentTab)
			{
				return false;
			}
		}
		if($msg = zbase_form_input_has_error($this->name()))
		{
			if(!in_array($msg, $this->_errors))
			{
				$this->_errors[] = $msg;
			}
			return true;
		}
		return !empty($this->_errors);
	}

	/**
	 * Check if was posted
	 * @return boolean
	 */
	public function wasPosted()
	{
		$currentTab = zbase_session_get('sessiontab', false);
		if(!empty($currentTab))
		{
			if($this->getTab() != $currentTab)
			{
				return false;
			}
		}
		if($this->form() instanceof \Zbase\Widgets\Type\FormInterface)
		{
			return $this->form()->wasPosted();
		}
		return false;
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
		if($this->wasPosted())
		{
			return zbase_form_old($this->name());
		}
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
		if($this->hasError())
		{
			$attr['class'][] = 'has-error';
		}
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
		$attr['value'] = $this->getValue();
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
		/**
		 * Load a widget
		 */
		if(!empty($configuration['widget']))
		{
			return zbase()->widget($configuration['widget'], true);
		}
		if(!empty($configuration['ui']))
		{
			return \Zbase\Ui\Ui::factory($configuration['ui']);
		}
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

	/**
	 * Set the Form
	 * @param \Zbase\Widgets\Type\FormInterface $form
	 * @return \Zbase\Ui\Form\Element
	 */
	public function form(\Zbase\Widgets\Type\FormInterface $form = null)
	{
		if(!is_null($form))
		{
			$this->_form = $form;
			return $this;
		}
		return $this->_form;
	}

	/**
	 * Set/Get the entity
	 * @param \Zbase\Widgets\EntityInterface $entity
	 */
	public function entity(\Zbase\Widgets\EntityInterface $entity = null)
	{
		if(!is_null($entity))
		{
			$this->_entity = $entity;
			if(!$this->hasError())
			{
				$entityProperty = $this->_v('entity.property', null);
				if(!is_null($entityProperty))
				{
					if(!$this->wasPosted())
					{
						$this->setValue($this->_entity->getAttribute($entityProperty));
					}
				}
			}
			return $this;
		}
		return $this->_entity;
	}

	/**
	 * Return all validation rules
	 * @return array
	 */
	public function getValidationRules()
	{
		if(empty($this->_fixValidation))
		{
			$this->_validation();
		}
		if($this->hasValidations())
		{
			return implode('|', $this->_validationRules);
		}
	}

	/**
	 * REturn the validation messages
	 * @return array
	 */
	public function getValidationMessages()
	{
		if(empty($this->_fixValidation))
		{
			$this->_validation();
		}
		return $this->_validationMessages;
	}

	/**
	 * Check if there are validations
	 * @return boolean
	 */
	public function hasValidations()
	{
		if(empty($this->_fixValidation))
		{
			$this->_validation();
		}
		return !empty($this->_validationRules);
	}

	/**
	 * Extract validation
	 * validations.type = configuration
	 * validations.required = configuration
	 *
	 * validations.required
	 * validations.required.message
	 */
	protected function _validation()
	{
		$validations = $this->_v('validations', []);
		$this->_fixValidation = true;
		if(!empty($validations))
		{
			foreach ($validations as $type => $config)
			{
				$enable = !empty($config['enable']) ? true : false;
				if(!empty($enable))
				{
					if(!empty($config['text']))
					{
						$this->_validationRules[] = zbase_data_get($config, 'text');
					}
					else
					{
						if(!in_array($type, $this->_validationRules))
						{
							$this->_validationRules[] = $type;
						}
					}
					if(!empty($config['message']))
					{
						$this->_validationMessages[$this->name() . '.' . $type] = $config['message'];
					}
				}
			}
		}
	}

	/**
	 * Return the HelpText
	 */
	public function helpText()
	{
		$helpText = [];
		$helpText[] = $this->_v('help.text', null);
		if($this->hasError())
		{
			foreach ($this->_errors as $error)
			{
				$helpText[] = '<span class="error-msg">' . $error . '</span>';
			}
		}
		return implode('<br />', $helpText);
	}

	/**
	 * Set the Tab that this element blong
	 * @param string $tabName The Tab Name
	 * @return \Zbase\Ui\Form\Element
	 */
	public function setTab($tabName)
	{
		$this->_tab = $tabName;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getTab()
	{
		return $this->_tab;
	}

	/**
	 * Set Mode
	 * @param string $mode
	 * @return \Zbase\Ui\Form\Element
	 */
	public function setMode($mode)
	{
		$this->_mode = $mode;
		return $this;
	}

	public function getMode()
	{
		return $this->_mode;
	}
}
