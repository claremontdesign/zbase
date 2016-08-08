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
use Zbase\Interfaces;
use Zbase\Traits;

class TreeView extends Widgets\Widget implements Widgets\WidgetInterface, Widgets\ControllerInterface, FormInterface, Interfaces\ValidationInterface
{

	use Traits\Validations;

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
	 * Hierarchy
	 * @var array
	 */
	protected $_tree = null;

	/**
	 * Create Action Button
	 * @var \Zbase\Ui\Component
	 */
	protected $_actionCreateButton = null;

	/**
	 * Has Actions Flag?
	 * @var boolean
	 */
	protected $_hasActions = false;
	protected $_actions = [];
	protected $_actionButtons = [];

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
	 * Render the actions
	 */
	public function renderRowActions($row, $children = null)
	{
		if(!$row instanceof \Zbase\Interfaces\EntityInterface)
		{
			return;
		}
		$rowTrashed = false;
		if($this->_entity->hasSoftDelete())
		{
			$rowTrashed = $row->trashed();
		}
		if(!empty($this->_actions))
		{
			$this->_actionButtons = [];
			foreach ($this->_actions as $actionName => $action)
			{
				if(!empty($children->count()) && $actionName == 'delete')
				{
					continue;
				}
				if($actionName == 'delete' || $actionName == 'update')
				{
					if($rowTrashed)
					{
						continue;
					}
				}
				if($actionName == 'restore' || $actionName == 'ddelete')
				{
					if(empty($rowTrashed))
					{
						continue;
					}
				}
				$label = !empty($action['label']) ? $action['label'] : ucfirst($actionName);
				if(strtolower($label) == 'ddelete')
				{
					$label = _zt('Forever Delete');
				}
				$action['type'] = 'component.button';
				$action['id'] = $this->id() . 'Action' . $actionName;
				$action['size'] = 'extrasmall';
				$action['label'] = _zt($label);
				$action['tag'] = 'a';
				$action['html']['attributes']['wrapper']['onclick'] = true;
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
				if($actionName == 'create')
				{
					$action['color'] = 'blue';
				}
				if($actionName == 'update')
				{
					$action['color'] = 'green';
				}
				if($actionName == 'delete')
				{
					$action['color'] = 'red';
				}
				if($actionName == 'restore')
				{
					$action['color'] = 'warning';
				}
				if($actionName == 'ddelete')
				{
					$action['color'] = 'red';
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
				$this->_actionButtons[] = $btn;
			}
			return implode("\n", $this->_actionButtons);
		}
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
									if(!empty($row))
									{
										$action['route']['params'][$paramName] = zbase_data_get($row, $rowIndex);
									}
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
				$this->_rows = $root->getImmediateDescendants();
			}
			else
			{
				zbase_alert('warning', 'No Root or No Categories found.');
			}
		}
		return $this->_rows;
	}

	/**
	 * Return the TREE
	 * @param type $options
	 */
	public function getTree($options = [])
	{
		if(empty($this->prepared))
		{
			$rows = $this->getRows();
			if(!empty($rows))
			{
				foreach ($rows as $row)
				{
					$this->_tree[] = $this->_treeRow($row, $options);
				}
			}
		}
		return $this->_tree;
	}

	/**
	 * Check if TreeView act as a Datatable
	 * @return boolean
	 */
	public function isDatatable()
	{
		return $this->_v('treeOptions.datatable', false);
	}

	/**
	 * Tree Row
	 * http://jonmiles.github.io/bootstrap-treeview/#grandchild1
	 * https://github.com/jonmiles/bootstrap-treeview
	 *
	 * @param type $row
	 * @return array
	 */
	protected function _treeRow($row, $options = [])
	{
		$newRow = [];
		$jsTree = !empty($options['jstree']) ? true : false;
		$children = $row->getImmediateDescendants();
		if($jsTree)
		{
			$newRow['text'] = $row->title();
		}
		else
		{
			$texts = [];
			if($this->isAdmin())
			{
				$texts[] = '<span>' . $this->renderRowActions($row, $children) . '</span>';
			}
			$texts[] = $row->title();
			$newRow['text'] = implode(' ', $texts);
		}
		$newRow['id'] = $row->alphaId();
		$newRow['state']['expanded'] = false;
		$selected = $this->selectedRows();
		if(!empty($children))
		{
			$newRow['id'] = $row->alphaId();
		}
		else
		{

		}
		if($selected instanceof \Illuminate\Database\Eloquent\Collection)
		{
			if($jsTree)
			{

			}
			else
			{
				$newRow['state']['expanded'] = false;
			}
			foreach ($selected as $sel)
			{
				$parents = $sel->ancestors()->lists('category_id')->toArray();
				if($sel->id() == $row->id())
				{
					if($jsTree)
					{
						$newRow['state']['selected'] = true;
					}
					else
					{
						$newRow['state']['selected'] = true;
					}
				}
				if(in_array($row->id(), $parents))
				{
					if($jsTree)
					{
						$newRow['state']['opened'] = true;
					}
					else
					{
						$newRow['state']['expanded'] = true;
					}
				}
			}
		}
		else
		{
			if(!empty($selected))
			{
				foreach ($selected as $sel)
				{
					if($sel == $row->id())
					{
						if($jsTree)
						{
							$newRow['state']['selected'] = true;
							$newRow['state']['opened'] = true;
						}
						else
						{
							$newRow['state']['selected'] = true;
							$newRow['state']['expanded'] = true;
						}
					}
				}
			}
		}
		if(!empty($children))
		{
			foreach ($children as $child)
			{
				if($jsTree)
				{
					$newRow['children'][] = $this->_treeRow($child, $options);
				}
				else
				{
					$newRow['nodes'][] = $this->_treeRow($child, $options);
				}
			}
		}
		return $newRow;
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
				if($this->form()->wasPosted())
				{
					$this->_selectedRows = zbase_form_old('category');
				}
				else
				{
					$entity = $this->form()->entity();
					if($entity instanceof \Zbase\Entity\Laravel\Node\Node)
					{
						$this->_viewParams['node'] = $entity;
						$this->_selectedRows = $entity->categories()->get();
					}
					if($entity instanceof \Zbase\Entity\Laravel\Node\Category)
					{
						$this->_viewParams['node'] = $entity;
						$this->_selectedRows = $entity->parent()->get();
					}
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
	public function validateWidget($action)
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
