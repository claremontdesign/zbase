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
	 * Empty message to display
	 * @var string
	 */
	protected $_emptyViewFile = '';

	/**
	 * 	Rows
	 * @var \Zbase\Entity\EntityInterface
	 */
	protected $_rows = [];
	protected $_repo = null;
	protected $_repoSelects = ['*'];
	protected $_repoJoins = [];
	protected $_repoFilters = [];
	protected $_repoSorts = [];
	protected $_repoPerPage = 10;

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

	public function __construct($widgetId, $configuration)
	{
		$this->_emptyViewFile = zbase_view_file_contents('ui.datatable.empty');
		parent::__construct($widgetId, $configuration);
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
	 * Prepare Repository
	 */
	protected function _repo()
	{
		if(!empty($this->_entity))
		{
			// <editor-fold defaultstate="collapsed" desc="PostInterface">
			/**
			 * PostInterface
			 * August 26, 2016
			 */
			if($this->_entity instanceof \Zbase\Post\PostInterface)
			{
				$entityObject = $this->entityObject();
				$entity = $this->_entity;
				$entityName = $entity->postTableName();
				$perPage = 10;
				$filters = [];
				$sorting = [];
				$joins = [];
				$urlFilters = [];
				$selects = ['*'];
				if($entity instanceof \Illuminate\Database\Eloquent\Collection)
				{
					$entity = $this->_entity->first();
					if($entity instanceof \Zbase\Interfaces\EntityInterface)
					{
						$perPage = $entity->postRowsPerPage();
					}
				}
				$this->_repoPerPage = zbase_request_query_input('pp', $perPage);
				if($this->isExporting())
				{
					$this->_repoPerPage = 99999999999;
				}
				$repo = $entityObject->repository();
				if($this->isNode())
				{
					/**
					 * Filters from the URL
					 */
					$urlFilters = $this->getRequestFilters();
					if($this->isNodeCategory() && $entity instanceof \Zbase\Entity\Laravel\Node\Nested)
					{
						/**
						 * We are getting the nodes on each category
						 *  switch to node entityObject
						 */
						$entityObject = zbase_entity($entityName . '_category', [], true);
						$repo = $entityObject->repository();
						$categories = $entity->getDescendantsAndSelf();
						if(!empty($categories))
						{
							foreach ($categories as $category)
							{
								$urlFilters['category'][] = $category;
							}
						}
					}
					if($this->isPublic())
					{
						$urlFilters['public'] = true;
					}
					if($this->isCurrentUser())
					{
						$urlFilters['currentUser'] = true;
					}
					$filters = $entityObject->postDatatableQueryFilters($urlFilters, $this);
					$selects = $entityObject->postDatatableQuerySelects($this);
					$joins = $entityObject->postDatatableQueryJoins($this);
					$sorting = $entityObject->postDatatableQuerySorting($this->getRequestSorting(), $this);
					/**
					 * Merge filters from widget configuration
					 * entity.filter.query
					 */
					if(!empty($filters))
					{
						$filters = array_merge($filters, $this->_v('entity.filter.query', []));
					}
					else
					{
						$widgetFilter = $this->_v('entity.filter.query', false);
						if(!empty($widgetFilter))
						{
							$filters = $widgetFilter;
						}
					}
				}
				$this->_repoSelects = $selects;
				$this->_repoJoins = $joins;
				$this->_repoSorts = $sorting;
				$this->_repoFilters = $filters;
				$this->_repo = $repo;
				return $this->_repo;
			}
			// </editor-fold>
			// <editor-fold defaultstate="collapsed" desc="EntityInterface">
			/**
			 * Will be deprecated in favor of PostInterface
			 */
			$entityObject = $this->entityObject();
			$entity = $this->_entity;
			$perPage = 10;
			if($entity instanceof \Illuminate\Database\Eloquent\Collection)
			{
				$entity = $this->_entity->first();
				if($entity instanceof \Zbase\Interfaces\EntityInterface)
				{
					$perPage = $entity->getPerPage();
				}
			}
			$this->_repoPerPage = zbase_request_query_input('pp', $this->_v('row.perpage', $perPage));
			if($this->isExporting())
			{
				$this->_repoPerPage = 99999999999;
			}
			$repo = $entityObject->repository()->perPage($this->_repoPerPage);
			$filters = [];
			$sorting = [];
			$joins = [];
			$selects = ['*'];
			if($this->isNode())
			{
				$urlFilters = $this->getRequestFilters();
				if($this->isNodeCategory() && $entity instanceof \Zbase\Entity\Laravel\Node\Nested)
				{
					/**
					 * We are getting the nodes on each category
					 *  switch to node entityObject
					 */
					$entityObject = zbase_entity($entityObject::$nodeNamePrefix, [], true);
					$repo = $entityObject->repository()->perPage(zbase_request_query_input('pp', $this->_v('row.perpage', $perPage)));
					$categories = $this->_entity->getDescendantsAndSelf();
					if(!empty($categories))
					{
						foreach ($categories as $category)
						{
							$urlFilters['category'][] = $category;
						}
					}
				}
				if($this->isPublic())
				{
					$urlFilters['public'] = true;
				}
				if($this->isCurrentUser())
				{
					$urlFilters['currentUser'] = true;
				}
				if(!empty($urlFilters))
				{
					if(method_exists($entityObject, 'queryFilters'))
					{
						$filters = $entityObject->queryFilters($urlFilters, $this->getRequestSorting(), ['widget' => $this]);
					}
					else
					{
						if(!empty($urlFilters['public']))
						{
							$filters['status'] = 2;
						}
						if(!empty($urlFilters['currentUser']))
						{
							$filters['user_id'] = zbase_auth_user()->id();
						}
					}
				}
				else
				{
					if(method_exists($entityObject, 'queryFilters'))
					{
						$filters = $entityObject->queryFilters($urlFilters, $this->getRequestSorting(), ['widget' => $this]);
					}
				}
				if(method_exists($entityObject, 'querySelects'))
				{
					$selects = $entityObject->querySelects($urlFilters, ['widget' => $this]);
				}
				if(method_exists($entityObject, 'queryJoins'))
				{
					$joins = $entityObject->queryJoins($urlFilters, $this->getRequestSorting(), ['widget' => $this]);
				}
				if(method_exists($entityObject, 'querySorting'))
				{
					$sorting = $entityObject->querySorting($this->getRequestSorting(), $urlFilters, ['widget' => $this]);
				}
				/**
				 * Merge filters from widget configuration
				 * entity.filter.query
				 */
				if(!empty($filters))
				{
					$filters = array_merge($filters, $this->_v('entity.filter.query', []));
				}
				else
				{
					$widgetFilter = $this->_v('entity.filter.query', false);
					if(!empty($widgetFilter))
					{
						$filters = $widgetFilter;
					}
				}
				if($this->isExporting())
				{
					if(method_exists($entityObject, 'queryExportFilters'))
					{
						$filters = $entityObject->queryExportFilters($filters, ['widget' => $this]);
					}
				}
				if($this->isSearchable() && $this->isSearching())
				{
					if(method_exists($entityObject, 'querySearchFilters'))
					{
						$filters = $entityObject->querySearchFilters($filters, ['widget' => $this]);
					}
				}
				// dd($joins, $sorting, $filters);
			}
			$this->_repoSelects = $selects;
			$this->_repoJoins = $joins;
			$this->_repoSorts = $sorting;
			$this->_repoFilters = $filters;
			$debug = zbase_request_query_input($this->id() . '__debug', false);
			$repo->setDebug($debug);
			$repo->setQueryName($this->getWidgetPrefix('Repo'));
			$this->_repo = $repo;
			return $this->_repo;
			// </editor-fold>
		}
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
					if($this->isQueryOnLoad())
					{
						$repo = $this->_repo();
						$entityObject = $this->entityObject();
						if($entityObject->hasSoftDelete() && $this->nodeIncludeTrashed())
						{
							$this->_rows = $repo->withTrashed()->all($this->_repoSelects, $this->_repoFilters, $this->_repoSorts, $this->_repoJoins, $this->_repoPerPage);
						}
						else
						{
							$this->_rows = $repo->all($this->_repoSelects, $this->_repoFilters, $this->_repoSorts, $this->_repoJoins, $this->_repoPerPage);
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
		//$rows = $this->_v('rows', null);
		if(!empty($this->_rows))
		{
			return $this->_rows;
		}
		$this->_rows();
		return $this->_rows;
	}

	/**
	 * Set the Rows
	 *
	 * @return $this
	 */
	public function setRows($rows)
	{
		$this->_rows = $rows;
		return $this;
	}

	// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc="Searching">
	/**
	 * Return the Search Text Placeholder
	 *
	 * @return string
	 */
	public function searchTextPlaceholder()
	{
		return $this->_v('searchable.input.placeholder', 'Search Data');
	}

	/**
	 * Return the Search Text Placeholder
	 *
	 * @return string
	 */
	public function searchResultsTemplate()
	{
		return $this->_v('searchable.view.template', zbase_view_file_contents('ui.datatable.table'));
	}

	/**
	 * If table is inQueryOnLoad,
	 * data will be loaded on default.
	 * will query DB
	 *
	 * @return boolean
	 */
	public function isQueryOnLoad()
	{
		if($this->isSearching())
		{
			return true;
		}
		return $this->_v('queryOnLoad', true);
	}

	/**
	 * Is Searchable?
	 * @return boolean
	 */
	public function isSearchable()
	{
		return $this->_v('searchable.enable', $this->_v('searchable', false));
	}

	/**
	 * Check if Data returned from search is a JSON object
	 *
	 * @return boolean
	 */
	public function isSearchResultJson()
	{
		return $this->_v('searchable.json', false);
	}

	/**
	 * Check if we are searching
	 *
	 * @return boolean
	 */
	public function isSearching()
	{
		if(zbase_request_is_post() && zbase_is_json() && !empty(zbase_request_input($this->getWidgetPrefix('search_query'))))
		{
			return true;
		}
		return false;
	}

	/**
	 * Will search for the saved value on Load.
	 * Default false
	 *
	 * @return boolean
	 */
	public function isSearchOnLoad()
	{
		return $this->_v('searchable.onload', false);
	}

	/**
	 * Return search queries
	 * @return string|array
	 */
	public function getSearchKeyword()
	{
		if(zbase_is_post())
		{
			return zbase_request_input($this->getWidgetPrefix('search_query'));
		}
	}

	// </editor-fold>

	/**
	 * Check if table rows can be selectable.
	 * Presence of a checkbox
	 *
	 * @return boolean
	 */
	public function isRowSelectable()
	{
		return $this->_v('row.selectable.enable', $this->_v('rows.selectable', false));
	}

	/**
	 * Check if table rows can be clickable
	 *
	 * @return boolean
	 */
	public function isRowsClickable()
	{
		return $this->_v('row.clickable.enable', $this->_v('rows.clickable', false));
	}

	/**
	 * Check if when clicking rows will open a row after
	 *
	 * @return boolean
	 */
	public function isRowsClickableToNextRow()
	{
		return $this->_v('row.clickable.nextrow', $this->_v('rows.clickable', false));
	}

	/**
	 * Return the Row clickable URL
	 * @param EntityInterface $row
	 * @param boolean $template If to generate a template
	 * @return string
	 */
	public function getRowClickableUrl($row, $template = false)
	{
		$action = $this->_v('row.clickable.action', null);
		if(!empty($action) && is_array($action))
		{
			$actionConfig = $action;
		}
		else
		{
			/**
			 * It's a string, a reference to actions.actionName indexes
			 */
			$actionConfig = $this->_v('actions.' . $action);
		}
		$btn = $this->createActionBtn('view', $row, $actionConfig, $template);
		if($btn instanceof \Zbase\Ui\UiInterface)
		{
			return $btn->href();
		}
		return '#';
	}

	/**
	 * Return Search URL
	 * @return string
	 */
	public function getSearchUrl()
	{
		return $this->_v('searchable.url', zbase_url_from_current());
	}

	/**
	 * The empty view file
	 * @param type $emptyViewFile
	 * @return \Zbase\Widgets\Type\Datatable
	 */
	public function setEmptyViewFile($emptyViewFile)
	{
		if(!empty($emptyViewFile))
		{
			$this->_emptyViewFile = $emptyViewFile;
		}
		return $this;
	}

	/**
	 * Empty Message
	 * @return string
	 */
	public function emptyViewFile()
	{
		return $this->_emptyViewFile;
	}

	/**
	 * The Row Value Index
	 *  Should be unique, like id or alpha_id
	 * @return string
	 */
	public function rowValueIndex()
	{
		return $this->_v('value.index', 'alpha_id');
	}

	/**
	 * Return the data filters
	 * @return array
	 */
	public function getRequestFilters()
	{
		$filters = zbase_request_query_input('filter');
		if(!empty($filters))
		{
			foreach ($filters as $fK => $fV)
			{
				$filters[$fK] = trim($fV, chr(0xC2) . chr(0xA0));
			}
			return $filters;
		}
		return [];
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
	 * Return the Current Page
	 */
	public function getCurrentPage()
	{
		$sorting = zbase_request_query_input('page');
		if(!empty($sorting))
		{
			return $sorting;
		}
		return 1;
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
					if($col->enable())
					{
						$this->_processedColumns[$name] = $col;
					}
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
	 *
	 * @param Entity $row
	 * @param boolean $template
	 */
	public function renderRowActions($row, $template = false)
	{
		if(!empty($this->_actions))
		{
			$this->_actionButtons = [];
			foreach ($this->_actions as $actionName => $actionConfig)
			{
				$btn = $this->createActionBtn($actionName, $actionConfig, $row, $template);
				if(!empty($btn))
				{
					$this->_actionButtons[] = $btn;
				}
			}
			return implode("\n", $this->_actionButtons);
		}
	}

	/**
	 * Create an action button
	 * @param string $actionName The Action index name update|create|delete|ddelete|restore
	 * @param \Zbase\Interfaces\EntityInterface $row
	 * @param array $actionConfig Action config
	 * @param boolean $template If we will have to generate a template
	 * @return \Zbase\Ui\UiInterface
	 */
	public function createActionBtn($actionName, $row, $actionConfig, $template = false)
	{
		if(!$row instanceof \Zbase\Interfaces\EntityInterface && !$template)
		{
			return null;
		}
		if(empty($actionConfig))
		{
			return null;
		}
		$rowTrashed = false;
		if($this->_entity->hasSoftDelete() && !empty($row))
		{
			$rowTrashed = $row->trashed();
		}
		if($actionName == 'delete' || $actionName == 'update')
		{
			if($rowTrashed)
			{
				return null;
			}
		}
		if($actionName == 'restore' || $actionName == 'ddelete')
		{
			if(empty($rowTrashed))
			{
				return null;
			}
		}
		$label = !empty($actionConfig['label']) ? $actionConfig['label'] : ucfirst($actionName);
		if(strtolower($label) == 'ddelete')
		{
			$label = _zt('Forever Delete');
		}
		$actionConfig['type'] = 'component.button';
		$actionConfig['size'] = 'extrasmall';
		$actionConfig['label'] = _zt($label);
		$actionConfig['tag'] = 'a';
		if(!empty($actionConfig['route']['name']))
		{
			if(!empty($actionConfig['route']['params']))
			{
				foreach ($actionConfig['route']['params'] as $paramName => $paramValue)
				{
					if(preg_match('/row::/', $paramValue))
					{
						$rowIndex = str_replace('row::', '', $paramValue);
						if(!empty($row))
						{
							$id = $actionConfig['route']['params'][$paramName] = zbase_data_get($row, $rowIndex);
						}
						else
						{
							if(!empty($template))
							{
								$id = $actionConfig['route']['params'][$paramName] = '__' . $rowIndex . '__';
							}
						}
					}
				}
			}
			$actionConfig['routeParams'] = $actionConfig['route']['params'];
			$actionConfig['route'] = $actionConfig['route']['name'];
		}
		$actionConfig['id'] = $this->getWidgetPrefix() . 'Action' . $actionName . (!empty($id) ? $id : null);
		if($actionName == 'create')
		{
			$actionConfig['color'] = 'blue';
		}
		if($actionName == 'update')
		{
			$actionConfig['color'] = 'green';
		}
		if($actionName == 'view')
		{
			$actionConfig['color'] = 'blue';
		}
		if($actionName == 'delete')
		{
			$actionConfig['color'] = 'red';
		}
		if($actionName == 'restore')
		{
			$actionConfig['color'] = 'warning';
		}
		if($actionName == 'ddelete')
		{
			$actionConfig['color'] = 'red';
		}
		$btn = \Zbase\Ui\Ui::factory($actionConfig);
		if($actionName == 'create')
		{
			if(!$this->_actionCreateButton instanceof \Zbase\Ui\UiInterface)
			{
				$this->_actionCreateButton = $btn;
			}
		}
		return $btn;
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
		if($this->isExporting())
		{
			return $this->export();
		}
	}

	/**
	 * Validate widget
	 */
	public function validateWidget($action)
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
		$attr['class'][] = 'zbase-widget-wrapper flip-scroll';
		$attr['class'][] = 'zbase-widget-wrapper-' . $this->_type;
		$attr['id'] = 'zbase-widget-wrapper-' . $this->id();
		return $attr;
	}

	/**
	 * Convert Data to JSON
	 *
	 * @return array
	 */
	public function toArray()
	{
		$rows = $this->getRows();
		if(!empty($rows))
		{
			$datas = [
				'currentPage' => $rows->currentPage(),
				'maxPage' => $rows->lastPage(),
				'totalRows' => $rows->total()
			];
			$columns = $this->getProcessedColumns();
			if(!empty($rows))
			{
				foreach ($rows as $row)
				{
					if(method_exists($row, 'cast'))
					{
						$row = $row->cast();
					}
					if($this->isNodeCategory() && $this->_entity instanceof \Zbase\Entity\Laravel\Node\Nested)
					{
						$row->setBrowseCategory($this->entity());
					}
					$data = [];
					foreach ($columns as $column)
					{
						$column->setRow($row)->prepare();
						$value = $column->renderValue();
						$columnName = $column->id();
						if($value instanceof \Zbase\Ui\UiInterface)
						{
							$data[$columnName] = $column->renderValue()->__toString();
						}
						else
						{
							$data[$columnName] = $column->renderValue();
						}
					}
					$datas['rows'][] = $data;
				}
			}
			return $datas;
		}
		return [];
	}

	// <editor-fold defaultstate="collapsed" desc="Export">

	/**
	 * Export datatable
	 *
	 * @return array
	 */
	public function export()
	{
		$rows = $this->getRows();
		if(!empty($rows))
		{
			$datas = [];
			if(!empty($rows))
			{
				$format = strtolower($this->exportFormat());
				$columns = $this->exportColumns();
				$prefix = $this->exportFilename();
				$name = $this->exportName();
				$filename = $prefix . date('Ymdhisa');
				$headers = [];
				if(!empty($columns))
				{
					foreach ($columns as $cIndexId => $cSettings)
					{
						$headers[] = !empty($cSettings['label']) ? $cSettings['label'] : $cIndexId;
					}
					$datas[] = $headers;
				}
				foreach ($rows as $row)
				{
					if(method_exists($row, 'cast'))
					{
						$row = $row->cast();
					}
					if($this->isNodeCategory() && $this->_entity instanceof \Zbase\Entity\Laravel\Node\Nested)
					{
						$row->setBrowseCategory($this->entity());
					}
					if($row instanceof \Zbase\Post\Interfaces\ExportInterface)
					{
						$rowData = $row->exportToArray($columns, []);
						if(!empty($columns))
						{
							$data = [];
							foreach ($columns as $cIndexId => $cSettings)
							{
								$data[] = isset($rowData[$cIndexId]) ? $rowData[$cIndexId] : '';
							}
							$datas[] = $data;
						}
					}
				}
				if(!empty($datas))
				{
					if($format == 'excel')
					{
						$excel = \Excel::create($filename, function($excel) use ($datas, $name) {
									$excel->sheet($name, function($sheet) use ($datas) {
										$sheet->freezeFirstRowAndColumn();
										$sheet->fromArray($datas, null, 'A1', false, false);
							});
						})->store('xlsx');
						return redirect()->to(zbase_public_download_link($filename . '.xlsx'));
					}
				}
			}
		}
		return [];
	}

	/**
	 * Is Exportable
	 * @return boolean
	 */
	public function isExportable()
	{
		return $this->_v('exportable.enable', $this->_v('exportable', false));
	}

	/**
	 * Export Headers
	 * @return boolean
	 */
	public function exportColumns()
	{
		return $this->_v('exportable.columns', false);
	}

	/**
	 * Export Headers
	 * @return boolean
	 */
	public function exportableFilters()
	{
		return $this->_v('exportable.filters', []);
	}

	/**
	 * Export Headers
	 * @return boolean
	 */
	public function exportFilename()
	{
		return $this->_v('exportable.filename', $this->getWidgetPrefix('export'));
	}

	/**
	 * Export Headers
	 * @return boolean
	 */
	public function exportName()
	{
		return $this->_v('exportable.name', $this->getWidgetPrefix('export'));
	}

	/**
	 * Is Exportable
	 * @return boolean
	 */
	public function exportFormat()
	{
		return zbase_request_input($this->getWidgetPrefix('export') . 'Format', 'excel');
	}

	/**
	 * Return the Export Filters
	 * @return array
	 */
	public function exportFilters()
	{
		return zbase_request_input($this->getWidgetPrefix('export') . 'Filter', []);
	}

	// </editor-fold>
}
