<?php

namespace Zbase\Widgets\Type;

/**
 * Zbase-Widgets Widget-Type TreeView
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file TreeView.php
 * @project Zbase
 * @package Zbase/Widgets/Type
 *
 * https://github.com/jonmiles/bootstrap-treeview
 * http://jonmiles.github.io/bootstrap-treeview/#grandchild1
 *
 */
use Zbase\Widgets;

class TreeView extends Widgets\Widget implements Widgets\WidgetInterface, Widgets\ControllerInterface, FormInterface
{

	/**
	 * Widget Type
	 * @var string
	 */
	protected $_type = 'nested';

	/**
	 * The ViewFile string
	 * @var string
	 */
	protected $_viewFile = 'ui.treeview';

	/**
	 * The Nested Entity
	 * @var
	 */
	protected $_entity = null;

	/**
	 * 	Rows
	 * @var \Zbase\Entity\EntityInterface[]
	 */
	protected $_rows = null;

	/**
	 * Selected Rows
	 * @var \Zbase\Entity\EntityInterface[]
	 */
	protected $_selectedRows = null;

	/**
	 * Create Action Button
	 * @var \Zbase\Ui\Component
	 */
	protected $_actionCreateButton = null;

	/**
	 * Prepare
	 */
	protected function _pre()
	{
		parent::_pre();
		$this->_entity();
		$this->_rows();
	}

	public function setActions($actions)
	{
		$this->_actions = $actions;
		return $this;
	}

	/**
	 * Prepare the Actions
	 */
	protected function _actions()
	{
		if(!empty($this->_actions))
		{
			foreach ($this->_actions as $actionName => $action)
			{
				$enable = !empty($action['enable']) ? true : false;
				if(!empty($enable))
				{
					$label = !empty($action['label']) ? $action['label'] : ucfirst($actionName);
					$action['type'] = 'component.button';
					$action['id'] = $this->id() . 'Action' . $actionName;
					$action['size'] = 'extrasmall';
					$action['label'] = _zt($label);
					$action['tag'] = 'a';
					if(!empty($action['route']['name']))
					{
						if(!empty($action['route']['params']))
						{
							foreach ($action['route']['params'] as $paramName => $paramValue)
							{
								if(preg_match('/row::/', $paramValue))
								{
									$rowIndex = str_replace('row::', '', $paramValue);
									$action['route']['params'][$paramName] = zbase_data_get($row, $rowIndex);
								}
							}
						}
						$action['routeParams'] = $action['route']['params'];
						$action['route'] = $action['route']['name'];
					}
					$btn = \Zbase\Ui\Ui::factory($action);
					if($actionName == 'create')
					{
						if(!$this->_actionCreateButton instanceof \Zbase\Ui\UiInterface)
						{
							$this->_actionCreateButton = $btn;
						}
						continue;
					}
				}
			}
		}
	}

	/**
	 * Return Create Action Button
	 * @return Zbase\Ui\UiInterface
	 */
	public function getActionCreateButton()
	{
		$this->_actions();
		return $this->_actionCreateButton;
	}

	/**
	 * prepare rows
	 */
	protected function _rows()
	{
		if(is_null($this->_rows))
		{
			$root = $this->_entity->getRoot();
			if(!empty($root))
			{
				$this->_rows = $root->getDescendants()->toHierarchy();
				$this->selectedRows();
			}
			else
			{
				zbase_alert('warning', 'No Root or No Categories found.');
			}
		}
		return $this->_rows;
	}

	/**
	 * Set the Selected Rows
	 * @param \Zbase\Entity\EntityInterface[] $selectedRows
	 * @return \Zbase\Widgets\Type\TreeView
	 */
	public function setSelectedRows($selectedRows)
	{
		$this->_selectedRows = $selectedRows;
		return $this;
	}

	/**
	 * Return the Selected Rows
	 * @return \Zbase\Entity\EntityInterface[]
	 */
	public function selectedRows()
	{
		if(is_null($this->_selectedRows))
		{
			$this->_selectedRows = [];
			if($this->form() instanceof \Zbase\Widgets\Type\FormInterface)
			{
				$entity = $this->form()->entity();
				if($entity instanceof \Zbase\Entity\Laravel\Node\Nested)
				{
					// Get Parent CAtegory
					$selectedRow = $this->form()->entity();
				}
				if($entity instanceof \Zbase\Entity\Laravel\Node\Node)
				{
					//Get Categories of the node
					$selectedRow = $this->form()->entity()->categories()->get();
				}
				if($selectedRow instanceof \Zbase\Entity\Laravel\Node\Nested)
				{
					$p = $selectedRow->parent()->first();
					$this->_selectedRows[$p->category_id] = $p;
				}
			}
		}
		return $this->_selectedRows;
	}

	/**
	 * Return the Rows
	 */
	public function getRows()
	{
		return $this->_rows;
	}

	/**
	 * Controller Action
	 * 	This will be called validating the form
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

	}

	/**
	 * Return the Wrapper Attributes
	 * @return array
	 */
	public function wrapperAttributes()
	{
		$attr = parent::wrapperAttributes();
		$attr['class'][] = 'zbase-widget-wrapper';
		$attr['id'] = 'zbase-widget-wrapper-' . $this->id();
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

}
