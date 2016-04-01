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
 * form_tag = boolean
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
	 * Form Mode
	 * input|display
	 * @var string
	 */
	protected $_mode = 'input';

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
	 * The Current entity
	 * @var Zbase\Interfaces\EntityInterface
	 */
	protected $_entity = null;

	/**
	 * Validation rules
	 * @var array
	 */
	protected $_validationRules = [];

	/**
	 * Validation messages
	 * @var array
	 */
	protected $_validationMessages = [];

	/**
	 * form has error
	 * @var boolean
	 */
	protected $_hasError = false;

	/**
	 * Form Tag
	 * @var true
	 */
	protected $_formTag = true;

	/**
	 * Set the Mode
	 * @param type $mode
	 * @return \Zbase\Widgets\Type\Form
	 */
	public function setMode($mode)
	{
		$this->_mode = $mode;
		return $this;
	}

	// <editor-fold defaultstate="collapsed" desc="CONTROLLERInterface">

	/**
	 * Check if form is creating
	 * @return boolean
	 */
	public function isCreating()
	{
		return $this->_action == 'create' || $this->_action == 'post';
	}

	/**
	 * set that form has error
	 */
	public function setHasError()
	{
		$this->_hasError = true;
		zbase_session_flash('errorForm' . $this->id(), true);
	}

	/**
	 * Check if form has error
	 * @return boolean
	 */
	public function hasError()
	{
		return zbase_session_has('errorForm' . $this->id());
	}

	/**
	 * Controller Action
	 * 	This will be called validating the form
	 * @param string $action
	 */
	public function controller($action)
	{
		$this->setAction($action);
		if($this->entity() instanceof \Zbase\Widgets\EntityInterface)
		{
			if($this->entity()->hasSoftDelete())
			{
				if($this->entity()->trashed())
				{
					$this->_mode = 'display';
				}
				else
				{
					if($action == 'restore' || $action == 'ddelete')
					{
						return zbase_redirect()->to(zbase_url_previous());
					}
				}
			}
			$inputs = zbase_route_inputs();
			if(zbase_request_method() == 'post')
			{
				$inputs = zbase_request_inputs();
			}
			$ret = $this->entity()->widgetController(zbase_request_method(), $action, $inputs, $this);
			$actionMessages = $this->entity()->getActionMessages($action);
			if(!empty($actionMessages))
			{
				foreach ($actionMessages as $alertType => $alertMessages)
				{
					if(is_array($alertMessages))
					{
						foreach ($alertMessages as $alertMessage)
						{
							zbase_alert($alertType, $alertMessage);
						}
					}
				}
			}
			if(!empty($ret))
			{
				if(zbase_request_method() == 'post')
				{
					if($this->isCreating())
					{
						zbase_session_flash($this->entity()->entityName() . 'new', $this->entity()->id());
					}
					return $this->_postEvent($action);
				}
			}
			if($this->isCreating())
			{
				if(zbase_is_dev())
				{
					if(method_exists($this->entity(), 'fakeValue'))
					{
						$entity = $this->_entity;
						$this->setValues($entity::fakeValue());
					}
				}
			}
		}
		else
		{
			return zbase_abort(404);
		}
		return false;
	}

	/**
	 * Event after Action
	 * @param string $action
	 * @param string $url The Default URL to redirect
	 */
	protected function _postEvent($action)
	{
		if($this->isPublic() && $this->isNode() && $this->isCreating())
		{
			return zbase_redirect()->to($this->entity()->alphaUrl());
		}
		$e = $this->_v('event.' . zbase_section() . '.' . $action . '.post', null);
		if(is_null($e))
		{
			if(zbase_is_back())
			{
				if($this->isCreating())
				{
					$action = 'update';
				}
				$params = ['action' => $action, 'id' => $this->entity()->id()];
			}
			else
			{
				$params = ['action' => $action, 'id' => $this->entity()->alphaId()];
			}
			if($action == 'delete')
			{
				$params = [];
			}
			$url = $this->getModule()->url(zbase_section(), $params);
			if($action == 'restore' || $action == 'ddelete')
			{
				$url = zbase_url_previous();
			}
		}
		if(!empty($e))
		{
			if(!empty($e['route']))
			{
				$url = zbase_url_from_config($e);
			}
		}
		return zbase_redirect()->to($url);
	}

	/**
	 * Check if was posted
	 * @return boolean
	 */
	public function wasPosted()
	{
		return !empty(zbase_session_get('posted', false));
	}

	/**
	 * Validate widget
	 */
	public function validateWidget()
	{
		$this->prepare();
		if(zbase_request_method() == 'post')
		{
			$currentTab = zbase_request_input('tab', false);
			if(!empty($currentTab))
			{
				zbase_session_flash('sessiontab', $currentTab);
			}
			$validationRules = $this->getValidationRules();
			if(!empty($validationRules))
			{
				$v = \Validator::make(zbase_request_inputs(), $validationRules, $this->getValidationMessages());
				if($v->fails())
				{
					zbase_session_flash('posted', true);
					$this->setHasError($v->errors()->getMessages());
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
		parent::_pre();
		$this->entity();
		$this->_tabs();
		$this->_elements();
	}

	// <editor-fold defaultstate="collapsed" desc="Element and Tabs">

	/**
	 * Create an element
	 * @param array $element Element configuration
	 * @param string $tabName The tabName the element belongs
	 * @return Zbase\Ui\Form\ElementInterface
	 */
	protected function _createElement($element, $tabName = null)
	{
		$e = \Zbase\Ui\Form\Element::factory($element);
		if($e instanceof FormInterface)
		{
			$e->form($this);
			if(empty($this->_entityIsDefault))
			{
				$e->entity($this->_entity);
			}
		}
		if($e instanceof \Zbase\Ui\Form\ElementInterface)
		{
			if(!empty($tabName))
			{
				$e->setTab($tabName);
			}
		}
		if($e instanceof \Zbase\Widgets\EntityInterface)
		{
			$e->entity($this->_entity);
		}
		if($e instanceof \Zbase\Widgets\Type\FormInterface)
		{
			if(!empty($tabName))
			{
				$widgetElements = $e->elements();
				if(!empty($widgetElements))
				{
					foreach ($widgetElements as $widgetElement)
					{
						if($widgetElement instanceof \Zbase\Ui\Form\ElementInterface)
						{
							if(!empty($tabName))
							{
								$widgetElement->setTab($tabName);
							}
						}
						if($widgetElement instanceof \Zbase\Interfaces\ValidationInterface)
						{
							if($widgetElement->hasValidations())
							{
								$this->_validationRules = array_replace_recursive($this->_validationRules, $widgetElement->getValidationRules());
								$this->_validationMessages = array_replace_recursive($this->_validationMessages, $widgetElement->getValidationMessages());
							}
						}
					}
				}
			}
		}
		$currentTab = zbase_request_input('tab', false);
		if($e instanceof \Zbase\Interfaces\ValidationInterface)
		{
			if($e->hasValidations())
			{
				$formTag = $this->_v('form_tab', true);
				if(zbase_request_method() == 'post' && empty($formTag) && !empty($currentTab))
				{
					if($tabName == $currentTab)
					{
						$this->_validationRules[$e->name()] = $e->getValidationRules();
						$this->_validationMessages = array_replace_recursive($this->_validationMessages, $e->getValidationMessages());
					}
				}
				else
				{
					$this->_validationRules[$e->name()] = $e->getValidationRules();
					$this->_validationMessages = array_replace_recursive($this->_validationMessages, $e->getValidationMessages());
				}
			}
		}
		return $e;
	}

	/**
	 * Set the Form Values
	 * @param array $values
	 */
	public function setValues($values)
	{
		if(!empty($values))
		{
			foreach ($values as $key => $val)
			{
				$e = $this->element($key);
				if($e instanceof \Zbase\Ui\Form\ElementInterface)
				{
					$e->setValue($val);
				}
			}
		}
	}

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
					$this->_elements[] = $this->_createElement($element);
				}
			}
			$this->_elements = $this->sortPosition($this->_elements);
		}
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
	 * Process tabs
	 */
	protected function _tabs()
	{
		$tabs = $this->_v('tabs', null);
		/**
		 * If $formTag is TRUE, will create a form on each tabs
		 */
		$formTag = $this->_v('form_tab', true);
		if(!is_null($tabs) && is_array($tabs))
		{
			foreach ($tabs as $tabName => $tab)
			{
				if(!is_array($tab))
				{
					continue;
				}
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
						$tab['contents'][] = $this->_createElement($element, $tabName);
					}
					unset($tab['elements']);
				}
				if(empty($formTag))
				{
					$tab['form'] = $this;
					$this->setFormTag(false);
				}
				$tabs[$tabName] = $tab;
			}
		}
		$this->_tabs = zbase_ui_tabs($tabs);
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
	 * Render CSRF Token
	 * @return string
	 */
	public function renderCSRFToken()
	{
		return zbase_csrf_token_field($this->id());
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
	 * REnder submit button
	 * @return boolean
	 */
	public function renderSubmitButton()
	{
		if(empty($this->_entityIsDefault) && $this->_entity->hasSoftDelete() && $this->_entity->trashed())
		{
			if($this->_action == 'restore')
			{
				return '<button onclick="window.history.back();" type="button" class="btn">Cancel</button>&nbsp;<button type="submit" class="btn btn-info">Restore</button>';
			}
			if($this->_action == 'ddelete')
			{
				return '<button onclick="window.history.back();" type="button" class="btn">Not Now</button>&nbsp;<button type="submit" class="btn btn-danger">Delete Forever</button>';
			}
			return '';
		}
		$attributes = $this->_v('submit.button.' . $this->_action . '.html.attributes', $this->_v('submit.button.html.attributes', []));
		$attributes['class'][] = 'btn btn-default';
		$cancel = $this->_v('submit.button.' . $this->_action . '.cancel', $this->_v('submit.button.cancel', false));
		$cancelButton = null;
		if(!empty($cancel))
		{
			$cancelButton = '<button onclick="window.history.back();" type="button" class="btn">Cancel</button>';
		}
		return $cancelButton . '&nbsp;<button type="submit" ' . $this->renderHtmlAttributes($attributes) . '>' . $this->submitButtonLabel() . '</button>';
	}

	/**
	 * The Submit Button Label
	 * @return string
	 */
	public function submitButtonLabel()
	{
		return $this->_v('submit.button.' . $this->_action . '.label', $this->_v('submit.button.label', 'Submit'));
	}

	/**
	 * Return the Wrapper Attributes
	 * @return array
	 */
	public function wrapperAttributes()
	{
		$attr = parent::wrapperAttributes();
		if(($this->_action == 'delete' && strtolower(zbase_request_method()) != 'post') || ($this->isNode() && $this->_entity->hasSoftDelete() && empty($this->_entityIsDefault) && $this->_entity->trashed()))
		{
			$attr['class'][] = 'action-delete';
			$attr['style'][] = 'border:2px solid red; padding:20px;';
		}
		return $attr;
	}

	/**
	 * Set/Get the parent Form
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
	 * Form Start Tag
	 * @return string
	 */
	public function startTag()
	{
		return '<form action="" method="POST" enctype="multipart/form-data">';
	}

	/**
	 * Form end tag
	 * @return string
	 */
	public function endTag()
	{
		return '</form>';
	}

	/**
	 * Set form tag
	 * @param boolean $flag
	 */
	public function setFormTag($flag)
	{
		$this->_formTag = $flag;
	}

	/**
	 * If this widget has a form Tag
	 * @return boolean
	 */
	public function hasFormTag()
	{
		return $this->_formTag;
	}

}
