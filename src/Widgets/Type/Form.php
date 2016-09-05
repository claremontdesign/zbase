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
	 * Form is deleting
	 * @return type
	 */
	public function isDeleting()
	{
		return $this->_action == 'delete';
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
	 * is to save values to session
	 *
	 * @return boolean
	 */
	public function isValueToSession()
	{
		return $this->_v('values.session.enable', $this->_v('values.session', false));
	}

	/**
	 * Has Default Values
	 *
	 * @return boolean
	 */
	public function hasDefaultValues()
	{
		return $this->_v('values.default.enable', false);
	}

	/**
	 * Default Values
	 *
	 * @return boolean
	 */
	public function defaultValues()
	{
		return $this->_v('values.default.array', []);
	}

	/**
	 * Controller Action
	 * 	This will be called validating the form
	 * @param string $action
	 */
	public function controller($action)
	{
		$this->setAction($action);
		if($this->isDeleting())
		{
			$deleteViewFile = $this->_v('event.' . zbase_section() . '.delete.pre.view.file', false);
			if(!empty($deleteViewFile))
			{
				$this->_viewParams['viewFile'] = $deleteViewFile;
			}
		}
		if($this->hasEntity())
		{
			if($this->entity() instanceof \Zbase\Widgets\EntityInterface)
			{
				$page = [];
				if($this->entity() instanceof \Zbase\Post\PostInterface)
				{
					$this->entity()->postPageProperties($this);
				}
				else
				{
					if(method_exists($this->entity(), 'pageProperty'))
					{
						$this->entity()->pageProperty($this);
					}
					else
					{
						$page['title'] = $this->entity()->title();
						$page['headTitle'] = $this->entity()->title();
						zbase_view_page_details(['page' => $page]);
					}
				}

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

				if($this->entity() instanceof \Zbase\Post\PostInterface)
				{
					$actionMessages = $this->entity()->postMessages();
				}
				else
				{
					$actionMessages = $this->entity()->getActionMessages($action);
				}

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
				if(zbase_request_method() == 'post')
				{
					if(!empty($this->isValueToSession()))
					{
						$sessionPrefix = $this->_v('values.session.prefix', null);
						foreach ($inputs as $k => $v)
						{
							if($k == '_token')
							{
								continue;
							}
							zbase_session_set($sessionPrefix . $k, $v);
						}
						return $this->_postEvent($action);
					}
				}
				if(!empty($ret))
				{
					if(zbase_request_method() == 'post')
					{
						if(is_bool($ret) && zbase_request_is_ajax())
						{
							zbase()->json()->addVariable($action . '_sucess', 1);
						}
						if($this->isCreating())
						{
							if($this->entity() instanceof \Zbase\Post\PostInterface)
							{
								zbase_session_flash($this->entity()->postTableName() . 'new', $this->entity()->postId());
							}
							else
							{
								zbase_session_flash($this->entity()->entityName() . 'new', $this->entity()->id());
							}
						}
						return $this->_postEvent($action);
					}
					if($action == 'restore' || $action == 'ddelete')
					{
						return $this->_postEvent($action);
					}
				}
			}
			else
			{
				return zbase_abort(404);
			}
		}
		else
		{
			if($this->hasDefaultValues())
			{
				$this->setValues($this->defaultValues());
			}
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
		$isAjax = zbase_request_is_ajax();
		$requestMethod = strtolower(zbase_request_method());
		if($isAjax)
		{
			if($requestMethod == 'post')
			{
				$e = $this->_v('event.' . zbase_section() . '.' . $action . '.post-json.post', $this->_v('event.' . $action . '.post-json'));
			}
			else
			{
				$e = $this->_v('event.' . zbase_section() . '.' . $action . '.post-json', $this->_v('event.' . zbase_section() . '.' . $action . '.post'));
			}
		}
		else
		{
			if($requestMethod == 'post')
			{
				$e = $this->_v('event.' . zbase_section() . '.' . $action . '.post.post', $this->_v('event.' . $action . '.post.post', null));
			}
			else
			{
				$e = $this->_v('event.' . zbase_section() . '.' . $action . '.post', $this->_v('event.' . $action . '.post', null));
			}
		}
		if(is_null($e))
		{
			if(zbase_is_back())
			{
				if($this->isCreating())
				{
					$action = 'update';
				}
				$byAlphaId = $this->_v('entity.repo.byAlphaId.route', false);
				if($this->entityIsPostInterface($this->entity()))
				{
					if(!empty($byAlphaId))
					{
						$params = ['action' => $action, 'id' => $this->entity()->postAlphaId()];
					}
					else
					{
						$params = ['action' => $action, 'id' => $this->entity()->postId()];
					}
				}
				else
				{
					if(!empty($byAlphaId))
					{
						$params = ['action' => $action, 'id' => $this->entity()->alphaId()];
					}
					else
					{
						$params = ['action' => $action, 'id' => $this->entity()->id()];
					}
				}
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
			if(!empty($e['data']))
			{
				if($isAjax)
				{
					zbase()->json()->addVariables($e['data']);
				}
			}
			if(!empty($e['route']))
			{
				$params = zbase_route_inputs();
				if(!empty($e['route']['params']))
				{
					$params = array_merge($params, $e['route']['params']);
				}
				if(zbase_is_back())
				{
					$byAlphaId = $this->_v('entity.repo.byAlphaId.route', false);
					if(!empty($byAlphaId))
					{
						$params['id'] = $this->entity()->alphaId();
					}
					else
					{
						$params['id'] = $this->entity()->id();
					}
				}
				if($action == 'ddelete')
				{
					if(isset($params['id']) && isset($params['action']))
					{
						unset($params['id']);
						unset($params['action']);
					}
				}
				$e['route']['params'] = $params;
				$url = zbase_url_from_config($e);
			}
			$toUrl = zbase_value_get($e, 'url', false);
			if(!empty($toUrl))
			{
				$url = $toUrl;
			}
		}
		$enableRedirect = $this->_v('event.' . zbase_section() . '.' . $action . '.post.redirect.enable', $this->_v('event.' . $action . '.post.redirect.enable', true));
		if(!empty($url) && !empty($enableRedirect))
		{
			return zbase_redirect()->to($url);
		}
		return true;
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
	public function validateWidget($action)
	{
		if($this->_urlHasRequest)
		{
			if(empty($this->_entity))
			{
				return zbase_abort(404);
			}
			if($this->isAdmin() && $this->_entity instanceof \Zbase\Entity\Laravel\Node\Nested)
			{
				$children = $this->_entity->getImmediateDescendants();
				if($children->count())
				{
					return zbase_abort(404);
				}
			}
		}
		$this->setAction($action);
		$this->prepare();
		if(zbase_request_method() == 'post')
		{
			$currentTab = zbase_request_input('tab', false);
			if(!empty($currentTab))
			{
				zbase_session_flash('sessiontab', $currentTab);
			}
			if($this->isDeleting())
			{
				return;
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
		if(zbase_request_is_post())
		{
			$currentTab = zbase_request_input('tab', false);
			if(!empty($this->_validationRules['_tab' . $currentTab]))
			{
				return $this->_validationRules['_tab' . $currentTab];
			}
		}
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
		if(is_null($e))
		{
			return;
		}
		$e->setAttribute('widgetEntity', $this->entity());
		if($e instanceof \Zbase\Widgets\Type\FormInterface)
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
			if($this->isValueToSession())
			{
				$e->setValue(zbase_session_get($e->id()));
			}
		}
		if($e instanceof \Zbase\Widgets\EntityInterface)
		{
			$e->entity($this->_entity);
		}
		if($e instanceof \Zbase\Widgets\Type\FormInterface)
		{
			$widgetElements = $e->elements();
			if(!empty($widgetElements))
			{
				foreach ($widgetElements as $widgetElement)
				{
					if(!empty($element['widget']) && !empty($element['prefix']))
					{
						$widgetElement->setIdPrefix($element['prefix']);
						if($this->isValueToSession())
						{
							$widgetElement->setValue(zbase_session_get($widgetElement->id()));
						}
					}
					if(!empty($tabName))
					{
						if($widgetElement instanceof \Zbase\Ui\Form\ElementInterface)
						{
							$widgetElement->setTab($tabName);
						}
						if($widgetElement instanceof \Zbase\Interfaces\ValidationInterface)
						{
							if($widgetElement->hasValidations())
							{
								//$currentTab = zbase_request_input('tab', false);
								//if(zbase_request_method() == 'post' && !empty($currentTab))
								//{
								$widgetValidationRules = $widgetElement->getValidationRules($this->getAction());
								if(!is_array($widgetValidationRules))
								{
									$widgetValidationRules = [$widgetElement->getId() => $widgetValidationRules];
								}
								$this->_validationRules = array_replace_recursive($this->_validationRules, $widgetValidationRules);
								$this->_validationMessages = array_replace_recursive($this->_validationMessages, $widgetElement->getValidationMessages($this->getAction()));
								//}
							}
						}
					}
				}
			}
			else
			{
				if($e instanceof \Zbase\Ui\Form\ElementInterface)
				{
					if($e instanceof \Zbase\Interfaces\ValidationInterface)
					{
						if($e->hasValidations())
						{
							$widgetValidationRules = $e->getValidationRules($this->getAction());
							if(!is_array($widgetValidationRules))
							{
								$widgetValidationRules = [$e->getId() => $widgetValidationRules];
							}
							$this->_validationRules = array_replace_recursive($this->_validationRules, $widgetValidationRules);
							$this->_validationMessages = array_replace_recursive($this->_validationMessages, $e->getValidationMessages($this->getAction()));
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
						if(!isset($this->_validationRules['_tab' . $tabName]))
						{
							$this->_validationRules['_tab' . $tabName] = [];
						}
						$this->_validationRules['_tab' . $tabName][$e->name()] = $e->getValidationRules($this->getAction());
						$this->_validationMessages = array_replace_recursive($this->_validationMessages, $e->getValidationMessages($this->getAction()));
					}
				}
//				if(zbase_request_method() == 'post' && empty($formTag) && !empty($currentTab))
//				{
//					if($tabName == $currentTab)
//					{
//						$this->_validationRules[$e->name()] = $e->getValidationRules($this->getAction());
//						$this->_validationMessages = array_replace_recursive($this->_validationMessages, $e->getValidationMessages($this->getAction()));
//					}
//				}
//				else
//				{
//					$this->_validationRules[$e->name()] = $e->getValidationRules($this->getAction());
//					$this->_validationMessages = array_replace_recursive($this->_validationMessages, $e->getValidationMessages($this->getAction()));
//				}
			}
//			if(zbase_request_method() == 'post')
//			{
//				var_dump(zbase_request_inputs());
//				var_dump($currentTab . '-' . $tabName);
//			}
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
					$e = $this->_createElement($element);
					if(is_null($e))
					{
						continue;
					}
					$this->_elements[] = $e;
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
		$this->prepare();
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
			foreach ($this->_elements as $i => $element)
			{
				if($element instanceof Form)
				{
					$formE = $element->element($name);
					if($formE instanceof \Zbase\Ui\Form\ElementInterface)
					{
						return $formE;
					}
				}
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
		$tabSet = $this->_v('tabs', null);
		/**
		 * If $formTag is TRUE, will create a form on each tabs
		 */
		$formTag = $this->_v('form_tab', true);
		if(!is_null($tabSet) && is_array($tabSet))
		{
			foreach ($tabSet as $tabName => $tab)
			{
				if(!is_array($tab))
				{
					continue;
				}
				$tab['widgetEntity'] = $this->entity();
				$tabObject = zbase_ui($tab);
				$tabObject->setGroup($this->id() . 'tabs');
				// $tabObject->setAttribute('widgetEntity', $this->entity());
				$enabled = $tabObject->enabled();
				if($enabled)
				{
					if(!empty($tab['elements']))
					{
						$hasFileElement = false;
						foreach ($tab['elements'] as $elementName => $element)
						{
							if(empty($element['id']))
							{
								$element['id'] = $elementName;
								$element['name'] = $elementName;
							}
							if(!empty($element['type']) && $element['type'] == 'file')
							{
								$hasFileElement = true;
							}
							// var_dump('_tabs: ' . $tabName);
							$tabObject->addContent($this->_createElement($element, $tabName));
						}
						unset($tab['elements']);
					}
					if(empty($formTag))
					{
						$tabObject->setForm(clone $this);
						/**
						 * Form Configuration in a tab
						 */
						if(!empty($tab['formConfiguration']))
						{
							$tabObject->form()->setAttributes($tab['formConfiguration']);
						}
						$this->setFormTag(false);
					}
					$tabs[$tabName] = $tabObject;
				}
			}
//			foreach ($tabs as $tabName => $tab)
//			{
//				$tab = zbase_ui($tab);
//				if(!is_array($tab))
//				{
//					continue;
//				}
//				$tab['group'] = $this->id() . 'tabs';
//				if(!empty($tab['elements']))
//				{
//					$hasFileElement = false;
//					foreach ($tab['elements'] as $elementName => $element)
//					{
//						if(empty($element['id']))
//						{
//							$element['id'] = $elementName;
//							$element['name'] = $elementName;
//						}
//						if(!empty($element['type']) && $element['type'] == 'file')
//						{
//							$hasFileElement = true;
//						}
//						$tab['contents'][] = $this->_createElement($element, $tabName);
//					}
//					unset($tab['elements']);
//				}
//				if(empty($formTag))
//				{
//					$tab['form'] = clone $this;
//					/**
//					 * Form Configuration in a tab
//					 */
//					if(!empty($tab['formConfiguration']))
//					{
//						$tab['form']->setAttributes($tab['formConfiguration']);
//					}
//					$this->setFormTag(false);
//				}
//				$tab['widgetEntity'] = $this->entity();
//				$tabs[$tabName] = $tab;
//			}
		}
		if(!empty($tabs))
		{
			$this->_tabs = zbase_ui_tabs($tabs);
		}
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
		$cancelId = 'cancelButton' . $this->getHtmlId();
		if($this->hasEntity())
		{
			if($this->_entity instanceof \Zbase\Post\PostInterface)
			{
				$postAction = $this->_v('post.action', $this->_action);
				$cancelId = 'formCancelButton' . ucfirst($postAction) . $this->entity()->postHtmlId();
			}
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
		}
		$attributes = $this->_v('submit.button.' . $this->_action . '.html.attributes', $this->_v('submit.button.html.attributes', []));
		$attributes['class'][] = 'btn btn-success';
		$cancel = $this->_v('submit.button.' . $this->_action . '.cancel', $this->_v('submit.button.cancel', false));
//		$cancelOnclick = $this->_v('submit.button.' . $this->_action . '.cancel.onclick', true);
		$cancelButton = null;
		if(!empty($cancel))
		{
			if(is_array($cancel))
			{
				if(!empty($cancel['route']))
				{
					$cancelUrl = zbase_url_from_config($cancel);
				}
			}
			$cancelAttributes['class'][] = 'btn';
			$cancelAttributes = $this->_v('submit.button.' . $this->_action . '.cancel.html.attributes.input', $this->_v('submit.button.cancel.html.attributes.input', false));
			$cancelLabel = $this->_v('submit.button.' . $this->_action . '.cancel.label', $this->_v('submit.button.cancel.label', 'Cancel'));
			if(is_array($cancelAttributes['class']))
			{
				if(!in_array('btn', $cancelAttributes['class']))
				{
					$cancelAttributes['class'][] = 'btn btn-danger';
				}
			}
			else
			{
				$cancelAttributes['class'][] = 'btn btn-danger';
			}
			if(!empty($cancelUrl))
			{
				$cancelButton = '<a id="' . $cancelId . '" href="' . $cancelUrl . '" ' . $this->renderHtmlAttributes($cancelAttributes) . '>' . $cancelLabel . '</a>';
			}
			else
			{
				$cancelButton = '<button id="' . $cancelId . '" type="button" ' . $this->renderHtmlAttributes($cancelAttributes) . '>' . $cancelLabel . '</button>';
			}
		}
		if(zbase_is_angular_template())
		{
			if(!empty($this->_validationRules))
			{
				return $cancelButton . '&nbsp;<button ng-disabled="' . $this->getHtmlId() . '.$invalid" class="btn btn-success" ' . $this->renderHtmlAttributes($attributes) . '>' . $this->submitButtonLabel() . '</button>';
			}
			return $cancelButton . '&nbsp;<button id="submitButton' . $this->getHtmlId() . '" class="btn btn-success" ' . $this->renderHtmlAttributes($attributes) . '>' . $this->submitButtonLabel() . '</button>';
		}
		return $cancelButton . '&nbsp;<button id="submitButton' . $this->getHtmlId() . '" type="submit" ' . $this->renderHtmlAttributes($attributes) . '>' . $this->submitButtonLabel() . '</button>';
	}

	/**
	 * The Submit Button Label
	 * @return string
	 */
	public function submitButtonLabel()
	{
		// return $this->_v('submit.button.' . $this->_action . '.label', $this->_v('submit.button.label', 'Submit'));
		return $this->_v('submit.button.label', 'Submit');
	}

	/**
	 * Return the Wrapper Attributes
	 * @return array
	 */
	public function wrapperAttributes()
	{
		$attr = parent::wrapperAttributes();
		if(!empty($this->_entity))
		{
			if(($this->_action == 'delete' && strtolower(zbase_request_method()) != 'post') || ($this->isNode() && $this->_entity->hasSoftDelete() && empty($this->_entityIsDefault) && $this->_entity->trashed()))
			{
				$attr['class'][] = 'action-delete';
				$attr['style'][] = 'border:2px solid red; padding:20px;';
			}
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
		$attributes = $this->_v('form.startTag.' . $this->_action . '.html.attributes', $this->_v('form.startTag.html.attributes', []));
		if(empty($attributes['name']))
		{
			$attributes['name'] = $this->getHtmlId();
		}
		if(zbase_is_angular_template())
		{
			/**
			 * false is disabled
			 */
			if(!isset($attributes['ng-submit']))
			{
				$attributes['ng-submit'] = 'submit' . $this->getHtmlId() . '()';
			}
			return '<form role="form" ' . $this->renderHtmlAttributes($attributes) . '>';
		}
		return '<form action="' . $this->getFormAction() . '" method="POST" enctype="multipart/form-data" ' . $this->renderHtmlAttributes($attributes) . '>';
	}

	/**
	 * The Form Action
	 *
	 * @return string
	 */
	public function getFormAction()
	{
		return $this->_v('form.startTag.' . $this->_action . '.action', $this->_v('form.startTag.action', null));
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
