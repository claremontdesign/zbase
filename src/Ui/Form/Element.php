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
 * $configuration.help.text
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

class Element extends \Zbase\Ui\Ui implements \Zbase\Ui\Form\ElementInterface, Interfaces\IdInterface, Interfaces\ValidationInterface, \Zbase\Ui\UiInterface, \Zbase\Widgets\Type\FormInterface
{

	use Traits\Attribute,
	 Traits\Id,
	 Traits\Validations,
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
		if($this->getType() == 'hidden')
		{
			$this->_value = $this->_v('forceValue', $value);
		}
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
			$this->_value = zbase_form_old($this->name());
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
		if($this->hasValidations())
		{
			if(zbase_is_angular_template())
			{
				$angularPrefix = $this->_form->getHtmlId() . '.' . $this->name();
				$attr['ng-class'] = [];
				$attr['ng-class']['has-error'] = [];
				$attr['ng-class']['has-error'][] = $angularPrefix . '.$dirty';
				if($this->hasValidation('required'))
				{
					$attr['ng-class']['has-error'][] = $angularPrefix . '.$error.required';
				}
				if(!empty($attr['ng-class']) && is_array($attr['ng-class']))
				{
					foreach ($attr['ng-class'] as $v => $k)
					{
						if(is_array($k))
						{
							$attr['ng-class'][$v] = implode(' && ', $k);
						}
					}
					$attr['ng-class'] = str_replace(array('{"', '":"', '"}'), array('{\'', '\': ', ' }'), json_encode($attr['ng-class']));
				}
			}
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
	 * The Input name
	 * @return string
	 */
	public function inputName()
	{
		return $this->name();
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
		$attr['name'] = $this->inputName();
		$attr['value'] = trim($this->getValue());
		$attr['class'][] = 'form-control';
		if(!isset($attr['placeholder']))
		{
			$attr['placeholder'] = $this->getLabel();
		}
		if(zbase_is_angular_template())
		{
			$ngModel = $this->_v('angular.ngModel', null);
			if(is_array($ngModel))
			{
				if(!empty($ngModel['prefix']))
				{
					$ngModel = $ngModel['prefix'] . '.' . $this->name();
				}
				if(!empty($ngModel['ngModel']))
				{
					$ngModel = $ngModel['ngModel'];
				}
			}
			if(!empty($ngModel))
			{
				$attr['ng-model'] = $ngModel;
			}
			if($this->hasValidation('min'))
			{
				$minValidation = $this->getValidation('min');
				if(is_array($minValidation))
				{
					$attr['ng-minlength'] = $minValidation[1];
				}
			}
		}
		if($this->hasValidations())
		{
			if($this->hasValidation('required'))
			{
				$attr['required'] = null;
			}
		}
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
			if(!empty($configuration['validations']))
			{
				$widget = zbase()->widget($configuration['widget'], [], true)->setAttribute('validations', $configuration['validations']);
			}
			else
			{
				$widget = zbase()->widget($configuration['widget'], [], true);
			}
			return $widget;
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
				$entityOptionsProperty = $this->_v('entity.dataoptions', null);
				if(!is_null($entityOptionsProperty))
				{
					if(!$this->wasPosted())
					{
						$options = $this->_entity->getDataOptions();
						if(isset($options[$entityOptionsProperty]))
						{
							$this->setValue($options[$entityOptionsProperty]);
						}
					}
				}
			}
			return $this;
		}
		return $this->_entity;
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
