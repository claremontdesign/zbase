<?php

namespace Zbase\Entity\Laravel\Node;

/**
 * Zbase-Node Entity
 *
 * Node Entity Base Model
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Node.php
 * @project Zbase
 * @package Zbase/Entity/Node
 */
use Zbase\Entity\Laravel\Entity as BaseEntity;
use Zbase\Widgets\EntityInterface as WidgetEntityInterface;
use Zbase\Interfaces;

class Node extends BaseEntity implements WidgetEntityInterface
{

	use \Zbase\Entity\Laravel\Node\Traits\Node;

	/**
	 * The Entity Name
	 * @var string
	 */
	protected $entityName = 'node';

	/**
	 * The set a node browsing category
	 * @var Category
	 */
	protected $browseCategory = null;

	/**
	 * Maximum Category
	 * @var integer
	 */
	protected $maxCategory = 1;

	/**
	 * The Node Name Prefix
	 * @var string
	 */
	public static $nodeNamePrefix = 'node';

	/**
	 * Route name
	 * @var type
	 */
	protected $routeName = 'node';

	protected static function boot()
	{
		parent::boot();
		static::saved(function($node) {
			$node->_updateAlphaId();
		});
	}

	/**
	 * Node Alpha URL
	 * @return string
	 */
	public function alphaUrl()
	{
		if($this->getBrowseCategory() instanceof Category)
		{
			return zbase_url_from_route($this->routeName, ['action' => $this->getBrowseCategory()->slug(), 'id' => $this->alphaId()]);
		}
		return zbase_url_from_route($this->routeName, ['action' => 'view', 'id' => $this->alphaId()]);
	}

	public function title()
	{
		if(!empty($this->title))
		{
			return $this->title;
		}
		return;
	}

	/**
	 * Node Canonical URL
	 * @return string
	 */
	public function canonicalUrl()
	{
		return '';
	}

	/**
	 * Set the Browsing category
	 * @param \Zbase\Entity\Laravel\Node\Category $categoryBrowse
	 * @return \Zbase\Entity\Laravel\Node\Node
	 */
	public function setBrowseCategory($categoryBrowse)
	{
		$this->browseCategory = $categoryBrowse;
		return $this;
	}

	/**
	 * Return the Browsing Category
	 * @return \Zbase\Entity\Laravel\Node\Category
	 */
	public function getBrowseCategory()
	{
		if($this->maxCategory == 1)
		{
			return $this->categories()->first();
		}
		return $this->browseCategory;
	}

	/**
	 * Check if current user is the post owner
	 * @return boolean
	 */
	public function isOwner()
	{
		return zbase_auth_has() && zbase_auth_user()->id() == $this->user_id;
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
	public function widgetController($method, $action, $data, \Zbase\Widgets\Widget $widget)
	{
		return $this->nodeWidgetController($method, $action, $data, $widget);
	}

	// <editor-fold defaultstate="collapsed" desc="Messages">
	/**
	 * Set Multiple messages
	 * @param array $data
	 */
	public function setMessages($data)
	{
		if(!empty($data['messages']))
		{
			foreach ($data['messages'] as $msg)
			{
				$message = !empty($msg['message']) ? $msg['message'] : null;
				$subject = !empty($msg['subject']) ? $msg['subject'] : null;
				$sender = !empty($msg['sender']) ? $msg['sender'] : null;
				$recipient = !empty($msg['recipient']) ? $msg['recipient'] : null;
				$options = !empty($msg['options']) ? $msg['options'] : null;
				$this->addMessage($message, $subject, $sender, $recipient, $options);
			}
		}
	}

	/**
	 * Add a Message
	 * @param string $message
	 * @param int|User $sender
	 * @param int|User $recipient
	 * @param array $options
	 * @return Message
	 */
	public function addMessage($message, $subject, $sender, $recipient, $options)
	{
		try
		{
			$options['node_id'] = $this->id();
			$options['node_prefix'] = static::$nodeNamePrefix;
			return zbase_entity('messages', [], true)->newMessage($message, $subject, $sender, $recipient, $options);
		} catch (\Zbase\Exceptions\RuntimeException $e)
		{
			if(zbase_is_dev())
			{
				dd($e);
			}
			zbase_abort(500);
		}
	}

	// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc="Node Creation">
	/**
	 * Set the Node Categories
	 * @param array $data array of data with index:category that is array of Category IDs
	 * @return void
	 */
	public function setNodeCategories($data)
	{
		if(!empty($data['category']))
		{
			if(is_array($data['category']))
			{
				$this->categories()->detach();
				foreach ($data['category'] as $categoryId)
				{
					$category = zbase_entity(static::$nodeNamePrefix . '_category')->repository()->byId($categoryId);
					if($category instanceof Nested)
					{
						$this->categories()->attach($category);
					}
				}
			}
		}
	}

	/**
	 * Upload a file for this node
	 * @param string $index The Upload file name/index or the URL to file to download and save
	 * @return void
	 */
	public function uploadNodeFile($index = 'file')
	{
		try
		{
			$folder = zbase_storage_path() . '/' . zbase_tag() . '/' . static::$nodeNamePrefix . '/' . $this->id() . '/';
			zbase_directory_check($folder, true);
			$nodeFileObject = zbase_entity(static::$nodeNamePrefix . '_files', [], true);
			$nodeFiles = $this->files()->get();
			if(preg_match('/http\:/', $index) || preg_match('/https\:/', $index))
			{
				// File given is a URL
				if($nodeFileObject->isUrlToFile())
				{
					$filename = zbase_file_name_from_file(basename($index), time(), true);
					$uploadedFile = zbase_file_download_from_url($index, $folder . $filename);
				}
				else
				{
					$nodeFiles = $this->files()->get();
					$nodeFileObject->is_primary = empty($nodeFiles) ? 1 : 0;
					$nodeFileObject->status = 2;
					$nodeFileObject->mimetype = null;
					$nodeFileObject->size = null;
					$nodeFileObject->filename = null;
					$nodeFileObject->url = $index;
					$nodeFileObject->save();
					$this->files()->save($nodeFileObject);
					return $nodeFileObject;
				}
			}
			if(zbase_file_exists($index))
			{
				$uploadedFile = $index;
				$filename = basename($index);
			}
			if(!empty($_FILES[$index]['name']))
			{
				$filename = zbase_file_name_from_file($_FILES[$index]['name'], time(), true);
				$uploadedFile = zbase_file_upload_image($index, $folder, $filename, 'png');
			}
			if(!empty($uploadedFile) && zbase_file_exists($uploadedFile))
			{
				$nodeFileObject->is_primary = empty($nodeFiles) ? 1 : 0;
				$nodeFileObject->status = 2;
				$nodeFileObject->mimetype = zbase_file_mime_type($uploadedFile);
				$nodeFileObject->size = zbase_file_size($uploadedFile);
				$nodeFileObject->filename = basename($uploadedFile);
				$nodeFileObject->save();
				$this->files()->save($nodeFileObject);
				return $nodeFileObject;
			}
		} catch (\Zbase\Exceptions\RuntimeException $e)
		{
			if(zbase_is_dev())
			{
				dd($e);
			}
			zbase_abort(500);
		}
	}

	/**
	 * Save a new model and return the instance.
	 *
	 * @param  array  $attributes
	 * @return static
	 */
	public static function create(array $attributes = [])
	{
		$model = zbase_entity(static::$nodeNamePrefix);
		$model->createNode($attributes);
		$model->save();
		return $model;
	}

	/**
	 * Create Node from Array
	 * @param array $data
	 * @return \Zbase\Entity\Laravel\Node\Node
	 */
	public function createNode($data)
	{
		if(!empty($data['user']))
		{
			if($data['user'] instanceof \Zbase\Entity\Laravel\User\User)
			{
				$user = $data['user'];
			}
			if(is_int((int) $data['user']))
			{
				$user = zbase_user_byid($data['user']);
			}
			if($user instanceof \Zbase\Entity\Laravel\User\User)
			{
				$user->equipments()->save($this);
			}
			unset($data['user']);
		}
		else
		{
			if(zbase_auth_has())
			{
				zbase_auth_user()->equipments()->save($this);
			}
		}
		$this->nodeAttributes($data);
		$this->status = 2;
		$this->save();
		$this->setNodeCategories($data);
		$this->setMessages($data);
		if(!empty($data['file_url']))
		{
			$this->uploadNodeFile($data['file_url']);
		}
		elseif(!empty($data['files_url']))
		{
			foreach ($data['files_url'] as $fUrl)
			{
				$this->uploadNodeFile($fUrl);
			}
		}
		else
		{
			$this->uploadNodeFile();
		}
		return $this;
	}

	// </editor-fold>
	/**
	 * Update this object
	 * @param type $data
	 * @return \Zbase\Entity\Laravel\Node\Node
	 */
	public function updateNode($data)
	{
		$this->nodeAttributes($data);
		$this->save();
		$this->setNodeCategories($data);
		if(!empty($data['file_url']))
		{
			$this->uploadNodeFile($data['file_url']);
		}
		else if(!empty($data['files_url']))
		{
			foreach ($data['files_url'] as $fUrl)
			{
				$this->uploadNodeFile($fUrl);
			}
		}
		else
		{
			$this->uploadNodeFile();
		}
	}

	// <editor-fold defaultstate="collapsed" desc="DataTable Widget Query Interface/Methods">

	/**
	 * Return SELECTs
	 * @param array $filters
	 */
	public function querySelects($filters)
	{
		return ['*'];
	}

	/**
	 * Join Query
	 * @param array $filters Array of Filters
	 * @param array $sorting Array of Sorting
	 * @param array $options some options
	 * @return array
	 */
	public function queryJoins($filters, $sorting = [], $options = [])
	{
		$joins = [];
		$category = !empty($filters['category']) ? true : false;
		/**
		 * There are categories in the filter,
		 * 	Join Node and Node-Category-Pivot table
		 * @see Zbase\Entity\Laravel\Traits\Joinable
		 */
		if(!empty($category))
		{
			$joins[] = [
				'type' => 'join',
				'model' => static::$nodeNamePrefix . '_category_pivot as pivot',
				'foreign_key' => static::$nodeNamePrefix . '.node_id',
				'local_key' => 'pivot.node_id',
			];
		}
		return $joins;
	}

	/**
	 * Filter Query
	 * @param array $filters Array of Filters
	 * @param array $sorting Array of Sorting
	 * @param array $options some options
	 * @return array
	 */
	public function queryFilters($filters, $sorting = [], $options = [])
	{
		$queryFilters = [];
		if(!empty($filters))
		{
			/**
			 * run through a given filters, possibly valid query filters
			 */
			if(!empty($filters))
			{
				foreach($filters as $fK => $fV)
				{
					if(!empty($fV['eq']) && !empty($fV['eq']['field'])  && !empty($fV['eq']['value']))
					{
						if(!preg_match('/' . static::$nodeNamePrefix . '\./', $fV['eq']['field']))
						{
							$fV['eq']['field'] = static::$nodeNamePrefix . '.' . $fV['eq']['field'];
						}
						$queryFilters[$fK] = $fV;
					}
				}
			}
			$isPublic = !empty($filters['public']) ? true : false;
			if(!empty($isPublic))
			{
				$queryFilters['status'] = [
					'eq' => [
						'field' => static::$nodeNamePrefix . '.status',
						'value' => 2
					]
				];
			}
			$currentUser = !empty($filters['currentUser']) ? true : false;
			if(!empty($currentUser))
			{
				$queryFilters['user'] = [
					'eq' => [
						'field' => static::$nodeNamePrefix . '.user_id',
						'value' => zbase_auth_user()->id()
					]
				];
			}
			if(!empty($filters['category']))
			{
				$categoryObject = zbase_entity(static::$nodeNamePrefix . '_category', [], true);
				/**
				 * We have category as a filter,
				 * 	Then search for the selected category
				 * @var strings[]|integers[]|EntityInterface[]
				 */
				$filterCategories = $filters['category'];
				/**
				 * We need category Ids To be able to do a cross-table-pivot search
				 * Examine the given filter if its an array of strings or integers or just a string or an integer
				 */
				$filterCategoryIds = [];
				if(is_array($filterCategories))
				{
					foreach ($filterCategories as $filterCategory)
					{
						if(!$filterCategory instanceof Interfaces\EntityInterface)
						{
							$filterCategory = $categoryObject::searchCategory(trim($filterCategory), $isPublic);
						}
						if($filterCategory instanceof Interfaces\EntityInterface && !$filterCategory->isRoot())
						{
							$filterCategoryIds[] = $filterCategory->id();
							continue;
						}
					}
				}
				else
				{
					$filterCategory = $categoryObject::searchCategory(trim($filterCategories), $isPublic);
					if($filterCategory instanceof Interfaces\EntityInterface && !$filterCategory->isRoot())
					{
						$filterCategoryIds[] = $filterCategory->id();
					}
				}
				if(!empty($filterCategoryIds))
				{
					$queryFilters['pivot.category_id'] = [
						'in' => [
							'field' => 'pivot.category_id',
							'values' => $filterCategoryIds
						]
					];
				}
				unset($filters['category']);
			}
			if(!empty($this->filterableColumns))
			{
				$processedFilters = [];
				foreach ($filters as $filterName => $filterValue)
				{
					if(empty($filterValue))
					{
						continue;
					}
					if(in_array($filterName, $processedFilters))
					{
						continue;
					}
					if(array_key_exists($filterName, $this->filterableColumns))
					{
						$column = $this->filterableColumns[$filterName]['column'];
						$filterType = $this->filterableColumns[$filterName]['filterType'];
						$options = $this->filterableColumns[$filterName]['options'];
						$columnType = $this->filterableColumns[$filterName]['type'];
						if($filterType == 'between')
						{
							$startValue = $filterValue;
							$endValue = null;
							if(preg_match('/\:/', $filterType))
							{
								$filterTypeEx = explode(':', $filterType);
								if(!empty($filterTypeEx[1]))
								{
									$endFilterName = $filterTypeEx[1];
									$processedFilters[] = $endFilterName;
									if(!empty($filters[$endFilterName]))
									{
										$endValue = $filters[$endFilterName];
									}
								}
							}
							if($columnType == 'timestamp')
							{
								$timestampFormat = zbase_data_get($options, 'timestamp.format', 'Y-m-d');
								$startValue = zbase_date_from_format($timestampFormat, $startValue);
								if($startValue instanceof \DateTime)
								{
									$startValue->hour = 0;
									$startValue->minute = 0;
									$startValue->second = 0;
								}
								if(empty($endValue))
								{
									$endValue = clone $startValue;
									$endValue->hour = 23;
									$endValue->minute = 59;
									$endValue->second = 59;
								}
								else
								{
									$endValue = zbase_date_from_format($timestampFormat, $endValue);
								}
								/**
								 * Argument is the other end of the between
								 */
								$queryFilters[$filterName] = [
									$filterType => [
										'field' => static::$nodeNamePrefix . '.' . $column,
										'from' => $startValue->format(DATE_FORMAT_DB),
										'to' => $endValue->format(DATE_FORMAT_DB)
									]
								];
							}
						}
						else
						{
							$queryFilters[$filterName] = [
								$filterType => [
									'field' => static::$nodeNamePrefix . '.' . $column,
									'value' => $filterValue
								]
							];
						}

						$processedFilters[] = $filterName;
					}
				}
			}
		}
		return $queryFilters;
	}

	/**
	 * Sorting Query
	 * @param array $sorting Array of Sorting
	 * @param array $filters Array of Filters
	 * @param array $options some options
	 * @return array
	 */
	public function querySorting($sorting, $filters = [], $options = [])
	{
		$sort = [];
		if(!empty($sorting))
		{
			if(!empty($this->sortableColumns))
			{
				foreach ($sorting as $sortName => $direction)
				{
					if(array_key_exists($sortName, $this->sortableColumns) && in_array(strtolower($direction), ['desc', 'asc']))
					{
						$column = $this->sortableColumns[$sortName]['column'];
						$sort[static::$nodeNamePrefix . '.' . $column] = strtoupper($direction);
					}
				}
			}
		}
		if(empty($sort))
		{
			$sort[] = [static::$nodeNamePrefix . '.created_at' => 'DESC'];
		}
		return $sort;
	}

	/**
	 * return the number of rows per page
	 * @return array
	 */
	public function getRowsPerPages()
	{
		return [10, 20, 30, 50, 100, 250, 500];
	}

	// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc="NodeWidgetController">
	/**
	 * Widget entity interface.
	 * 	Data should be validated first before passing it here
	 * @param string $method post|get
	 * @param string $action the controller action
	 * @param array $data validated; assoc array
	 * @param Zbase\Widgets\Widget $widget
	 * @return boolean
	 */
	public function nodeWidgetController($method, $action, $data, \Zbase\Widgets\Widget $widget)
	{
		zbase_db_transaction_start();
		try
		{
			if($action == 'create' && strtolower($method) == 'post')
			{
				$this->createNode($data);
				$this->log($action);
				zbase_db_transaction_commit();
				zbase_cache_flush([$this->getTable()]);
				$this->_actionMessages[$action]['success'][] = _zt('Created "%title%"!', ['%title%' => $this->title, '%id%' => $this->id()]);
				return true;
			}
			if($action == 'update' && strtolower($method) == 'post')
			{
				$this->updateNode($data);
				$this->log($action);
				zbase_db_transaction_commit();
				zbase_cache_flush([$this->getTable()]);
				$this->_actionMessages[$action]['success'][] = _zt('Saved "%title%"!', ['%title%' => $this->title, '%id%' => $this->id()]);
				return true;
			}
		} catch (\Zbase\Exceptions\RuntimeException $e)
		{
			zbase_db_transaction_rollback();
		}

		if($action == 'index')
		{

			return;
		}
		if($action == 'update')
		{
			if($this->hasSoftDelete() && $this->trashed())
			{
				$undoText = '<a href="' . $widget->getModule()->url(zbase_section(), ['action' => 'restore', 'id' => $this->alphaId()]) . '" title="Restore" class="undodelete">Restore</a>';
				$undoText .= ' | <a href="' . $widget->getModule()->url(zbase_section(), ['action' => 'ddelete', 'id' => $this->alphaId()]) . '" title="Delete Forever " class="ddeleteforever">Delete Forever</a>';
				$this->_actionMessages[$action]['warning'][] = _zt('Row "%title%" was trashed! %undo%', ['%title%' => $this->title, '%id%' => $this->id(), '%undo%' => $undoText]);
				return false;
			}
		}
		if($action == 'delete')
		{
			if($this->hasSoftDelete() && $this->trashed())
			{
				$undoText = '<a href="' . $widget->getModule()->url(zbase_section(), ['action' => 'restore', 'id' => $this->alphaId()]) . '" title="Restore" class="undodelete">Restore</a>';
				$undoText .= ' | <a href="' . $widget->getModule()->url(zbase_section(), ['action' => 'ddelete', 'id' => $this->alphaId()]) . '" title="Delete Forever " class="ddeleteforever">Delete Forever</a>';
				$this->_actionMessages[$action]['warning'][] = _zt('Row "%title%" was trashed! %undo%', ['%title%' => $this->title, '%id%' => $this->id(), '%undo%' => $undoText]);
				return false;
			}
		}
		try
		{
			if(strtolower($method) == 'post')
			{
				if($action == 'delete')
				{
					if($this->hasSoftDelete())
					{
						$this->deleted_at = zbase_date_now();
						$this->save();
					}
					else
					{
						$this->delete();
					}
					$this->log($action);
					zbase_db_transaction_commit();
					zbase_cache_flush([$this->getTable()]);
					$undoText = '';
					if(!empty($this->hasSoftDelete()))
					{
						$undoText = '<a href="' . $widget->getModule()->url(zbase_section(), ['action' => 'restore', 'id' => $this->alphaId()]) . '" title="Undo Delete" class="undodelete">Undo</a>';
						$undoText .= ' | <a href="' . $widget->getModule()->url(zbase_section(), ['action' => 'ddelete', 'id' => $this->alphaId()]) . '" title="Delete Forever " class="ddeleteforever">Delete Forever</a>';
					}
					$this->_actionMessages[$action]['success'][] = _zt('Deleted "%title%"! %undo%', ['%title%' => $this->title, '%id%' => $this->id(), '%undo%' => $undoText]);
					return true;
				}
			}
			if($action == 'restore')
			{
				if($this->trashed())
				{
					$this->restore();
					$this->log($action);
					zbase_db_transaction_commit();
					zbase_cache_flush([$this->getTable()]);
					$this->_actionMessages[$action]['success'][] = _zt('Row "%title%" was restored!', ['%title%' => $this->title, '%id%' => $this->id()]);
					return true;
				}
				$this->_actionMessages[$action]['error'][] = _zt('Error restoring "%title%". Row was not trashed.!', ['%title%' => $this->title, '%id%' => $this->id()]);
				return false;
			}
			if($action == 'ddelete')
			{
				if($this->trashed())
				{
					$this->forceDelete();
					$this->log($action);
					zbase_db_transaction_commit();
					zbase_cache_flush([$this->getTable()]);
					$this->_actionMessages[$action]['success'][] = _zt('Row "%title%" was removed from database!', ['%title%' => $this->title, '%id%' => $this->id()]);
					return true;
				}
				$this->_actionMessages[$action]['error'][] = _zt('Error restoring "%title%". Row was not trashed.!', ['%title%' => $this->title, '%id%' => $this->id()]);
				return false;
			}
		} catch (\Zbase\Exceptions\RuntimeException $e)
		{
			$this->_actionMessages[$action]['error'][] = _zt('There was a problem performing the request for "%title%".', ['%title%' => $this->title, '%id%' => $this->id()]);
			zbase_db_transaction_rollback();
		}
		return false;
	}

	// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc="SEEDING / Table Configuration">
	/**
	 * Return fake values
	 */
	public static function fakeValue()
	{
		$faker = \Faker\Factory::create();
		return [
			'title' => ucfirst($faker->words(rand(3, 10), true)),
			'content' => $faker->text(1000, true),
			'excerpt' => $faker->text(200),
			'status' => rand(0, 2),
		];
	}

	/**
	 * POST-Seeding event
	 */
	public static function seedingEventPost($entity = [])
	{

	}

	/**
	 * Seed
	 */
	public static function seeder($max = 15)
	{
		if(property_exists(__CLASS__, 'maxSeed'))
		{
			$max = static::$maxSeed;
		}
		if(!empty($max))
		{
			for ($x = 0; $x <= $max; $x++)
			{
				$data = static::fakeValue();
				$model = zbase_entity(static::$nodeNamePrefix, [], $x);
				$model->createNode($data)->save();
			}
		}
	}

	/**
	 * Table Relations
	 * @param array $relations Configuration default data
	 * @return array
	 */
	public static function tableRelations($relations = [])
	{
		$relations = [
			'owner' => [
				'entity' => 'user',
				'type' => 'onetomany',
				'class' => [
					'method' => 'owner'
				],
				'keys' => [
					'local' => 'user_id',
					'foreign' => 'user_id'
				],
			],
			static::$nodeNamePrefix . '_category' => [
				'entity' => static::$nodeNamePrefix . '_category',
				'type' => 'manytomany',
				'class' => [
					'method' => 'categories'
				],
				'pivot' => static::$nodeNamePrefix . '_category_pivot',
				'keys' => [
					'local' => 'category_id',
					'foreign' => 'node_id'
				],
			],
			static::$nodeNamePrefix . '_files' => [
				'entity' => static::$nodeNamePrefix . '_files',
				'type' => 'onetomany',
				'class' => [
					'method' => 'files'
				],
				'keys' => [
					'local' => 'node_id',
					'foreign' => 'node_id'
				],
			],
			static::$nodeNamePrefix . '_messages' => [
				'entity' => static::$nodeNamePrefix . '_messages',
				'type' => 'onetomany',
				'class' => [
					'method' => 'messages'
				],
				'keys' => [
					'local' => 'node_id',
					'foreign' => 'node_id'
				],
			],
		];
		return $relations;
	}

	/**
	 * Table Entity Configuration
	 * @param array $entity Configuration default data
	 * @return array
	 */
	public static function entityConfiguration($entity = [])
	{
		$entity['table'] = [
			'name' => static::$nodeNamePrefix,
			'primaryKey' => 'node_id',
			'timestamp' => true,
			'softDelete' => true,
			'alphaId' => true,
			'nodeable' => true,
			'optionable' => true,
			'sluggable' => true
		];
		return $entity;
	}

	// </editor-fold>
}
