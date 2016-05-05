<?php

namespace Zbase\Entity\Laravel\Node;

/**
 * Zbase-Node Nested
 *
 * Node Entity Base Model
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Nested.php
 * @project Nested
 * @package Zbase/Entity/Node
 */
use Zbase\Widgets\EntityInterface as WidgetEntityInterface;
use Zbase\Interfaces;

class Category extends Nested implements WidgetEntityInterface, Interfaces\EntityInterface
{

	/**
	 * Entity name as described in the config
	 * @var string
	 */
	protected $entityName = 'node_category';

	/**
	 * Is Node Selected
	 * @var boolean
	 */
	protected $selected = false;

	/**
	 * The Node Name Prefix
	 * @var string
	 */
	public static $nodeNamePrefix = 'node';

	/**
	 * The Route Name to use
	 * @var string
	 */
	protected $routeName = 'node';

	protected static function boot()
	{
		parent::boot();
		static::saved(function($node) {
			$node->_updateAlphaId();
		});
	}

	public function title()
	{
		return $this->title;
	}

	/**
	 * Return the ID
	 * @return integer
	 */
	public function id()
	{
		return $this->category_id;
	}

	/**
	 * Return Slug
	 * @return string
	 */
	public function slug()
	{
		return $this->slug;
	}

	/**
	 * Return the Root Node
	 * @return
	 */
	public function getRoot()
	{
		return self::root();
	}

	/**
	 *
	 * @param boolean $flag
	 * @return \Zbase\Entity\Laravel\Node\Category
	 */
	public function setSelected($flag)
	{
		$this->attributes['selected'] = $flag;
		//$this->selected = $flag;
		return $this;
	}

	/**
	 * Category Avatar
	 */
	public function avatar()
	{
		$avatar = $this->getDataOption('avatar', null);
		if(!empty($avatar))
		{
			return $avatar;
		}
		return false;
	}

	/**
	 * cATEGORY iMAGE uRL
	 * @return type
	 */
	public function avatarUrl($options = [])
	{
		$fullImage = false;
		$params = ['node' => static::$nodeNamePrefix];
		$params['id'] = $this->alphaId();
		if(empty($options) || !empty($options['full']))
		{
			$fullImage = true;
		}
		$params['w'] = !empty($options['w']) ? $options['w'] : 150;
		$params['h'] = !empty($options['h']) ? $options['h'] : 0;
		$params['q'] = !empty($options['q']) ? $options['q'] : 80;
		if(!empty($options['thumbnail']))
		{
			$params['w'] = !empty($options['w']) ? $options['w'] : $this->thWidth;
			$params['h'] = !empty($options['h']) ? $options['h'] : $this->thHeight;
			$params['q'] = !empty($options['q']) ? $options['q'] : $this->thQuality;
		}
		$params['ext'] = zbase_config_get('node.files.image.format', 'png');
		return zbase_url_from_route('nodeCategoryImage', $params);
	}

	/**
	 * Serve the File
	 * @param integer $width
	 * @param integer $height
	 * @param integer $quality Image Quality
	 * @param boolean $download If to download
	 * @return boolean
	 */
	public function serveImage($width, $height = null, $quality = null, $download = false)
	{
		$folder = zbase_storage_path() . '/' . zbase_tag() . '/' . static::$nodeNamePrefix . '_category' . '/' . $this->id() . '/';
		$path = $folder . $this->avatar();
		if(file_exists($path))
		{
			$cachedImage = \Image::cache(function($image) use ($width, $height, $path){
						if(empty($width))
						{
							$size = getimagesize($path);
							$width = $size[0];
							$height = $size[1];
						}
						if(!empty($width) && empty($height))
						{
							return $image->make($path)->resize($width, null, function($constraint)
						{
										$constraint->upsize();
										$constraint->aspectRatio();
						});
						}
						if(empty($width) && !empty($height))
						{
							return $image->make($path)->resize(null, $height, function($constraint)
						{
										$constraint->upsize();
										$constraint->aspectRatio();
						});
						}
						return $image->make($path)->resize($width, $height);
				});
			return \Response::make($cachedImage, 200, array('Content-Type' => $this->mimetype));
		}
		return false;
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
			return zbase_url_from_route('admin.node_' . $this->routeName . '_category', $params);
		}
		return zbase_url_from_route('node_' . static::$nodeNamePrefix . '_category', $params);
	}

	/**
	 *
	 * @return boolean
	 */
	public function isSelected()
	{
		return $this->attributes['selected'];
	}

	/**
	 * Search a CAtegory by string|integer
	 * @param int|string $category The category id or category name
	 * @param boolean $isPublic if to return a Public category
	 * @return Zbase\Entity\Laravel\Node\Category|null
	 */
	public static function searchCategory($category, $isPublic = true)
	{
		if(!$category instanceof Interfaces\EntityInterface)
		{
			$filter = [];
			if($isPublic)
			{
				$filter['status'] = 2;
			}
			if(is_numeric($category))
			{
				$filter['category_id'] = (int) $category;
			}
			else
			{
				/**
				 * It's a string, or name of the category
				 */
				$filter['title'] = trim($category);
			}
			$categoryNode = zbase_entity(static::$nodeNamePrefix . '_category', [], true)
					->repository()
					->all('*', $filter)
					->first();
		}
		else
		{
			$categoryNode = $category;
		}
		if($categoryNode instanceof Interfaces\EntityInterface)
		{
			return $categoryNode;
		}
		return null;
	}

	// <editor-fold defaultstate="collapsed" desc="Image Upload">
	/**
	 * Upload a file for this node
	 * @param string $index The Upload file name/index or the URL to file to download and save
	 * @return void
	 */
	public function uploadNodeFile($index = 'file')
	{
		try
		{
			$folder = zbase_storage_path() . '/' . zbase_tag() . '/' . static::$nodeNamePrefix . '_category' . '/' . $this->id() . '/';
			zbase_directory_check($folder, true);
			if(!empty($_FILES[$index]['name']))
			{
				$filename = $this->alphaId();//zbase_file_name_from_file($_FILES[$index]['name'], time(), true);
				$uploadedFile = zbase_file_upload_image($index, $folder, $filename, zbase_config_get('node.files.image.format', 'png'));
			}
			if(!empty($uploadedFile) && zbase_file_exists($uploadedFile))
			{
				$filename = basename($uploadedFile);
				$this->setDataOption('avatar', $filename);
				$this->save();
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

	// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc="NodeWidgetControll">
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
		if(($action == 'update' && strtolower($method) == 'post') || ($action == 'create' && strtolower($method) == 'post'))
		{
			$this->nodeAttributes($data);
		}
		zbase_db_transaction_start();
		try
		{
			$parent = !empty($data['category']) ? $data['category'] : null;
			$parentNodes = [];
			if(!empty($parent))
			{
				$currentParent = $this->ancestors()->first();
				if(is_array($parent))
				{
					foreach ($parent as $p)
					{
						if($parent instanceof Interfaces\EntityInterface)
						{
							$parentCategoryNode = $p;
						}
						else
						{
							$parentCategoryNode = $this->repository()->byAlphaId($p);
						}
						if($parentCategoryNode instanceof Interfaces\EntityInterface)
						{
							if($currentParent->id() != $parentCategoryNode->id())
							{
								$parentNodes[] = $parentCategoryNode;
							}
						}
						else
						{
							$this->_actionMessages[$action]['error'][] = _zt('There was a problem performing your request.');
							return false;
						}
					}
				}
			}
			if($action == 'create' && strtolower($method) == 'post')
			{
				if(empty($parentNodes))
				{
					$parentNodes[] = self::root();
				}
				$this->save();
				$this->_setParentNodes($parentNodes);
				$this->uploadNodeFile();
				$this->log($action);
				zbase_db_transaction_commit();
				zbase_cache_flush([$this->getTable()]);
				$this->_actionMessages[$action]['success'][] = _zt('Created "%title%"!', ['%title%' => $this->title, '%id%' => $this->id()]);
				return true;
			}
			if($action == 'update' && strtolower($method) == 'post')
			{
				$this->save();
				$this->_setParentNodes($parentNodes);
				$this->uploadNodeFile();
				$this->log($action);
				zbase_db_transaction_commit();
				zbase_cache_flush([$this->getTable()]);
				$this->_actionMessages[$action]['success'][] = _zt('Saved "%title%"!', ['%title%' => $this->title, '%id%' => $this->id()]);
				return true;
			}
			if($action == 'delete' && strtolower($method) == 'post')
			{
				$this->delete();
				$this->log($action);
				zbase_db_transaction_commit();
				zbase_cache_flush([$this->getTable()]);
				$undoText = '';
				if(!empty($this->hasSoftDelete()))
				{
					$undoText = '<a href="' . $widget->getModule()->url(zbase_section(), ['action' => 'restore', 'id' => $this->id()]) . '" title="Undo Delete" class="undodelete">Undo</a>.';
					$undoText .= ' | <a href="' . $widget->getModule()->url(zbase_section(), ['action' => 'ddelete', 'id' => $this->id()]) . '" title="Delete Forever " class="ddeleteforever">Delete Forever</a>';
				}
				$this->_actionMessages[$action]['success'][] = _zt('Deleted "%title%"! %undo%', ['%title%' => $this->title, '%id%' => $this->id(), '%undo%' => $undoText]);
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
				$undoText = '<a href="' . $widget->getModule()->url(zbase_section(), ['action' => 'restore', 'id' => $this->id()]) . '" title="Restore" class="undodelete">Restore</a>';
				$undoText .= ' | <a href="' . $widget->getModule()->url(zbase_section(), ['action' => 'ddelete', 'id' => $this->id()]) . '" title="Delete Forever " class="ddeleteforever">Delete Forever</a>';
				$this->_actionMessages[$action]['warning'][] = _zt('Row "%title%" was trashed! %undo%', ['%title%' => $this->title, '%id%' => $this->id(), '%undo%' => $undoText]);
				return false;
			}
		}
		if($action == 'delete')
		{
			if($this->hasSoftDelete() && $this->trashed())
			{
				$undoText = '<a href="' . $widget->getModule()->url(zbase_section(), ['action' => 'restore', 'id' => $this->id()]) . '" title="Restore" class="undodelete">Restore</a>';
				$undoText .= ' | <a href="' . $widget->getModule()->url(zbase_section(), ['action' => 'ddelete', 'id' => $this->id()]) . '" title="Delete Forever " class="ddeleteforever">Delete Forever</a>';
				$this->_actionMessages[$action]['warning'][] = _zt('Row "%title%" was trashed! %undo%', ['%title%' => $this->title, '%id%' => $this->id(), '%undo%' => $undoText]);
				return false;
			}
		}
		try
		{
			if($action == 'move')
			{

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

	/**
	 *
	 * @param string $value
	 */
	public function setSlugAttribute($value)
	{
		$this->attributes['slug'] = $value;
	}

	/**
	 * Generate and Update Row Alpha ID
	 * @return void
	 */
	protected function _updateAlphaId()
	{
		if(!empty($this->category_id) && empty($this->alpha_id) && !empty($this->alphable))
		{
			$this->alpha_id = zbase_generate_hash([$this->category_id, time()], $this->entityName);
			$this->save();
		}
	}

	/**
	 * SEt the Parents
	 * @param Nested[] $parentNodes
	 */
	protected function _setParentNodes($parentNodes)
	{
		if(!empty($parentNodes) && is_array($parentNodes))
		{
			foreach ($parentNodes as $p)
			{
				$this->makeChildOf($p);
			}
		}
	}

	// <editor-fold defaultstate="collapsed" desc="Seeding/TableConfiguration">
	/**
	 * Generate FAke Values
	 */
	public static function fakeValues()
	{
		$categories = [
			['title' => 'Root Category', 'status' => 2, 'children' => [
					['title' => 'TV & Home Theather', 'status' => rand(0, 2)],
					['title' => 'Tablets & E-Readers', 'status' => rand(0, 2)],
					['title' => 'Computers', 'status' => rand(0, 2), 'children' => [
							['title' => 'Laptops', 'status' => rand(0, 2), 'children' => [
									['title' => 'PC Laptops', 'status' => rand(0, 2)],
									['title' => 'Macbooks (Air/Pro)', 'status' => rand(0, 2)]
								]
							],
							['title' => 'Desktops', 'status' => rand(0, 2), 'children' => [
									['title' => 'Towers Only', 'status' => rand(0, 2)],
									['title' => 'Desktop Packages', 'status' => rand(0, 2)],
									['title' => 'All-in-One Computers', 'status' => rand(0, 2)],
									['title' => 'Gaming Desktops', 'status' => rand(0, 2)]
								]
							]
						]
					],
					['title' => 'Cell Phones', 'status' => rand(0, 2)]
				]
			]
		];
		parent::buildTree($categories);
	}

	/**
	 * POST-Seeding event
	 * @param array $entity Entity Configuration
	 */
	public static function seedingEventPost($entity = [])
	{

	}

	/**
	 * Seeder
	 */
	public static function seeder()
	{

	}

	/**
	 * Return fake values
	 */
	public static function fakeValue()
	{
		$faker = \Faker\Factory::create();
		return [
			'title' => ucfirst($faker->words(3, true)),
			'status' => rand(0, 2),
		];
	}

	/**
	 * Table Entity Configuration
	 * @param array $entity Configuration default data
	 * @return array
	 */
	public static function entityConfiguration($entity = [])
	{
		if(!empty($entity['pivot']))
		{
			$entity['table'] = [
				'name' => static::$nodeNamePrefix . '_category_pivot',
				'description' => ucfirst(static::$nodeNamePrefix) . '-Categories Pivot Table',
				'pivotable' => ['entity' => static::$nodeNamePrefix, 'nested' => static::$nodeNamePrefix . '_category'],
				'orderable' => true,
				'columns' => [
					'node_id' => [
						'length' => 16,
						'hidden' => false,
						'fillable' => true,
						'type' => 'integer',
						'unsigned' => true,
						'foreign' => [
							'table' => static::$nodeNamePrefix,
							'column' => 'node_id',
							'onDelete' => 'cascade'
						],
						'comment' => 'Node ID'
					],
					'category_id' => [
						'length' => 16,
						'hidden' => false,
						'fillable' => true,
						'type' => 'integer',
						'index' => true,
						'unique' => false,
						'unsigned' => true,
						'foreign' => [
							'table' => static::$nodeNamePrefix . '_category',
							'column' => 'category_id',
							'onDelete' => 'cascade'
						],
						'comment' => 'Category ID'
					]
				],
			];
			return $entity;
		}
		$entity['table'] = [
			'name' => static::$nodeNamePrefix . '_category',
			'primaryKey' => 'category_id',
			'timestamp' => true,
			'softDelete' => true,
			'alphaId' => true,
			'nodeable' => true,
			'nesteable' => true,
			'optionable' => true,
			'sluggable' => true
		];
		return $entity;
	}

	/**
	 * Table Relations
	 * @param array $relations Configuration default data
	 * @return array
	 */
	public static function tableRelations($relations = [])
	{
		$relations = [
			static::$nodeNamePrefix => [
				'entity' => static::$nodeNamePrefix,
				'type' => 'manytomany',
				'class' => [
					'method' => static::$nodeNamePrefix
				],
				'pivot' => static::$nodeNamePrefix . '_category_pivot',
				'keys' => [
					'local' => 'node_id',
					'foreign' => 'category_id'
				],
			],
		];
		return $relations;
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
