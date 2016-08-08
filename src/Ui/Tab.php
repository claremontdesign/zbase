<?php

namespace Zbase\Ui;

/**
 * Zbase-Form Tab
 *
 * Tab Base Model
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Tab.php
 * @project Zbase
 * @package Zbase/Widgets
 *
 * $configuration.group The Group Id (Ui\Tabs::id) where this tab will be belong
 * $configuration.contents
 * $configuration.contents.UiInterface|ContentInterface
 */
use Zbase\Traits;
use Zbase\Interfaces;
use Zbase\Ui as UIs;

class Tab extends UIs\Ui implements UIs\UiInterface, Interfaces\IdInterface, \Zbase\Widgets\Type\FormInterface
{

	use Traits\Attribute,
	 Traits\Id,
	 Traits\Position,
	 Traits\Html;

	/**
	 * UI Type
	 * @var string
	 */
	protected $_type = 'tab';

	/**
	 * Element Label
	 * @var string
	 */
	protected $_label = null;

	/**
	 * The view File to use
	 * @var string
	 */
	protected $_viewFile = 'ui.tab';

	/**
	 * The Group Id
	 * @var Ui\Tabs
	 */
	protected $_group = null;

	/**
	 * Tab is Active
	 * @var boolean
	 */
	protected $_active = false;

	/**
	 * Form
	 * @var true
	 */
	protected $_form = null;


	/**
	 * REturn the Id
	 * @return type
	 */
	public function id()
	{
		return $this->_group->id() . $this->id;
	}

	public function tabId()
	{
		return $this->id;
	}

	/**
	 * Set label
	 * @param string $label
	 * @return \Zbase\Ui\Tab
	 */
	public function setLabel($label)
	{
		$this->_label = $label;
		return $this;
	}

	/**
	 * Return the label
	 * @return string
	 */
	public function label()
	{
		return zbase_data_get($this->_label, null, ucfirst($this->id()), $this);
	}

	/**
	 * If Tab is Active or Not
	 * @param boolean $flag
	 * @return \Zbase\Ui\Tab
	 */
	public function setActive($flag)
	{
		$this->_active = $flag;
		return $this;
	}

	/**
	 * Check if Tab is Active or NOt
	 * @return boolean
	 */
	public function isActive()
	{
		return $this->_active;
	}

	/**
	 *
	 * @param string|UIs\Tabs $group
	 * @return UIs\Tab
	 */
	public function setGroup($group)
	{
		$groupId = $group instanceof UIs\Tabs ? $group->id() : $group;
		$this->_group = zbase()->ui()->tabs()->get($groupId, true)->add($this);
		return $this;
	}

	/**
	 * Return the Group that this Tab belongs
	 * @return UIs\Tab
	 */
	public function group()
	{
		return $this->_group;
	}

	/**
	 * Prepare
	 * @return void
	 */
	protected function _pre()
	{
		$this->_contents = $this->sortPosition($this->_contents);
		parent::_pre();
	}

	/**
	 *
	 */
	public function renderContents()
	{
		$contents = parent::renderContents();
		if($this->getForm() instanceof \Zbase\Widgets\WidgetInterface)
		{
			$this->_form->setHtmlPrefix($this->getHtmlId());
			$str = $this->_form->startTag();
			$str .= $contents;
			if($this->_form->submitButton())
			{
				$str .= $this->_form->renderSubmitButton();
			}
			$str .= $this->_form->renderCSRFToken();
			$str .= '<input type="hidden" value="' . $this->id . '" name="tab" />';
			$str .= $this->_form->endTag();
			return $str;
		}
		return $contents;
	}

	/**
	 * Return the Wrapper Attributes
	 * @return array
	 */
	public function wrapperAttributes()
	{
		$attr = parent::wrapperAttributes();
		$attr['class'][] = 'zbase-ui-' . $this->_type;
		$attr['class'][] = 'tab-pane';
		$attr['class'][] = 'fade';
		if($this->isActive())
		{
			$attr['class'][] = 'active';
			$attr['class'][] = 'in';
		}
		$attr['id'] = $this->getHtmlId();
		return $attr;
	}

	/**
	 * Set/Get the parent Form
	 * @param \Zbase\Widgets\Type\FormInterface $form
	 * @return \Zbase\Ui\Form\Element
	 */
	public function setForm(\Zbase\Widgets\Type\FormInterface $form = null)
	{
		if(!is_null($form))
		{
			$this->_form = $form;
			return $this;
		}
		return $this->_form;
	}

	/**
	 * Set/Get the parent Form
	 * @param \Zbase\Widgets\Type\FormInterface $form
	 * @return \Zbase\Ui\Form\Element
	 */
	public function form(\Zbase\Widgets\Type\FormInterface $form = null)
	{
		return $this->setForm($form);
	}

	public function getForm()
	{
		return $this->_form;
	}

}
