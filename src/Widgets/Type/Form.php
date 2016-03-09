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
 *
 * elements
 * nested
 */
use Zbase\Widgets;
use Illuminate\Foundation\Validation\ValidatesRequests;

class Form extends Widgets\Widget implements Widgets\WidgetInterface, FormInterface, Widgets\ControllerInterface
{

	use ValidatesRequests;

	protected $_type = 'form';
	protected $_viewFile = 'ui.form.form';

	/**
	 * The Parent Form, if nested is true
	 * @var FormInterface
	 */
	protected $_form = null;

	/**
	 * Element
	 * @var \Zbase\Ui\Form\Element[]
	 */
	protected $_elements = null;

	/**
	 *
	 * @var \Zbase\Ui\Tabs
	 */
	protected $_tabs = null;

	/**
	 * The Element Values
	 * @var array
	 */
	protected $_validationRules = [];
	protected $_validationMessages = [];

	/**
	 * form has error
	 * @var boolean
	 */
	protected $_formHasError = false;

	// <editor-fold defaultstate="collapsed" desc="CONTROLLERInterface">

	/**
	 * set that form has error
	 */
	public function setFormHasError($errors = null)
	{
		$this->_formHasError = true;
		zbase_session_flash('errorForm' . $this->id(), true);
	}

	/**
	 * Check if form has error
	 * @return boolean
	 */
	public function formHasError()
	{
		return $this->_formHasError = zbase_session_flash('errorForm' . $this->id());
	}

	/**
	 * Controller Action
	 * @param string $action
	 */
	public function controller($action)
	{

	}

	/**
	 * Validate widget
	 */
	public function validateWidget()
	{
		if(zbase_request_method() == 'post')
		{
			$v = \Validator::make(zbase_request_inputs(), ['account_password' => 'required'], $this->getValidationMessages());
			if($v->fails())
			{
				$this->setFormHasError($v->errors()->getMessages());
				$messageBag = $v->getMessageBag();
				zbase_alert(\Zbase\Zbase::ALERT_ERROR, $messageBag, ['formvalidation' => true]);
				return $v;
			}
			$inputs = zbase_request_inputs();
			foreach ($inputs as $k => $v)
			{
				$e = $this->element($k);
				if($e instanceof \Zbase\Ui\Form\ElementInterface)
				{
					$e->setValue($v);
				}
			}
		}
	}

	/**
	 * Return the validation rules
	 * @return array
	 */
	public function getValidationRules()
	{
		return $this->_validationRules;
	}

	/**
	 * The validation messages
	 * @return array
	 */
	public function getValidationMessages()
	{
		return $this->_validationMessages;
	}

	// </editor-fold>

	/**
	 * PreParation
	 * @return void
	 */
	protected function _pre()
	{
		$this->_tabs();
		$this->_elements();
	}

	// <editor-fold defaultstate="collapsed" desc="Element and Tabs">
	/**
	 * Process all elements
	 * @return void
	 */
	protected function _elements()
	{
		if(is_null($this->_elements))
		{
			$elements = $this->_v('elements', null);
			if(!is_null($elements) && is_array($elements))
			{
				foreach ($elements as $element)
				{
					$e = \Zbase\Ui\Form\Element::factory($element);
					if($e instanceof \Zbase\Ui\Form\ElementInterface)
					{
						$this->_validationRules = array_merge($this->_validationRules, $e->getValidationRules());
						$this->_validationMessages = array_merge($this->_validationMessages, $e->getValidationMessages());
					}
					if($e instanceof FormInterface)
					{
						$e->setForm($this);
					}
					$this->_elements[] = $e;
				}
			}
			$this->_elements = $this->sortPosition($this->_elements);
		}
	}

	/**
	 * Process tabs
	 */
	protected function _tabs()
	{
		$tabs = $this->_v('tabs', null);
		if(!is_null($tabs) && is_array($tabs))
		{
			foreach ($tabs as $tabName => $tab)
			{
				$tab['group'] = $this->id() . 'tabs';
				if(!empty($tab['elements']))
				{
					foreach ($tab['elements'] as $elementName => $element)
					{
						if(empty($element['id']))
						{
							$element['id'] = $elementName;
							$element['name'] = $elementName;
						}
						$e = \Zbase\Ui\Form\Element::factory($element);
						if($e instanceof FormInterface)
						{
							$e->setForm($this);
						}
						if($e instanceof \Zbase\Ui\Form\ElementInterface)
						{
							$this->_validationRules = array_merge($this->_validationRules, $e->getValidationRules());
							$this->_validationMessages = array_merge($this->_validationMessages, $e->getValidationMessages());
						}
						$tab['contents'][] = $e;
					}
					unset($tab['elements']);
				}
				$tabs[$tabName] = $tab;
			}
		}
		$this->_tabs = zbase_ui_tabs($tabs);
	}

	/**
	 * Return the form elements
	 * @return \Zbase\Ui\Form\ElementInterface[]
	 */
	public function elements()
	{
		return $this->_elements;
	}

	/**
	 * Return an element by name
	 * @param string $name
	 * @return \Zbase\Ui\Form\ElementInterface
	 */
	public function element($name)
	{
		$this->prepare();
		if(!empty($this->_elements))
		{
			foreach ($this->_elements as $element)
			{
				if($name == $element->name())
				{
					return $element;
				}
			}
		}
		if(!empty($this->_tabs))
		{
			$tabs = $this->_tabs->tabs();
			if(!empty($tabs))
			{
				foreach ($tabs as $tab)
				{
					$elements = $tab->getContents();
					if(!empty($elements))
					{
						foreach ($elements as $element)
						{
							if($name == $element->name())
							{
								return $element;
							}
						}
					}
				}
			}
		}
		return null;
	}

	/**
	 *
	 * @return \Zbase\Ui\Tabs
	 */
	public function tabs()
	{
		return $this->_tabs;
	}

	// </editor-fold>

	/**
	 * Check if this widget is a child of another widget
	 * @return boolean
	 */
	public function isNested()
	{
		return $this->_v('nested', false);
	}

	/**
	 * Check if to add a submit button, default: true
	 * @return boolean
	 */
	public function submitButton()
	{
		if(!empty($this->isNested()))
		{
			return false;
		}
		return $this->_v('submit.button.enable', true);
	}

	/**
	 * The Submit Button Label
	 * @return string
	 */
	public function submitButtonLabel()
	{
		return $this->_v('submit.button.label', 'Submit');
	}

	/**
	 * Return the parent form
	 * @return \Zbase\Widgets\Type\FormInterface
	 */
	public function getForm()
	{
		return $this->_form;
	}

	/**
	 * Set the parent Form
	 * @param \Zbase\Widgets\Type\FormInterface $form
	 * @return \Zbase\Ui\Form\Element
	 */
	public function setForm(\Zbase\Widgets\Type\FormInterface $form)
	{
		$this->_form = $form;
		return $this;
	}

}
