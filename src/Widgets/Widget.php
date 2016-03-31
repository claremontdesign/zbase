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
	 * Constructor
	 * @param string $widgetId
	 * @param array $configuration
	 */
	public function __construct($widgetId, $configuration)
	{
		$this->_widgetId = $widgetId;
		$this->setAttributes($configuration);
	}

	public function id()
	{
		return $this->_widgetId;
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
		return $this->entity();
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
	 * SEt the View Confioguration
	 * @param type $view
	 */
	public function setView($view)
	{
		if(!empty($view['file']))
		{
			$this->_viewFile = $view['file'];
		}
		return $this;
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
	 * Check if we are filtering for the current user
	 * @return boolean
	 */
	public function isCurrentUser()
	{
		return $this->_v('entity.filter.currentUser', false);
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
	 * Return the Child Entity
	 * @return Node
	 */
	public function getChildEntity()
	{
		return $this->_childEntity;
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
	 * Return the entity
	 * @return Zbase\Widget\EntityInterface
	 */
	public function entity()
	{
		if(is_null($this->_entity))
		{
			$entityName = $this->_v('entity.name', null);
			if(!is_null($entityName))
			{
				$this->_entityObject = $entity = zbase_entity($entityName, [], true);
				$repoById = $this->_v('entity.repo.byId', null);
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
						$childAlphaId = zbase_route_input('id');
						if(!empty($childAlphaId))
						{
							$this->_childEntity = zbase_entity($this->nodePrefix(), [], true)->repository()->byAlphaId($childAlphaId);
							if(!$this->_childEntity instanceof \Zbase\Entity\Laravel\Node\Node)
							{
								return zbase_abort(404);
							}
						}
					}
					if(!empty($repoById['request']) && zbase_is_post() == 'post')
					{
						$id = zbase_request_input($repoById['request']);
					}
					if(!empty($id))
					{
						$filters = [];
						$this->_urlHasRequest = true;
						if($this->isNode())
						{
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
								// return $this->_entity = $entity->repository()->byAlphaId($id);
							}
							if(!empty($bySlug))
							{
								$filters['slug'] = ['eq' => ['field' => 'slug', 'value' => $id]];
								// return $this->_entity = $entity->repository()->bySlug($id);
							}
							if($entity->hasSoftDelete() && $this->isCurrentUser())
							{
								return $this->_entity = $entity->repository()->withTrashed()->all(['*'], $filters)->first();
							}
							else
							{
								return $this->_entity = $entity->repository()->all(['*'], $filters)->first();
							}
						}
//						if($entity->hasSoftDelete() && !$this->isPublic())
//						{
//							if(!empty($byAlpha))
//							{
//								return $this->_entity = $entity->repository()->withTrashed()->byAlphaId($id);
//							}
//							if(!empty($bySlug))
//							{
//								return $this->_entity = $entity->repository()->withTrashed()->bySlug($id);
//							}
//							return $this->_entity = $entity->repository()->withTrashed()->byId($id);
//						}
//						if(!empty($byAlpha))
//						{
//							return $this->_entity = $entity->repository()->byAlphaId($id);
//						}
//						if(!empty($bySlug))
//						{
//							return $this->_entity = $entity->repository()->bySlug($id);
//						}
//						return $this->_entity = $entity->repository()->byId($id);
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

}
