<?php

namespace Zbase\Widgets\Type;

/**
 * Zbase-Widgets Widget-Type Datatable
 *
 * https://developer.mozilla.org/en-US/docs/Web/Accessibility/ARIA/ARIA_Techniques/Using_the_aria-labelledby_attribute
 * http://v4-alpha.getbootstrap.com/components/forms/#form-controls
 * Process and Displays a dynamic form
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Datatable.php
 * @project Zbase
 * @package Zbase/Widgets/Type
 *
 *
 */
use Zbase\Widgets;

class Datatable extends Widgets\Widget implements Widgets\WidgetInterface, Widgets\ControllerInterface
{

	/**
	 * Widget Type
	 * @var string
	 */
	protected $_type = 'datatable';

	/**
	 * The ViewFile string
	 * @var string
	 */
	protected $_viewFile = 'ui.datatable';

	/**
	 * The View Type grid|row|list
	 * @var string
	 */
	protected $_viewType = 'grid';

	/**
	 *
	 * @var type
	 */
	protected $_entity = null;

	/**
	 * Processed Grid Columns
	 * @var array
	 */
	protected $_columns = [];

	/**
	 * Processed Grid Columns
	 * @var \Zbase\Models\Data\Column[]
	 */
	protected $_processedColumns = [];

	/**
	 * Columns prepared?
	 * @var boolean
	 */
	protected $_columnsPrepared = false;

	/**
	 * 	Rows
	 * @var \Zbase\Entity\EntityInterface
	 */
	protected $_rows = [];

	/**
	 * Has Actions Flag?
	 * @var boolean
	 */
	protected $_hasActions = false;
	protected $_actions = [];
	protected $_actionButtons = [];

	/**
	 * Create Action Button
	 * @var \Zbase\Ui\Component
	 */
	protected $_actionCreateButton = null;

	/**
	 * Rows are prepared?
	 * @var boolean
	 */
	protected $_rowsPrepared = false;
	protected $_htmlWrapperAttributes = ['class' => ['table-responsive']];

	/**
	 * Return the data filters
	 * @return array
	 */
	public function getRequestFilters()
	{
		$filters = zbase_request_query_input('filter');
		if($this->isPublic())
		{
			$filters['public'] = true;
		}
		if(!empty($filters))
		{
			foreach ($filters as $fK => $fV)
			{
				$filters[$fK] = trim($fV, chr(0xC2) . chr(0xA0));
			}
			return $filters;
		}
		return false;
	}

	/**
	 * Return the Data Sorting
	 * @return array
	 */
	public function getRequestSorting()
	{
		$sorting = zbase_request_query_input('sort');
		if(!empty($sorting))
		{
			return $sorting;
		}
		return false;
	}

	/**
	 * Return node sortable Columns
	 * @return array|null
	 */
	public function getSortableColumns()
	{
		/**
		 * Check if we are browsing categories and displaying its nodes
		 */
		if($this->isNode() && $this->isNodeCategory() && $this->_entity instanceof \Zbase\Entity\Laravel\Node\Nested)
		{
			$entity = $this->_entity;
			return zbase_entity($entity::$nodeNamePrefix)->getSortableColumns();
		}
		/**
		 * Check if we are browsing categories and displaying its nodes
		 */
		if($this->isNode() && $this->_entity instanceof \Zbase\Entity\Laravel\Node\Node)
		{
			return $this->_entity->getSortableColumns();
		}
		return null;
	}

	/**
	 * return the number of rows per page
	 * @return array
	 */
	public function getRowsPerPages()
	{
		/**
		 * Check if we are browsing categories and displaying its nodes
		 */
		if($this->isNode() && $this->isNodeCategory() && $this->_entity instanceof \Zbase\Entity\Laravel\Node\Nested)
		{
			$entity = $this->_entity;
			return zbase_entity($entity::$nodeNamePrefix)->getRowsPerPages();
		}
		/**
		 * Check if we are browsing categories and displaying its nodes
		 */
		if($this->isNode() && $this->_entity instanceof \Zbase\Entity\Laravel\Node\Node)
		{
			return $this->_entity->getRowsPerPages();
		}
		return null;
	}

	// <editor-fold defaultstate="collapsed" desc="Rows">

	/**
	 * Return the Categories
	 * Some nodes has categories,
	 * 	so let's return their subCategories
	 *
	 * @return null|\Zbase\Entity\Laravel\Node\Nested[]
	 */
	public function categoryDescendants()
	{
		if($this->isNodeCategory() && $this->_entity instanceof \Zbase\Entity\Laravel\Node\Nested)
		{
			return $this->_entity->getDescendants();
		}
		if($this->isNodeCategory())
		{
			$entity = $this->_entity;
			if(!$entity instanceof \Zbase\Entity\Laravel\Node\Nested)
			{
				$entity = zbase_entity($entity::$nodeNamePrefix . '_category');
				return $entity->getRoot()->getDescendants()->toHierarchy();
			}
		}
		return null;
	}

	/**
	 * Prepare and fetch all rows
	 */
	protected function _rows()
	{
		if(empty($this->_rowsPrepared))
		{
			try
			{
				if(!empty($this->_entity))
				{
					$repo = $this->_entity->setPerPage(zbase_request_query_input('pp', $this->_entity->getPerPage()))->repository();
					$filters = [];
					$sorting = [];
					$joins = [];
					if($this->isNode())
					{
						$urlFilters = $this->getRequestFilters();
						$entityObject = $this->entityObject();
						$selects = ['*'];
						if($this->isNodeCategory() && $this->_entity instanceof \Zbase\Entity\Laravel\Node\Nested)
						{
							/**
							 * We are getting the nodes on each category
							 *  switch to node entityObject
							 */
							$entityObject = zbase_entity($entityObject::$nodeNamePrefix, [], true);
							$repo = $entityObject->setPerPage(zbase_request_query_input('pp', $this->_entity->getPerPage()))->repository();
							$categories = $this->_entity->getDescendantsAndSelf();
							if(!empty($categories))
							{
								foreach ($categories as $category)
								{
									$urlFilters['category'][] = $category;
								}
							}
						}
						if($this->isCurrentUser())
						{
							$urlFilters['currentUser'] = true;
						}
						if(!empty($urlFilters))
						{
							if(method_exists($entityObject, 'queryFilters'))
							{
								$filters = $entityObject->queryFilters($urlFilters, $this->getRequestSorting());
							}
						}
						if(method_exists($entityObject, 'querySelects'))
						{
							$selects = $entityObject->querySelects($urlFilters);
						}
						if(method_exists($entityObject, 'queryJoins'))
						{
							$joins = $entityObject->queryJoins($urlFilters, $this->getRequestSorting());
						}
						if(method_exists($entityObject, 'querySorting'))
						{
							$sorting = $entityObject->querySorting($this->getRequestSorting(), $urlFilters);
						}
						// dd($joins, $sorting, $filters);
					}
					$debug = zbase_request_query_input($this->id() . '__debug', false);
					if($this->isPublic())
					{
						$this->_rows = $repo->setDebug($debug)->all($selects, $filters, $sorting, $joins, true);
					}
					else
					{
						if($this->_entity->hasSoftDelete())
						{
							$this->_rows = $repo->setDebug($debug)->withTrashed()->all($selects, $filters, $sorting, $joins, true);
						}
						else
						{
							$this->_rows = $repo->setDebug($debug)->all($selects, $filters, $sorting, $joins, true);
						}
					}
				}
				$this->_rowsPrepared = true;
			} catch (\Zbase\Exceptions\RuntimeException $e)
			{
				if(zbase_in_dev($e))
				{
					dd($e);
				}
				else
				{
					zbase_abort(500);
				}
			}
		}
	}

	/**
	 * Return the fetch rows
	 * @var \Zbase\Entity\EntityInterface[]
	 */
	public function getRows()
	{
		$this->_rows();
		return $this->_rows;
	}

	// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc="COLUMNS">
	/**
	 * Prepare Columns
	 */
	protected function _columns()
	{
		if(empty($this->_columnsPrepared))
		{
			if(!empty($this->_columns))
			{
				foreach ($this->_columns as $name => $config)
				{
					if(empty($config['id']))
					{
						$config['id'] = $name;
					}
					$col = new \Zbase\Models\Data\Column($config);
					$this->_processedColumns[$name] = $col;
				}
			}
			$this->_columnsPrepared = true;
			$this->_processedColumns = $this->sortPosition($this->_processedColumns);
		}
	}

	/**
	 * Set Columns
	 * @param array $columns
	 */
	public function setColumns($columns)
	{
		$this->_columnsPrepared = false;
		$this->_processedColumns = null;
		$this->_columns = $columns;
		$this->_columns();
	}

	/**
	 * Return all columns
	 * @return array
	 */
	public function getColumns()
	{
		$this->_columns();
		return $this->_columns;
	}

	/**
	 * Return the Processed Columns
	 * @return \Zbase\Models\Data\Column[]
	 */
	public function getProcessedColumns()
	{
		return $this->_processedColumns;
	}

	// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc="Actions">

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
			foreach ($this->_actions as $action)
			{
				$enable = !empty($action['enable']) ? true : false;
				if(!empty($enable))
				{
					$this->_hasActions = true;
				}
			}
		}
	}

	/**
	 * Render the actions
	 */
	public function renderRowActions($row)
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
	 * Has Actions?
	 * return boolean
	 */
	public function hasActions()
	{
		return $this->_hasActions;
	}

	/**
	 * Return an Action by ID
	 * @param type $actionName
	 * @return \Zbase\Ui\UiInterface|null
	 */
	public function getActionButton($actionName)
	{
		$this->_actions();
		if(!empty($this->_actionButtons))
		{
			foreach ($this->_actionButtons as $action)
			{
				if($action instanceof \Zbase\Ui\UiInterface && $action->id() == $this->id() . 'Action' . $actionName)
				{
					return $action;
				}
			}
		}
		return null;
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

	// </editor-fold>

	/**
	 * Controller Action
	 * 	This will be called validating the form
	 * @param string $action
	 */
	public function controller($action)
	{
		if(!$this->checkUrlRequest())
		{
			return zbase_abort(404);
		}
		$this->_rows();
		$this->_actions();
		$this->_columns();
	}

	/**
	 * Validate widget
	 */
	public function validateWidget()
	{
		$this->_pre();
	}

	protected function _pre()
	{
		$this->entity();
	}

	/**
	 * Return the Wrapper Attributes
	 * @return array
	 */
	public function wrapperAttributes()
	{
		$attr = parent::wrapperAttributes();
		$attr['class'][] = 'zbase-widget-wrapper';
		$attr['class'][] = 'zbase-widget-wrapper-' . $this->_type;
		$attr['id'] = 'zbase-widget-wrapper-' . $this->id();
		return $attr;
	}

}
