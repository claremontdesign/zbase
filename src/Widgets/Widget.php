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
	 * The Entity task add|update|delete|restore|ddelete|row|rows
	 * @var string
	 */
	protected $_entityTask = null;

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
				$repoById = $this->_v('entity.repo.byId', null);
				if(is_array($repoById))
				{
					if(!empty($repoById['route']))
					{
						$id = zbase_route_input($repoById['route']);
					}
					if(!empty($repoById['request']) && zbase_is_post() == 'post')
					{
						$id = zbase_request_input($repoById['request']);
					}
					if(!empty($id))
					{
						$entity = zbase_entity($entityName);
						if($entity->hasSoftDelete())
						{
							return $this->_entity = $entity->repository()->withTrashed()->byId($id);
						}
						return $this->_entity = $entity->repository()->byId($id);
					}
				}
				$repoMethod = $this->_v('entity.method', null);
				if(!is_null($repoMethod))
				{
					return $this->_entity = zbase_entity($entityName)->$repoMethod();
				}
				return $this->_entity = zbase_entity($entityName);
			}
		}
		return $this->_entity;
	}

}
