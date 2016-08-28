<?php

namespace Zbase\Widgets;

/**
 * Zbase-Widgets Widget
 *
 * Widget base model
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Widget.php
 * @project Zbase
 * @package Zbase/Widgets
 *
 * type = Type of Widget eg. form
 * id = name of widget; unique; [optional], default to name of widget file
 * enable = true|false
 * access = access [optional], default to minimum access
 * 		string: minimum|admin
 * 		array: [admin, user]
 * 		Who has access.
 * 		minimum|role name
 * 		minimum is the minimum role for the current section, else a role name or array of role names
 *
 * config = array; widget-type-specific configuration
 */
use Zbase\Traits;

class Widget extends \Zbase\Ui\Ui implements \Zbase\Ui\UiInterface
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
	 * The Widget ID
	 * @var string
	 */
	protected $_widgetId = null;

	/**
	 * Current task
	 * display|update|delete|restore|ddelete|create
	 * @var string
	 */
	protected $_action = null;

	/**
	 * The Task
	 * @var string
	 */
	protected $_task = null;

	/**
	 * The Entity task add|update|delete|restore|ddelete|row|rows
	 * @var string
	 */
	protected $_entityTask = null;

	/**
	 * The Entity
	 * @var EntityInterface
	 */
	protected $_entity = null;

	/**
	 * ChildEntity when browsing a parent or parent category
	 * @see isNodeCategoryBrowsing
	 * @var
	 */
	protected $_childEntity = null;

	/**
	 * Flag to check if URL has a request
	 * @var type
	 */
	protected $_urlHasRequest = false;

	/**
	 * The Entity Object
	 * @var EntityInterface
	 */
	protected $_entityObject = null;

	/**
	 * Flag that Entity given was default
	 * @var boolean
	 */
	protected $_entityIsDefault = false;

	/**
	 * The Module
	 * @var \Zbase\Module\ModuleInterface
	 */
	protected $_module = null;

	/**
	 * Node Suppport?
	 * @var boolean
	 */
	protected $_nodeSupport = false;
	protected $_nodeName = null;
	protected $_parentEntityObject = null;

	/**
	 * Constructor
	 * @param string $widgetId
	 * @param array $configuration
	 */
	public function __construct($widgetId, $configuration)
	{
		$this->_widgetId = $widgetId;
		$this->setAttributes($configuration);
		if(isset($configuration['_viewFileContent']) && is_bool($configuration['_viewFileContent']))
		{
			$this->_viewFileContent = $configuration['_viewFileContent'];
		}
		$this->setViewFile($this->_v('view.file', $this->_viewFile));
	}


	/**
	 * POST
	 *
	 * Check if Entity is of PostInterace
	 *
	 * @return boolean
	 */
	public function entityIsPostInterface($entity)
	{
		return $entity instanceof \Zbase\Post\PostInterface;
	}


	/**
	 * Set Page Property
	 * @param string $action The Controller Action
	 * @param array $param some assoc array that can be replace to the string
	 * @return void
	 */
	public function pageProperties($action)
	{
		$enable = $this->_v('page.' . $action . '.enable', false);
		if(!empty($enable))
		{
			$title = $this->_v('page.' . $action . '.title', null);
			$headTitle = $this->_v('page.' . $action . '.headTitle', $title);
			$subTitle = $this->_v('page.' . $action . '.subTitle', null);
			$breadcrumbs = $this->_v('page.' . $action . '.breadcrumbs', []);
			if(!empty($title))
			{
				zbase_view_pagetitle_set($headTitle, $title, $subTitle);
			}
			if(!empty($breadcrumbs))
			{
				zbase_view_breadcrumb($breadcrumbs);
			}
		}
		return $this;
	}

	/**
	 * The generice node name
	 * @param type $nodeName
	 * @return \Zbase\Widgets\Widget
	 */
	public function setNodeName($nodeName)
	{
		$this->_nodeName = $nodeName;
		return $this;
	}

	/**
	 * Do we need generic node support?
	 * @param type $flag
	 * @return \Zbase\Widgets\Widget
	 */
	public function setNodeSupport($flag)
	{
		$this->_nodeSupport = $flag;
		return $this;
	}

	/**
	 * return the node namespace
	 * @return string
	 */
	public function getNodeNamespace()
	{
		return $this->getModule()->nodeNamespace();
	}

	public function id()
	{
		return $this->_widgetId;
	}

	/**
	 * Return a usable clean Prefix
	 *
	 * @return string
	 */
	public function getWidgetPrefix($tag = null)
	{
		return zbase_string_camel_case(str_replace('_', '', $this->id()) . (!empty($tag) ? '_' . $tag : ''));
	}

	/**
	 * Set the Module
	 * @param \Zbase\Module\ModuleInterface $module
	 */
	public function setModule(\Zbase\Module\ModuleInterface $module)
	{
		$this->_module = $module;
	}

	/**
	 *
	 * @return \Zbase\Module\ModuleInterface $module
	 */
	public function getModule()
	{
		return $this->_module;
	}

	/**
	 * SEt the Action
	 * @param string $action
	 * @return \Zbase\Widgets\Widget
	 */
	public function setAction($action)
	{
		$this->_action = $action;
		return $this;
	}

	/**
	 * Return the Action
	 * @return string
	 */
	public function getAction()
	{
		return $this->_action;
	}

	/**
	 * SEt the Task
	 * @param string $task
	 * @return \Zbase\Widgets\Widget
	 */
	public function setTask($task)
	{
		$this->_task = $task;
		return $this;
	}

	/**
	 * Return the TAsk
	 * @return string
	 */
	public function getTask()
	{
		$routeTask = zbase_route_input('task', null);
		if(!empty($routeTask) && empty($this->_task))
		{
			$this->setTask($routeTask);
		}
		return $this->_task;
	}

	/**
	 * Proxy
	 * @return Zbase\Widget\EntityInterface
	 */
	protected function _entity()
	{
		$entity = $this->entity();
		return $entity;
	}

	/**
	 * Return the Child Entity
	 * @return Node
	 */
	public function getChildEntity()
	{
		$entity = $this->_childEntity;
		return $entity;
	}

	/**
	 * Preparation
	 */
	protected function _pre()
	{
		parent::_pre();
	}

	/**
	 * Post Prep
	 */
	protected function _post()
	{
		parent::_post();
	}

	/**
	 * Check if we are filtering for publico
	 * @return boolean
	 */
	public function isPublic()
	{
		return $this->_v('entity.filter.public', false);
	}

	/**
	 * Check if this has an entity to be checked.
	 *
	 * @return boolean
	 */
	public function hasEntity()
	{
		$entity = $this->_v('entity', false);
		if(!empty($entity))
		{
			return $entity;
		}
		return false;
	}

	/**
	 * Check if we are filtering for the current user
	 * @return boolean
	 */
	public function isCurrentUser()
	{
		return $this->_v('entity.filter.currentUser', false);
	}

	/**
	 * Check if we are filtering for the current user
	 * @return boolean
	 */
	public function isAdmin()
	{
		return $this->_v('entity.filter.admin', false);
	}

	/**
	 * Node Typoe Entity
	 * @return type
	 */
	public function isNode()
	{
		return $this->_v('entity.node.enable', false);
	}

	/**
	 * Check if we are on the Category > nodes
	 * @return boolean
	 */
	public function isNodeCategory()
	{
		return $this->_v('entity.node.category', false);
	}

	/**
	 * Check if we are on the Category and browsing items
	 *  When browsing items, we opened an item from that category,
	 *  we keep the Category URL and prepend the Item AlphaID: /nodes/category-slug/node-alpha-id
	 * @return boolean
	 */
	public function isNodeCategoryBrowsing()
	{
		return $this->_v('entity.node.category.browse', false) && $this->_v('entity.repo.item', false);
	}

	/**
	 * The Node Prefix
	 * @return string
	 */
	public function nodePrefix()
	{
		return $this->_v('entity.node.prefix', null);
	}

	/**
	 * If to include trashed data
	 * @return boolean
	 */
	public function nodeIncludeTrashed()
	{
		return $this->_v('entity.node.trashed', false);
	}

	/**
	 * Return if URL has a request and if its fulfilled
	 * @return boolean
	 */
	public function checkUrlRequest()
	{
		if(!empty($this->_urlHasRequest))
		{
			return $this->_entity instanceof \Zbase\Interfaces\EntityInterface;
		}
		return true;
	}

	/**
	 * Check if Entity is Needed
	 * @return string
	 */
	public function isEntityNeeded()
	{
		return $this->_v('entity.name', false);
	}

	/**
	 * REturn the Parent Entity Object
	 *
	 * @return Entity
	 */
	public function parentEntityObject()
	{
		if(is_null($this->_parentEntityObject))
		{
			$this->_parentEntityObject = $this->_v('entity.parent', $this->_v('entity.entity', $this->entity()));
		}
		return $this->_parentEntityObject;
	}

	/**
	 * Return the entity
	 * @return Zbase\Widget\EntityInterface
	 */
	public function entity()
	{
		if(empty($this->hasEntity()))
		{
			return false;
		}
		if(is_null($this->_entity))
		{
			$entityName = $this->_v('entity.name', null);
			if(!empty($this->_nodeSupport))
			{
				$entityName = $this->getNodeNamespace() . '_' . strtolower($this->_nodeName);
			}
			if(!is_null($entityName))
			{
				$entity = $this->_v('entity.entity', null);
				if($entity instanceof \Zbase\Entity\Laravel\Entity)
				{
					$this->_entityObject = zbase()->entity($entityName, [], true);
					$this->_entity = $entity;
					return $this->_entity;
				}
				if($entity instanceof \Zbase\Post\PostInterface)
				{
					$this->_entityObject = $entity;
					$this->_entity = $entity;
					return $this->_entity;
				}
				$this->_entity = $this->_entityObject = $entity = zbase()->entity($entityName, [], true);
				$repoById = $this->_v('entity.repo.byId', null);
				$repoByFilter = $this->_v('entity.repo.byFilter', null);
				if(is_null($repoById))
				{
					$repoById = $this->_v('entity.repo.byAlphaId', null);
					if(!empty($repoById))
					{
						$byAlpha = true;
					}
					else
					{
						$repoById = $this->_v('entity.repo.bySlug', null);
						if(!empty($repoById))
						{
							$bySlug = true;
						}
					}
				}
				if(is_array($repoById))
				{
					if(!empty($repoById['route']))
					{
						$id = zbase_route_input($repoById['route']);
					}
					if($this->isNodeCategoryBrowsing())
					{
						$repoItemBySlug = $this->_v('entity.repo.item.bySlug', null);
						$repoItemByAlpha = $this->_v('entity.repo.item.byAlpha', null);
						$repoItemById = $this->_v('entity.repo.item.byId', null);
						/**
						 * Browse by category
						 * /CategorySlug/ - should show all category items
						 * /CategorySlug/ItemName - show item
						 *
						 * Module should have a "default" entry as the wildcard catchAll action
						 */
						if(!empty($repoItemByAlpha))
						{
							$itemRouteParameterName = $this->_v('entity.repo.item.byAlpha.route', null);
							$childAlphaId = zbase_route_input($itemRouteParameterName);
							if(!empty($childAlphaId))
							{
								$this->_childEntity = zbase()->entity($this->nodePrefix(), [], true)->repository()->byAlphaId($childAlphaId);
								if(!$this->_childEntity instanceof \Zbase\Entity\Laravel\Node\Node)
								{
									$this->setViewFile(zbase_view_file_contents('errors.404'));
									return zbase_abort(404);
								}
							}
						}
						if(!empty($repoItemBySlug))
						{
							$itemRouteParameterName = $this->_v('entity.repo.item.bySlug.route', null);
							$childAlphaId = zbase_route_input($itemRouteParameterName);
							if(!empty($childAlphaId))
							{
								$this->_childEntity = zbase()->entity($this->nodePrefix(), [], true)->repository()->bySlug($childAlphaId);
								if(!$this->_childEntity instanceof \Zbase\Entity\Laravel\Node\Node)
								{
									$this->setViewFile(zbase_view_file_contents('errors.404'));
									return zbase_abort(404);
								}
							}
						}
						if(!empty($repoItemById))
						{
							$itemRouteParameterName = $this->_v('entity.repo.item.byId.route', null);
							$childAlphaId = zbase_route_input($itemRouteParameterName);
							if(!empty($childAlphaId))
							{
								$this->_childEntity = zbase()->entity($this->nodePrefix(), [], true)->repository()->byId($childAlphaId);
								if(!$this->_childEntity instanceof \Zbase\Entity\Laravel\Node\Node)
								{
									$this->setViewFile(zbase_view_file_contents('errors.404'));
									return zbase_abort(404);
								}
							}
						}
					}
					if(!empty($repoById['request']) && zbase_is_post() == 'post')
					{
						$id = zbase_request_input($repoById['request']);
					}
					if(!empty($id))
					{
						$filters = $this->_v('entity.filter.query', []);
						$sorting = $this->_v('entity.sorting.query', []);
						$selects = ['*'];
						$joins = [];
						$this->_urlHasRequest = true;
						if($this->isNode())
						{
							zbase()->json()->addVariable('id', $id);
							if(!empty($repoById) && !empty($id) && empty($byAlpha) && empty($bySlug))
							{
								$filters['id'] = ['eq' => ['field' => $entity->getKeyName(), 'value' => $id]];
							}
							if($this->isCurrentUser())
							{
								$filters['user'] = ['eq' => ['field' => 'user_id', 'value' => zbase_auth_user()->id()]];
							}
							if($this->isPublic())
							{
								$filters['status'] = ['eq' => ['field' => 'status', 'value' => 2]];
							}
							if(!empty($byAlpha))
							{
								$filters['alpha'] = ['eq' => ['field' => 'alpha_id', 'value' => $id]];
							}
							if(!empty($bySlug))
							{
								$filters['slug'] = ['eq' => ['field' => 'slug', 'value' => $id]];
							}
							if(method_exists($entity, 'querySelects'))
							{
								$selects = $entity->querySelects($filters, ['widget' => $this]);
							}
							if(method_exists($entity, 'queryJoins'))
							{
								$joins = $entity->queryJoins($filters, $this->getRequestSorting(), ['widget' => $this]);
							}
							if(method_exists($entity, 'querySorting'))
							{
								$sorting = $entity->querySorting($sorting, $filters, ['widget' => $this]);
							}
							if(method_exists($entity, 'queryFilters'))
							{
								$filters = $entity->queryFilters($filters, $sorting, ['widget' => $this]);
							}
							/**
							 * Merge filters from widget configuration
							 * entity.filter.query
							 */
							$filters = array_merge($filters, $this->_v('entity.filter.query', []));
							$sorting = array_merge($sorting, $this->_v('entity.sorting.query', []));
							$action = $this->getAction();
							$debug = zbase_request_query_input('__widgetEntityDebug', false);
							if($this->isAdmin())
							{
								if($action == 'restore' || $action == 'ddelete')
								{
									return $this->_entity = $entity->repository()->onlyTrashed()->all($selects, $filters, $sorting, $joins)->first();
								}
							}
							else
							{
								if($entity->hasSoftDelete() && $this->isCurrentUser())
								{
									if($action == 'restore' || $action == 'ddelete')
									{
										return $this->_entity = $entity->repository()->onlyTrashed()->all($selects, $filters, $sorting, $joins)->first();
									}
									return $this->_entity = $entity->repository()->setDebug($debug)->withTrashed()->all($selects, $filters, $sorting, $joins)->first();
								}
							}
							return $this->_entity = $entity->repository()->setDebug($debug)->all($selects, $filters, $sorting, $joins)->first();
						}
					}
				}
				else
				{
					if(!empty($repoByFilter))
					{
						$filters = [];
						$sorting = [];
						$selects = ['*'];
						$joins = [];
						$singleRow = $this->_v('entity.singlerow', true);
						if($this->isCurrentUser())
						{
							$filters['user'] = ['eq' => ['field' => 'user_id', 'value' => zbase_auth_user()->id()]];
						}
						if($this->isPublic())
						{
							$filters['status'] = ['eq' => ['field' => 'status', 'value' => 2]];
						}
						$filters = array_merge($filters, $this->_v('entity.filter.query', []));
						$sorting = array_merge($sorting, $this->_v('entity.sorting.query', []));
						if(!empty($singleRow))
						{
							return $this->_entity = $entity->repository()->all($selects, $filters, $sorting, $joins)->first();
						}
						else
						{
							return $this->_entity = $entity->repository()->all($selects, $filters, $sorting, $joins);
						}
					}
				}
				$repoMethod = $this->_v('entity.method', null);
				if(!is_null($repoMethod))
				{
					return $this->_entity = $this->_entityObject->$repoMethod();
				}
				$this->_entityIsDefault = true;
				return $this->_entity = $this->_entityObject;
			}
		}
		return $this->_entity;
	}

	/**
	 * REturn the Entity Object
	 * @return EntityInterface
	 */
	public function entityObject()
	{
		return $this->_entityObject;
	}

	/**
	 * To String
	 * @return string
	 */
	public function __toString()
	{
		$content = parent::__toString();
		if(zbase_is_angular_template())
		{
			$serviceName = zbase_angular_module_servicename($this->getModule(), $this);
			$scopeName = zbase_angular_module_scopename($this->getModule(), $this);
			$content = str_replace('ANGULAR_WIDGET_MODULE_SERVICENAME', $serviceName, $content);
			$content = str_replace('ANGULAR_WIDGET_MODULE_SCOPENAME', $scopeName, $content);
		}
		return $content;
	}

}
