<?php

namespace Zbase\Post\Traits;

/**
 * Zbase-Entity Zbase Post Maker
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Entity.php
 * @project Zbase
 * @package Zbase/Entity/Traits
 */
use Zbase\Exceptions;
use Zbase\Post\Repository;
use Zbase\Entity\Laravel\User\User;

trait Post
{

	/**
	 *
	 * @var Repository
	 */
	protected $repository = null;

	/**
	 * The Post Owner
	 * @var User
	 */
	protected $postOwner = null;

	/**
	 * Default Rows Per Page
	 * @var integer
	 */
	protected $postRowsPerPage = 10;

	/**
	 * Post Id
	 * @var integer
	 */
	protected $id = null;

	/**
	 * Index of Messages
	 * @var array
	 */
	protected $messages = [];

	// <editor-fold defaultstate="collapsed" desc="Messages">
	/**
	 * Add a message/alert
	 * @param string $type The type of mesage
	 * @param string $msg the message
	 *
	 * @return $this
	 */
	public function postAddMessage($type, $msg)
	{
		if(!empty($msg))
		{
			if(isset($this->messages[$type]))
			{
				$this->messages[$type][] = $msg;
			}
			else
			{
				$this->messages[$type] = [$msg];
			}
		}
	}

	/**
	 * Return the Messages
	 * @return array
	 */
	public function postMessages()
	{
		return $this->messages;
	}

	// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc="Setter/Getters/Properties">
	/**
	 * Post Owner
	 * @return User
	 */
	public function postOwner()
	{
		if($this->postTableIsUserable())
		{
			if(method_exists($this, 'owner'))
			{
				return $this->postOwner = $this->owner();
			}
			if(is_null($this->postOwner))
			{
				$this->postOwner = false;
				if(!empty($this->user_id))
				{
					$user = zbase_user_byid($this->user_id);
					if($user instanceof User)
					{
						$this->postOwner = $user;
					}
				}
			}
			return $this->postOwner;
		}
		throw new Exceptions\PropertyNotFoundException('Post is not userable. No ownership found in ' . __CLASS__);
	}

	/**
	 * Set the Owner
	 * @param User $user
	 *
	 * @return $this
	 */
	public function setPostOwner(User $user)
	{
		$this->postOwner = $user;
		return $this;
	}

	/**
	 * Return the Post Owner Id
	 *
	 * @return integer
	 */
	public function postOwnerId()
	{
		if($this->postTableIsUserable())
		{
			if(method_exists($this, 'ownerId'))
			{
				return $this->ownerId();
			}
			return $this->user_id;
		}
		throw new Exceptions\PropertyNotFoundException('Post is not userable. No ownership found in ' . __CLASS__);
	}

	/**
	 * The Display Status Text with colors
	 * Uses Bootstrap
	 * @return string|Html
	 */
	public function postStatusText()
	{
		if($this->postTableIsStatusable())
		{
			if(method_exists($this, 'statusText'))
			{
				return $this->statusText;
			}
			if(property_exists($this, 'statusDisplayConfiguration'))
			{
				if(!empty($this->statusDisplayConfiguration[$this->status]))
				{
					$text = !empty($this->statusDisplayConfiguration[$this->status]['text']) ? $this->statusDisplayConfiguration[$this->status]['text'] : $this->status;
					$color = !empty($this->statusDisplayConfiguration[$this->status]['color']) ? $this->statusDisplayConfiguration[$this->status]['color'] : 'gray';
					$colorMap = [
						'red' => 'danger',
						'yellow' => 'warning',
						'green' => 'success',
						'gray' => 'default',
						'blue' => 'info',
					];
					if(array_key_exists($color, $colorMap))
					{
						$color = $colorMap[$color];
					}
					return '<span class="label label-' . $color . '">' . $text . '</span>';
				}
			}
			return $this->status;
		}
		return null;
	}

	/**
	 * Return the Post Id
	 *
	 * @return id
	 */
	public function postId()
	{
		if(method_exists($this, 'id'))
		{
			return $this->id = $this->id();
		}
		return $this->{$this->postTablePrimaryKey()};
	}

	/**
	 * Id that will be displayed to the User
	 *
	 * @return integer
	 */
	public function postDisplayId()
	{
		if(method_exists($this, 'displayId'))
		{
			return $this->displayId();
		}
		return $this->postId();
	}

	// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc="Manipulations">
	/**
	 * Create a new row
	 * @param array $data
	 */
	public function postRowCreate($data)
	{
		if(!empty($data))
		{
			if(method_exists($this, 'rowCreate'))
			{
				return $this->rowCreate($data);
			}
			return $this->insert($data);
		}
	}

	/**
	 * Update Row
	 * @param array $data
	 */
	public function postRowUpdate($data)
	{
		if(!empty($data))
		{
			if(method_exists($this, 'rowUpdate'))
			{
				return $this->rowUpdate($data);
			}
			return $this->fill($data)->save();
		}
	}

	/**
	 * Update Row
	 * @param array $data
	 */
	public function postRowDelete()
	{
		if(method_exists($this, 'rowDelete'))
		{
			return $this->rowDelete();
		}
		return $this->delete();
	}

	// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc="Widget: DatatableQueries">
	/**
	 * Datatable Filters
	 * @param array $filters
	 * @param WidgetInterface $datatable
	 *
	 * @return boolean
	 */
	public function postDatatableQueryFilters($filters, $datatable)
	{
		$queryFilters = $this->postQueryFilters($filters);
		if($datatable->isSearchable() && $datatable->isSearching())
		{
			$queryFilters = array_replace_recursive($queryFilters, $this->postSearchQueryFilters($datatable->getQueryKeyword()));
		}
		if(method_exists($this, 'datatableQueryFilters'))
		{
			return $this->datatableQueryFilters($filters, $queryFilters, $datatable);
		}
		return $queryFilters;
	}

	/**
	 * DataTable selected columns
	 * @param WidgetInterface $datatable
	 *
	 * @return array
	 */
	public function postDatatableQuerySelects($datatable)
	{
		$querySelects = $this->postQuerySelects();
		if(method_exists($this, 'datatableQuerySelects'))
		{
			return $this->datatableQuerySelects($querySelects, $datatable);
		}
		return $querySelects;
	}

	/**
	 * Return the Joinable Tables
	 *
	 * @param WidgetInterface $datatable
	 * @return array
	 */
	public function postDatatableQueryJoins($datatable)
	{
		$queryJoins = $this->postQueryJoins();
		if(method_exists($this, 'datatableQueryJoins'))
		{
			return $this->datatableQueryJoins($queryJoins, $datatable);
		}
		return $queryJoins;
	}

	/**
	 * Query Sorting
	 * @param array $sorting
	 * @param WidgetInterface $datatable
	 *
	 * @return array
	 */
	public function postDatatableQuerySorting($sorting, $datatable)
	{
		$tableName = $this->postTableName();
		$querySorting = $this->postQuerySorting();
		if(method_exists($this, 'datatableQuerySorting'))
		{
			return $this->datatableQuerySorting($sorting, $querySorting, $datatable);
		}
		return $querySorting;
	}

	// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc="Widget: Form">

	/**
	 * This Post page Property when selected
	 *
	 * @param WidgetInterface $widget The Widget
	 * @return void
	 */
	public function postPageProperty($widget)
	{
		if(method_exists($this, 'pageProperty'))
		{
			return $this->pageProperty($widget);
		}
//		$page = [];
//		$page['title'] = '<span class="userDisplayName' . $this->id() . '">' . $this->displayName() . '</span>' . $this->statusText();
//		$page['headTitle'] = $this->displayName();
//		zbase_view_page_details(['page' => $page]);
//		$breadcrumbs = [
//			['label' => 'Users', 'route' => ['name' => 'admin.users']],
//			['label' => '<span class="userDisplayName' . $this->id() . '">' . $this->displayName() . '</span>', 'link' => '#', 'title' => $this->displayName()],
//		];
//		zbase_view_breadcrumb($breadcrumbs);
	}

	/**
	 * Post Log
	 * @param string $log
	 */
	public function postLog($log)
	{

	}

	/**
	 * Widget entity interface.
	 * 	Data should be validated first before passing it here
	 * @param string $method post|get
	 * @param string $action the controller action
	 * @param array $data validated; assoc array
	 * @param Zbase\Widgets\Widget $widget
	 */
	public function widgetController($method, $action, $data, \Zbase\Widgets\Widget $widget)
	{
		return $this->postNodeWidgetController($method, $action, $data, $widget);
	}

	/**
	 * Widget entity interface.
	 * 	Data should be validated first before passing it here
	 * @param string $method post|get
	 * @param string $action the controller action
	 * @param array $data validated; assoc array
	 * @param Zbase\Widgets\Widget $widget
	 * @return boolean
	 */
	public function postNodeWidgetController($method, $action, $data, \Zbase\Widgets\Widget $widget)
	{
		if(method_exists($widget, 'nodeWidgetController'))
		{
			return $this->nodeWidgetController($method, $action, $data, $widget);
		}
		$methodName = zbase_string_camel_case('node_' . $method . '_' . $action . '_widget_controller');
		if(zbase_is_dev())
		{
			zbase()->json()->addVariable(__METHOD__, $methodName);
		}
		if(method_exists($this, $methodName))
		{
			return $this->{$methodName}($method, $action, $data, $widget);
		}
		try
		{
			if($action == 'index')
			{
				return;
			}
			if($action == 'create' && strtolower($method) == 'post')
			{
				zbase_db_transaction_start();
				$this->postRowCreate($data);
				$this->postLog($this->postTableName() . '_' . $action);
				zbase_db_transaction_commit();
				return true;
			}
			if($action == 'update' && strtolower($method) == 'post')
			{
				zbase_db_transaction_start();
				$this->postRowUpdate($data);
				$this->postLog($this->postTableName() . '_' . $action);
				zbase_db_transaction_commit();
				return true;
			}
			if($action == 'delete' && strtolower($method) == 'post')
			{
				zbase_db_transaction_start();
				$this->postRowDelete();
				$this->postLog($this->postTableName() . '_' . $action);
				zbase_db_transaction_commit();
				return true;
			}
		} catch (\Zbase\Exceptions\RuntimeException $e)
		{
			zbase_db_transaction_rollback();
		}
		return false;
	}

	// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc="Queries">

	/**
	 * Query Sorting
	 * @param array $sorting
	 * @param WidgetInterface $datatable
	 *
	 * @return array
	 */
	public function postQuerySorting()
	{
		$tableName = $this->postTableName();
		$querySorting = [$tableName . '.created_at' => 'DESC'];
		if(method_exists($this, 'querySorting'))
		{
			return $this->querySorting($querySorting);
		}
		return $querySorting;
	}

	/**
	 * Return the Joinable Tables
	 *
	 * @param WidgetInterface $datatable
	 * @return array
	 */
	public function postQueryJoins()
	{
		$tableName = $this->postTableName();
		$queryJoins[] = [
			'type' => 'join',
			'model' => 'users as users',
			'foreign_key' => $tableName . '.user_id',
			'local_key' => 'users.user_id',
		];
		if(method_exists($this, 'queryJoins'))
		{
			return $this->queryJoins($queryJoins);
		}
		return $queryJoins;
	}

	/**
	 * Return the Columns to Return
	 *
	 * @return aray
	 */
	public function postQuerySelects()
	{
		$tableName = $this->postTableName();
		if($this->postTableIsUserable())
		{
			$querySelects = [
				$tableName . '.*',
				'users.user_id',
				'users.name',
				'users.email',
				'users.username',
				'users.roles',
				'users.avatar',
			];
		}
		if(method_exists($this, 'querySelects'))
		{
			return $this->querySelects($querySelects);
		}
		return $querySelects;
	}

	/**
	 * Query Filters
	 * @param array $filters
	 *
	 * @return array
	 */
	public function postQueryFilters($filters)
	{
		$queryFilters = [];
		$tableName = $this->postTableName();
		if($this->postTableIsStatusable() && !empty($filters['public']))
		{
			$queryFilters[$tableName . '.status'] = self::STATUS_DISPLAY;
		}
		if($this->postTableIsStatusable() && !empty($filters['status']))
		{
			$queryFilters[$tableName . '.status'] = intval($filters['status']);
		}
		if($this->postTableIsUserable())
		{
			/**
			 * Query by the current User
			 */
			if(!empty($filters['currentUser']))
			{
				$queryFilters[$tableName . '.user_id'] = zbase_auth_user()->id();
			}
		}
		if(method_exists($this, 'queryFilters'))
		{
			return $this->queryFilters($queryFilters);
		}
		return $queryFilters;
	}

	/**
	 * Query by search keyword
	 *
	 * @param string|integer $keyword The keyword
	 * @return boolean
	 */
	public function postSearchQueryFilters($keyword)
	{
		$queryFilters = [];
		$queries = [];
		if(preg_match('/\,/', $query) > 0)
		{
			$queries = explode(',', $query);
		}
		else
		{
			$queries[] = $keyword;
		}
		foreach ($queries as $query)
		{
			/**
			 * Searching for Name
			 */
			if(preg_match('/name\:/', $query) > 0)
			{
				$queryFilters['users.name'] = function($q) use ($query){
					$name = trim(str_replace('name:', '', $query));
					return $q->orWhere('users.name', 'LIKE', '%' . $name . '%')
									->orWhere('users.username', 'LIKE', '%' . $name . '%');
					};
			}
			/**
			 * Searching for Email
			 */
			if(preg_match('/\@/', $query) > 0)
			{
				$queryFilters['users.email'] = [
					'eq' => [
						'field' => 'users.email',
						'value' => $query
					]
				];
			}
			/**
			 * Serachng by Username
			 */
			if(preg_match('/username\:/', $query) > 0)
			{
				$username = trim(str_replace('username:', '', $query));
				$queryFilters['users.username'] = [
					'like' => [
						'field' => 'users.username',
						'value' => '%' . $username . '%'
					]
				];
			}
			/**
			 * Searching By user Id
			 */
			if(preg_match('/userid\:/', $query) > 0)
			{
				$userId = intval(trim(str_replace('userid:', '', $query)));
				$queryFilters['users.user_id'] = [
					'eq' => [
						'field' => 'users.user_id',
						'value' => $userId
					]
				];
			}
		}
		if(method_exists($this, 'searchQueryFilters'))
		{
			return $this->searchQueryFilters($queryFilters);
		}
		return $queryFilters;
	}

	/**
	 * Post By ID
	 *
	 * @return PostInterface
	 */
	public function postById($postId)
	{
		$tableName = $this->postTableName();
		$cacheKey = zbase_cache_key(zbase_entity($tableName), 'byId_' . $postId);
		return zbase_cache($cacheKey, function() use ($postId, $cacheKey){
			return $this->repo()->byId($postId);
		}, [$tableName], $this->postCacheMinutes(), ['forceCache' => $this->postForceCache(), 'driver' => $this->postCacheDriver()]);
	}

	/**
	 * Return a User By attribute
	 * @param type $attribute
	 * @param type $value
	 */
	public function postBy($attribute, $value)
	{
		$tableName = $this->postTableName();
		$cacheKey = zbase_cache_key(zbase_entity($tableName), 'by_' . $attribute . '_' . $value);
		return zbase_cache($cacheKey, function() use ($attribute, $value){
			return $this->repo()->by($attribute, $value)->first();
		}, [$tableName], $this->postCacheMinutes(), ['forceCache' => $this->postCacheForce(), 'driver' => $this->postCacheDriver()]);
	}

	/**
	 * Return the NUmber of minutes to keep the cache
	 *
	 * @return integer
	 */
	public function postCacheMinutes()
	{
		if(method_exists($this, 'cacheMinutes'))
		{
			return $this->cacheMinutes();
		}
		return (60 * 24);
	}

	/**
	 * If to use ForceCaching
	 * @return boolean
	 */
	public function postCacheForce()
	{
		if(method_exists($this, 'cacheForce'))
		{
			return $this->cacheForce();
		}
		return true;
	}

	/**
	 * Return the Cache Driver
	 * @default to file
	 * @return string
	 */
	public function postCacheDriver()
	{
		if(method_exists($this, 'cacheDriver'))
		{
			return $this->cacheDriver();
		}
		return 'file';
	}

	/**
	 * Return the Rows Per Page
	 *
	 * @return integer
	 */
	public function postRowsPerPage()
	{
		if(method_exists($this, 'rowsPerPage'))
		{
			return $this->rowsPerPage();
		}
		if(property_exists($this, 'rowsPerPage'))
		{
			return $this->rowsPerPage;
		}
		return $this->postRowsPerPage;
	}

	/**
	 * @see $this->repository
	 * @return Repository
	 */
	public function repository()
	{
		if(!$this->repository instanceof Repository)
		{
			$this->repository = new Repository($this);
		}
		return $this->repository;
	}

	/**
	 * Proxy to self::repository()
	 * @return Repository
	 */
	public function repo()
	{
		return $this->repository();
	}

	// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc="TableMigrations and Setup">
	/**
	 * Return the Table Primary Key
	 *
	 * @return string
	 */
	public function postTablePrimaryKey()
	{
		if(property_exists($this, 'primaryKey'))
		{
			if(!is_null($this->primaryKey))
			{
				return $this->primaryKey;
			}
		}
		throw new Exceptions\PropertyNotFoundException('$primaryKey Property not found or is empty in ' . __CLASS__);
	}

	/**
	 * REturn the Post Table/Entity Name
	 *
	 * @return string
	 */
	public function postTableName()
	{
		if(property_exists($this, 'table'))
		{
			if(!is_null($this->table))
			{
				return $this->table;
			}
		}
		throw new Exceptions\PropertyNotFoundException('$table Property not found or is empty in ' . __CLASS__);
	}

	/**
	 * REturn the Post Table Description
	 *
	 * @return string
	 */
	public function postTableDescription()
	{
		if(property_exists($this, 'tableDescription'))
		{
			return $this->tableDescription;
		}
		return null;
	}

	/**
	 * Return the Table Columns based on the DB and not this class configuration
	 *
	 * @return array
	 */
	public function postTableColumns()
	{
		return \DB::getSchemaBuilder()->getColumnListing($this->postTableName());
	}

	/**
	 * Table Configuration
	 *
	 * @param array $postTableConfiguration
	 * @return string
	 */
	public function postTableConfigurations(array $postTableConfiguration)
	{
		$postTableConfiguration['table'] = [
			'name' => $this->postTableName(),
			'primaryKey' => $this->postTablePrimaryKey(),
			'description' => $this->postTableDescription()
		];
		if($this->postTableIsOptionable())
		{
			$postTableConfiguration['table']['optionable'] = true;
		}
		if($this->postTableIsTimestampable())
		{
			$postTableConfiguration['table']['timestamp'] = true;
		}
		if($this->postTableIsSluggable())
		{
			$postTableConfiguration['table']['sluggable'] = true;
		}
		if($this->postTableIsAlphable())
		{
			$postTableConfiguration['table']['alphable'] = true;
		}
		if($this->postTableIsUserable())
		{
			$postTableConfiguration['table']['userable'] = true;
		}
		if($this->postTableIsOrderable())
		{
			$postTableConfiguration['table']['orderable'] = true;
		}
		if($this->postTableIsStatusable())
		{
			$postTableConfiguration['table']['statusable'] = true;
		}
		if($this->postTableIsSoftableDelete())
		{
			$postTableConfiguration['table']['softDelete'] = true;
		}
		if($this->postTableIsPostParentable())
		{
			$postTableConfiguration['table']['postParentable'] = true;
		}
		if($this->postTableIsIpAddressable())
		{
			$postTableConfiguration['table']['ipAddress'] = true;
		}
		if($this->postTableIsRemarkable())
		{
			$postTableConfiguration['table']['remarkable'] = true;
		}
		if($this->postTableIsRoleable())
		{
			$postTableConfiguration['table']['roleable'] = true;
		}
		return $postTableConfiguration;
	}

	/**
	 * Table Column Configuration
	 * @param type $columns
	 */
	public function postTableColumnsConfiguration($columns)
	{
		if(method_exists($this, 'tableColumnsConfiguration'))
		{
			return $this->tableColumnsConfiguration($columns);
		}
		return $columns;
	}

	/**
	 * Return a table configuration
	 * @param type $index
	 */
	public function postTableConfiguration($property)
	{
		if(property_exists($this, 'tableConfiguration'))
		{
			if(is_array($this->tableConfiguration) && array_key_exists($property, $this->tableConfiguration))
			{
				return $this->tableConfiguration[$property];
			}
		}
		return false;
	}

	/**
	 * Table has column ip_address
	 * @return boolean
	 */
	public function postTableIsIpAddressable()
	{
		if(method_exists($this, 'tableIsIpAddressable'))
		{
			return $this->tableIsIpAddressable();
		}
		return $this->postTableConfiguration('ipAddressable');
	}

	/**
	 * Table has column remarks
	 * @return boolean
	 */
	public function postTableIsRemarkable()
	{
		if(method_exists($this, 'tableIsRemarkable'))
		{
			return $this->tableIsRemarkable();
		}
		return $this->postTableConfiguration('remarkable');
	}

	/**
	 * Table has column post_id as a reference to the Parent Table
	 * @return boolean
	 */
	public function postTableIsPostParentable()
	{
		if(method_exists($this, 'tableIsPostParentable'))
		{
			return $this->tableIsPostParentable();
		}
		return $this->postTableConfiguration('postParentable');
	}

	/**
	 * Table has column position
	 * @return boolean
	 */
	public function postTableIsOrderable()
	{
		if(method_exists($this, 'tableIsOrderable'))
		{
			return $this->tableIsOrderable();
		}
		return $this->postTableConfiguration('orderable');
	}

	/**
	 * Table has column status integer
	 * @return boolean
	 */
	public function postTableIsStatusable()
	{
		if(method_exists($this, 'tableIsStatusable'))
		{
			return $this->tableIsStatusable();
		}
		return $this->postTableConfiguration('statusable');
	}

	/**
	 * Table has column status integer
	 * @return boolean
	 */
	public function postTableIsSoftableDelete()
	{
		if(method_exists($this, 'tableIsSoftableDelete'))
		{
			return $this->tableIsSoftableDelete();
		}
		return $this->postTableConfiguration('softableDelete');
	}

	/**
	 * Table has column options
	 * @return boolean
	 */
	public function postTableIsOptionable()
	{
		if(method_exists($this, 'tableIsOptionable'))
		{
			return $this->tableIsOptionable();
		}
		return $this->postTableConfiguration('optionable');
	}

	/**
	 * Table has column created_at and updated_at
	 * @return boolean
	 */
	public function postTableIsTimestampable()
	{
		if(method_exists($this, 'tableIsTimestampable'))
		{
			return $this->tableIsTimestampable();
		}
		return $this->postTableConfiguration('timestampable');
	}

	/**
	 * Table has column alpha_id
	 * @return boolean
	 */
	public function postTableIsAlphable()
	{
		if(method_exists($this, 'tableIsAlphable'))
		{
			return $this->tableIsAlphable();
		}
		return $this->postTableConfiguration('alphable');
	}

	/**
	 * Table has column slug
	 * @return boolean
	 */
	public function postTableIsSluggable()
	{
		if(method_exists($this, 'tableIsSluggable'))
		{
			return $this->tableIsSluggable();
		}
		return $this->postTableConfiguration('sluggable');
	}

	/**
	 * Table has column user_id as the PostOwner
	 * @return boolean
	 */
	public function postTableIsUserable()
	{
		if(method_exists($this, 'tableIsUserable'))
		{
			return $this->tableIsUserable();
		}
		return $this->postTableConfiguration('userable');
	}

	/**
	 * Table has column roles for row CSV roles
	 * @return boolean
	 */
	public function postTableIsRoleable()
	{
		if(method_exists($this, 'tableIsRoleable'))
		{
			return $this->tableIsRoleable();
		}
		return $this->postTableConfiguration('roleable');
	}

	/**
	 * Has SoftDelete
	 *
	 * @return boolean
	 */
	public function hasSoftDelete()
	{
		return $this->postTableIsSoftableDelete();
	}

	// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc="Seeding">

	/**
	 * Seeding
	 *
	 * @return
	 */
	public function postTableSeeder()
	{
		if(method_exists($this, 'tableSeeder'))
		{
			$this->tableSeeder();
		}
		$rowsToCreate = 5;
		for ($x = 0; $x < $rowsToCreate; $x++)
		{
			$data = $this->postTableRowFactory();
			$this->postRowCreate($data);
		}
	}

	/**
	 * Create a dummy data for seeding
	 * @return array
	 */
	public function postTableRowFactory()
	{
		if(method_exists($this, 'tableRowFactory'))
		{
			return $this->tableRowFactory();
		}
		$data = [];
		return $data;
	}

	// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc="Caching">

	/**
	 * Clear Post Cache
	 *
	 * @return void
	 */
	public function clearPostCache()
	{
		if(method_exists($this, 'clearCache'))
		{
			$this->clearCache();
		}
	}

	/**
	 * Clear entity cache by Id
	 *
	 * @return void
	 */
	public function clearPostCacheById()
	{
		if(method_exists($this, 'clearCacheById'))
		{
			$this->clearCacheById();
		}
		$tableName = $this->postTableName();
		$cacheKey = zbase_cache_key(zbase_entity($tableName), 'byId_' . $this->postId());
		zbase_cache_remove($cacheKey, [$tableName], ['driver' => $this->postCacheDriver()]);
		$cacheKey = zbase_cache_key(zbase_entity($tableName), 'byId_' . $this->postId() . '_withtrashed');
		zbase_cache_remove($cacheKey, [$tableName], ['driver' => $this->postCacheDriver()]);
		$cacheKey = zbase_cache_key(zbase_entity($tableName), 'byId_' . $this->postId() . '_onlytrashed');
		zbase_cache_remove($cacheKey, [$tableName], ['driver' => $this->postCacheDriver()]);
	}

	/**
	 * Clear entity cache by Attributes/Value
	 *
	 * @return void
	 */
	public function clearPostCacheByTableColumns()
	{
		if(method_exists($this, 'clearCacheByTableColumns'))
		{
			$this->clearCacheByTableColumns();
		}
		$tableName = $this->postTableName();
		foreach ($this->postTableColumns() as $columnName => $columnConfig)
		{
			$cacheKey = zbase_cache_key(zbase_entity($tableName), 'by_' . $columnName . '_' . $this->{$columnName});
			zbase_cache_remove($cacheKey, [$tableName], ['driver' => $this->postCacheDriver()]);
		}
	}

	/**
	 * Events
	 */
	protected static function boot()
	{
		parent::boot();
		static::saved(function($post) {
			$post->clearPostCacheById();
			$post->clearPostCacheByTableColumns();
		});
		static::deleted(function($post) {
			$post->clearPostCacheById();
			$post->clearPostCacheByTableColumns();
		});
	}

	// </editor-fold>
}
