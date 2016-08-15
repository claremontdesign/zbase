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

	use \Illuminate\Database\Eloquent\SoftDeletes;

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
	 * Node has only a single image
	 * Single Image Mode: Node Image will be saved with this alphaId
	 * Multiple Image: with image property in table. with primary images
	 * @var boolean
	 */
	protected $singleImage = true;

	/**
	 * The Action Messages
	 * @var array
	 */
	protected $_actionMessages = [];

	protected static function boot()
	{
		parent::boot();
		static::saved(function($node) {
			$node->_updateAlphaId();
		});
	}

	/**
	 * Return the Node ID
	 * @return string
	 */

	/**
	 * Return a messages based on the Action made
	 * @param boolean $flag
	 * @param string $action create|update|delete|restore|ddelete
	 * @return array
	 */
	public function getActionMessages($action)
	{
		if(!empty($this->_actionMessages[$action]))
		{
			return $this->_actionMessages[$action];
		}
		return [];
	}

	/**
	 * Generate and Update Row Alpha ID
	 * @return void
	 */
	protected function _updateAlphaId()
	{
		if(!empty($this->node_id) && empty($this->alpha_id) && !empty($this->alphable))
		{
			$alphaId = zbase_generate_hash([$this->node_id, time()], $this->entityName);
			$rowByAlphaId = $this->repository()->byAlphaId($alphaId);
			if(!empty($rowByAlphaId))
			{
				$i = 1;
				while (!empty($this->repository()->byAlphaId($alphaId)))
				{
					$alphaId = zbase_generate_hash([$this->node_id, time(), $i++], $this->entityName);
				}
			}
			$this->alpha_id = $alphaId;
			$this->save();
		}
	}

	/**
	 * Set the Node Attributes
	 * @param array $data
	 */
	public function nodeAttributes($data)
	{
		if(isset($data['status']))
		{
			$this->status = $data['status'];
		}
		if(isset($data['content']))
		{
			$this->content = $data['content'];
		}
		if(isset($data['excerpt']))
		{
			$this->excerpt = $data['excerpt'];
		}
		if(!empty($data['title']))
		{
			$this->title = $data['title'];
		}
		if(!empty($data['slug']))
		{
			$this->slug = $data['slug'];
		}
	}

	/**
	 * Set the Title Attribute
	 * @param string $value
	 */
	public function setTitleAttribute($value)
	{
		$this->attributes['title'] = $value;
		$this->slug = $value;
	}

	// <editor-fold defaultstate="collapsed" desc="SLUG">
	/**
	 *
	 * @param string $value
	 */
	public function setSlugAttribute($value)
	{
		if(!empty($this->sluggable))
		{
			$slug = $this->createSlug($value);
			$rowsBySlug = $this->repository()->bySlug($slug);
			if(!empty($rowsBySlug))
			{
				$i = 1;
				while (!empty($this->repository()->bySlug($slug)))
				{
					$slug = $slug . '-' . $i++;
				}
			}
			$this->attributes['slug'] = $slug;
		}
		else
		{
			$this->attributes['slug'] = null;
		}
	}

	/**
	 * Create a Slug
	 * @param string $value
	 * @return string
	 */
	public function createSlug($value)
	{
		return zbase_string_slug($value);
	}

	// </editor-fold>

	/**
	 * Create a Log
	 * @param string $action
	 * @param array $options
	 * @return void
	 */
	public function log($action, $options = [])
	{

	}

	public function id()
	{
		return $this->node_id;
	}

	public function alphaId()
	{
		return $this->alpha_id;
	}

	/**
	 * Return the Collection
	 *
	 * @return Collection
	 */
	public function childrenFiles()
	{
		return zbase_entity(static::$nodeNamePrefix . '_files')->repo()->by('node_id', $this->node_id);
	}

	/**
	 * Return order status text
	 * @return string
	 */
	public function statusText()
	{
		$status = \Zbase\Ui\Data\DisplayStatus::class;
		$status = new $status(['value' => $this->status, 'id' => 'nodestatus' . $this->id()]);
		return $status->render();
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

	/**
	 * Node Alpha URL
	 * @return string
	 */
	public function slugUrl()
	{
		if($this->getBrowseCategory() instanceof Category)
		{
			return zbase_url_from_route($this->routeName, ['action' => $this->getBrowseCategory()->slug(), 'id' => $this->slug()]);
		}
		return zbase_url_from_route($this->routeName, ['action' => 'view', 'id' => $this->slug()]);
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
	 * Return the Slug
	 * @return string
	 */
	public function slug()
	{
		return $this->slug;
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
	 * return the image/file URL route name
	 * @return string
	 */
	public function routeName()
	{
		return static::$nodeNamePrefix . '_node';
	}

	/**
	 * Return this URL for Action
	 */
	public function actionUrl($action, $task = null)
	{
		$params = ['action' => $action, 'task' => $task];
		$params['id'] = $this->alphaId();
		if(zbase_is_back())
		{
			return zbase_url_from_route('admin.' . $this->routeName(), $params);
		}
		return zbase_url_from_route($this->routeName(), $params);
	}

	/**
	 * Return the URl
	 * @param type $name
	 * @param type $params
	 */
	public function url($name, $params = [])
	{
		return zbase_url_from_route($name, $params);
	}

	/**
	 * REturn the Image ID
	 * @return type
	 */
	public function imageId()
	{
		return $this->image;
	}

	/**
	 * Return the image url
	 *
	 * @return string
	 */
	public function imageUrl($options = [])
	{
		$fullImage = false;
		$params = ['node' => static::$nodeNamePrefix];
		if($this->singleImage)
		{
			$params['id'] = $this->alphaId();
		}
		else
		{
			$params['id'] = $this->imageId();
		}
		if(empty($options) || !empty($options['full']))
		{
			$fullImage = true;
		}
		$params['w'] = !empty($options['w']) ? $options['w'] : 150;
		$params['h'] = !empty($options['h']) ? $options['h'] : 0;
		$params['q'] = !empty($options['q']) ? $options['q'] : 80;
		if(!empty($options['thumbnail']))
		{
			$params['w'] = !empty($options['w']) ? $options['w'] : 200;
			$params['h'] = !empty($options['h']) ? $options['h'] : 0;
			$params['q'] = !empty($options['q']) ? $options['q'] : 80;
		}
		$params['ext'] = zbase_config_get('node.files.image.format', 'png');
		return zbase_url_from_route('nodeImage', $params);
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

	/**
	 * Update this object
	 * @param type $data
	 * @return \Zbase\Entity\Laravel\Node\Node
	 */
	public function updateNode($data)
	{
		$this->fill($data);
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
					$category = zbase_entity(static::$nodeNamePrefix . '_category')->repository()->byAlphaId($categoryId);
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
			$defaultImageFormat = zbase_config_get('node.files.image.format', 'png');
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
					$nodeFileObject->node_id = $this->id();
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
				$uploadedFile = zbase_file_upload_image($index, $folder, $filename, $defaultImageFormat);
			}
			if(!empty($uploadedFile) && zbase_file_exists($uploadedFile))
			{
				$nodeFileObject->is_primary = empty($nodeFiles) ? 1 : 0;
				$nodeFileObject->status = 2;
				$nodeFileObject->mimetype = zbase_file_mime_type($uploadedFile);
				$nodeFileObject->size = zbase_file_size($uploadedFile);
				$nodeFileObject->filename = basename($uploadedFile);
				$nodeFileObject->node_id = $this->id();
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
	 * Join Query
	 * @param array $filters Array of Filters
	 * @param array $sorting Array of Sorting
	 * @param array $options some options
	 * @return array
	 */
	public function querySearchFilters($filters, $options = [])
	{
		$keyword = $options['widget']->getSearchKeyword();
		if(!empty($keyword))
		{
			$filters['search'] = [
				'like' => [
					'field' => 'title',
					'value' => '%' . $keyword . '%'
				]
			];
		}
		return $filters;
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
				foreach ($filters as $fK => $fV)
				{
					if(!empty($fV['eq']) && !empty($fV['eq']['field']) && !empty($fV['eq']['value']))
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
					$this->_actionMessages[$action]['success'][] = _zt('Row "%title%" forever deleted!', ['%title%' => $this->title, '%id%' => $this->id()]);
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
	 * FAke images by Tags
	 * @param type $fileEntityName
	 * @param type $node
	 * @param type $name
	 * @param type $min
	 * @param type $max
	 */
	public static function fakeImagesByTags($fileEntityName, $node, $name, $min = 1, $max = 4)
	{
		$fileEntity = zbase_entity($fileEntityName);
		$files = $fileEntity::fakeImages(rand(1, 4), ['tags' => explode(' ', $name)]);
		if(!empty($files))
		{
			foreach ($files as $fUrl)
			{
				$node->uploadNodeFile($fUrl);
			}
		}
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
	// <editor-fold defaultstate="collapsed" desc="Table Definition">
	/**
	 * Return table minimum columns requirement
	 * @return array
	 */
	public static function nodeDefaultColumns()
	{
		$columns = [];
		$columns['user_id'] = [
			'filterable' => [
				'name' => 'userid',
				'enable' => true
			],
			'sortable' => [
				'name' => 'userid',
				'enable' => true
			],
			'hidden' => false,
			'length' => 16,
			'fillable' => true,
			'nullable' => true,
			'type' => 'integer',
			'index' => true,
			'comment' => 'Author UserId'
		];
		$columns['title'] = [
			'filterable' => [
				'name' => 'title',
				'enable' => true
			],
			'sortable' => [
				'name' => 'title',
				'enable' => true
			],
			'hidden' => false,
			'length' => 255,
			'fillable' => true,
			'nullable' => true,
			'type' => 'string',
			'index' => false,
			'comment' => 'Title'
		];
		$columns['content'] = [
			'filterable' => [
				'name' => 'content',
				'enable' => true
			],
			'hidden' => false,
			'fillable' => true,
			'nullable' => true,
			'type' => 'text',
			'comment' => 'Content'
		];
		$columns['excerpt'] = [
			'filterable' => [
				'name' => 'excerpt',
				'enable' => true
			],
			'hidden' => false,
			'fillable' => true,
			'nullable' => true,
			'type' => 'text',
			'comment' => 'Excerpt'
		];
		$columns['status'] = [
			'filterable' => [
				'name' => 'status',
				'enable' => true
			],
			'sortable' => [
				'name' => 'status',
				'enable' => true
			],
			'hidden' => false,
			'fillable' => false,
			'nullable' => true,
			'unsigned' => true,
			'type' => 'boolean',
			'index' => true,
			'comment' => 'Status'
		];
		$columns['image'] = [
			'hidden' => false,
			'fillable' => false,
			'nullable' => true,
			'type' => 'string',
			'comment' => 'Main image'
		];
		return $columns;
	}

	/**
	 * Return table minimum columns requirement
	 * @param array $columns Some columns
	 * @param array $entity Entity Configuration
	 * @return array
	 */
	public static function tableColumns($columns = [], $entity = [])
	{
		return $columns;
	}

	// </editor-fold>
}
