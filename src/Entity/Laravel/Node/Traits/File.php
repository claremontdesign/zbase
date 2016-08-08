<?php

namespace Zbase\Entity\Laravel\Node\Traits;

/**
 * Zbase-Entity Zbase Entity Maker
 *
 * Filterable Entity
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Entity.php
 * @project Zbase
 * @package Zbase/Entity/Traits
 */
trait File
{

	/**
	 * The Action Messages
	 * @var array
	 */
	protected $_actionMessages = [];
	protected $joinUserTable = true;
	protected $parentObject = null;
	protected $parentObjectIndexId = 'node_id';

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
	 * Update other primary to 0
	 * before updating a new primary
	 *
	 * @return
	 */
	public function updatePrimary($selectedAlphaId)
	{
		zbase_entity($this->entityName)->repo()->update(['is_primary' => 0], ['node_id' => ['eq' => ['field' => 'node_id', 'value' => $this->parentObject()->id()]]]);
		zbase_entity($this->entityName)->repo()->update(['is_primary' => 1], ['node_id' => ['eq' => ['field' => 'alpha_id', 'value' => $selectedAlphaId]]]);
	}

	/**
	 * Receive the File/Image
	 *
	 * @param \Zbase\Entity\Laravel\Entity $parentObject The Parent
	 */
	public function receiveFile(\Zbase\Entity\Laravel\Entity $parentObject)
	{
		try
		{
			$index = 'file';
			$entityName = $this->entityName;
			$defaultImageFormat = zbase_config_get('node.files.image.format', 'png');
			$folder = zbase_storage_path() . '/' . zbase_tag() . '/' . $this->actionUrlRouteName() . '/' . $parentObject->id() . '/';
			zbase_directory_check($folder, true);
			$nodeFileObject = zbase_entity($entityName, [], true);
			$nodeFiles = $parentObject->childrenFiles();
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
					$this->is_primary = empty($nodeFiles) ? 1 : 0;
					$this->status = 2;
					$this->mimetype = null;
					$this->size = null;
					$this->filename = null;
					$this->url = $index;
					$this->{$this->parentObjectIndexId} = $parentObject->id();
					$this->user_id = zbase_auth_has() ? zbase_auth_user()->id() : null;
					$this->save();
					return true;
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
				$this->is_primary = empty($nodeFiles) ? 1 : 0;
				$this->status = 2;
				$this->user_id = zbase_auth_has() ? zbase_auth_user()->id() : null;
				$this->mimetype = zbase_file_mime_type($uploadedFile);
				$this->size = zbase_file_size($uploadedFile);
				$this->filename = basename($uploadedFile);
				$this->{$this->parentObjectIndexId} = $parentObject->id();
				if(empty($nodeFiles))
				{
					$parentObject->image = $this->filename;
					$parentObject->save();
				}
				$this->save();
				return true;
			}
		} catch (\Zbase\Exceptions\RuntimeException $e)
		{
			if(zbase_is_dev())
			{
				dd($e);
			}
			zbase_abort(500);
		}
		return false;
	}

	// <editor-fold defaultstate="collapsed" desc="nodeWidgetController">
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
			if(strtolower($method) == 'post')
			{
				if($action == 'file-update')
				{
					$action = 'update';
				}
			}
			if(strtolower($method) == 'post' && zbase_request_is_upload())
			{
				$parentObject = $widget->parentEntityObject();
				if(empty($parentObject))
				{
					return false;
				}
				$this->receiveFile($parentObject);
				$action = 'create';
			}
			if(strtolower($method) == 'post')
			{
				if(!empty($data))
				{
					$newData = $data;
					$data = [];
					foreach ($newData as $dK => $dV)
					{
						$data[str_replace('nodefile', '', $dK)] = $dV;
					}
				}
			}
			if($action == 'create' && strtolower($method) == 'post')
			{
				if(isset($data['title']))
				{
					$this->title = $data['title'];
				}
				if(isset($data['excerpt']))
				{
					$this->excerpt = $data['excerpt'];
				}
				$this->save();
				$this->log($action);
				zbase_db_transaction_commit();
				zbase_cache_flush([$this->getTable()]);
				$this->_actionMessages[$action]['success'][] = _zt('File Uploaded!', ['%title%' => $this->title, '%id%' => $this->id()]);
				return true;
			}
			if($action == 'update' && strtolower($method) == 'post')
			{
				foreach ($data as $k => $v)
				{
					unset($data[$k]);
					$data[str_replace('nodefile', '', $k)] = $v;
				}
				if(!empty($data['status']))
				{
					$this->status = 2;
					unset($data['status']);
				}
				else
				{
					$this->status = 0;
				}
				if(isset($data['title']))
				{
					$this->title = $data['title'];
				}
				if(isset($data['excerpt']))
				{
					$this->excerpt = $data['excerpt'];
				}
				if(isset($data['primary']) && !empty($data['primary']))
				{
					$this->updatePrimary($data['primary']);
					$this->parentObject()->image = $this->alphaId();
				}
				else
				{
					$this->parentObject()->image = null;
				}
				$this->parentObject()->save();
				$this->save();
				$this->log($action);
				zbase_db_transaction_commit();
				zbase_cache_flush([$this->getTable()]);
				$this->_actionMessages[$action]['success'][] = _zt('Saved', ['%title%' => $this->title, '%id%' => $this->id()]);
				return true;
			}
			if($action == 'primary' && strtolower($method) == 'post')
			{
				$this->log($action);
				zbase_db_transaction_commit();
				zbase_cache_flush([$this->getTable()]);
				$this->_actionMessages[$action]['success'][] = _zt('Saved', ['%title%' => $this->title, '%id%' => $this->id()]);
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
			if($action == 'delete')
			{
				if($this->hasSoftDelete())
				{
					$this->deleted_at = zbase_date_now();
					$this->save();
				}
				else
				{
					$this->_deleteFile();
					$this->delete();
				}
				$this->log($action);
				zbase_db_transaction_commit();
				zbase_cache_flush([$this->getTable()]);
				$undoText = '';
				if(!empty($this->hasSoftDelete()))
				{
					$undoText = '<a href="' . $widget->getModule()->url(zbase_section(), ['action' => 'restore', 'id' => $this->id()]) . '" title="Undo Delete" class="undodelete">Undo</a>.';
					$undoText .= ' | <a href="' . $widget->getModule()->url(zbase_section(), ['action' => 'ddelete', 'id' => $this->id()]) . '" title="Delete Forever " class="ddeleteforever">Delete Forever</a>';
				}
				$this->_actionMessages[$action]['success'][] = _zt('File Deleted! %undo%', ['%title%' => $this->title, '%id%' => $this->id(), '%undo%' => $undoText]);
				return true;
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
					$this->_deleteFile();
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
	// <editor-fold defaultstate="collapsed" desc="Datatable Queries">

	/**
	 * Sorting Query
	 * @param array $sorting Array of Sorting
	 * @param array $filters Array of Filters
	 * @param array $options some options
	 * @return array
	 */
	public function querySorting($sorting, $filters = [], $options = [])
	{
		$entityName = $this->entityName;
		$sort = [$entityName . '.created_at' => 'ASC'];
		return $sort;
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
		if(!empty($this->joinUserTable))
		{
			$entityName = $this->entityName;
			$joins[] = [
				'type' => 'join',
				'model' => 'users as user',
				'foreign_key' => $entityName . '.user_id',
				'local_key' => 'user.user_id',
			];
			$joins[] = [
				'type' => 'join',
				'model' => 'users_profile as user_profile',
				'foreign_key' => $entityName . '.user_id',
				'local_key' => 'user_profile.user_id',
			];
		}
		return $joins;
	}

	/**
	 * REturn selects
	 * @param array $filters
	 * @return array
	 */
	public function querySelects($filters, $options = [])
	{
		$entityName = $this->entityName;
		$selects = [];
		$selects[] = $entityName . '.*';
		if(!empty($this->joinUserTable))
		{
			$selects[] = 'user.email as user_email';
			$selects[] = 'user.name as user_name';
			$selects[] = 'user.username as user_username';
			$selects[] = 'user_profile.avatar as user_avatar';
		}
		return $selects;
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
		if(!empty($filters))
		{
			$entityName = $this->entityName;
			$queryFilters = [];
			foreach ($filters as $fId => $fValue)
			{
				if(!empty($fValue))
				{
					foreach ($fValue as $fQ => $fV)
					{
						$field = $fV['field'];
						if(preg_match('/\./', $field) == 0)
						{
							$field = $entityName . '.' . $field;
						}
						$queryFilters[$fId][$fQ]['field'] = $field;
						$queryFilters[$fId][$fQ]['value'] = $fV['value'];
					}
				}
			}
		}
		return $queryFilters;
	}

	// </editor-fold>

	/**
	 * Return the Parent Object
	 *
	 * @return Entity
	 */
	public function parentObject()
	{
		if(is_null($this->parentObject))
		{
			$this->parentObject = zbase_entity(static::$nodeNamePrefix . '_node')->repo()->byId($this->{$this->parentObjectIndexId});
		}
		return $this->parentObject;
	}

	/**
	 * Set the Node Attributes
	 * @param array $data
	 */
	public function nodeAttributes($data)
	{
		if(!empty($data['title']))
		{
			$this->title = $data['title'];
		}
	}

	/**
	 * Set the Title Attribute
	 * @param string $value
	 */
	public function setTitleAttribute($value)
	{
		$this->attributes['title'] = $value;
	}

	/**
	 * Return only Status Public files
	 * @param type $query
	 * @return query
	 */
	public function scopeStatusPublic($query)
	{
		return $query->where('status', '=', 2);
	}

	protected static function boot()
	{
		parent::boot();
		static::saved(function($node) {
			$node->_updateAlphaId();
		});
	}

	/**
	 * Generate and Update Row Alpha ID
	 * @return void
	 */
	protected function _updateAlphaId()
	{
		if(!empty($this->file_id) && empty($this->alpha_id) && !empty($this->alphable))
		{
			$alphaId = zbase_generate_hash([$this->file_id, time()], $this->entityName);
			$i = 1;
			while ($this->fetchByAlphaId($alphaId) > 0)
			{
				$alphaId = zbase_generate_hash([time(), $i++, $this->file_id], $this->entityName);
			}
			$this->alpha_id = $alphaId;
			$this->save();
		}
	}

	/**
	 * Fetch a Row By AlphaId
	 * @param string $alphaId
	 * @return Collection[]
	 */
	public function fetchByAlphaId($alphaId)
	{
		return $this->repository()->byAlphaId($alphaId);
	}

	/**
	 * Return primary Files
	 * @param type $query
	 * @return query
	 */
	public function scopeIsPrimary($query)
	{
		return $query->where('is_primary', '=', 1);
	}

	/**
	 * Alpha URL
	 * @param array $options
	 */
	public function alphaUrl($options = [])
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
		return zbase_url_from_route($this->urlRouteName(), $params);
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
			return zbase_url_from_route('admin.' . $this->actionUrlRouteName(), $params);
		}
		return zbase_url_from_route($this->actionUrlRouteName(), $params);
	}

	/**
	 * The Action URL Route Name
	 * @return string
	 */
	public function actionUrlRouteName()
	{
		return 'node_' . static::$nodeNamePrefix . '_files';
	}

	/**
	 * return the image/file URL route name
	 * @return string
	 */
	public function urlRouteName()
	{
		return 'nodeImage';
	}

	public function alphaId()
	{
		return $this->alpha_id;
	}

	public function id()
	{
		return $this->file_id;
	}

	public function title()
	{
		return $this->title;
	}

	public function caption()
	{
		return $this->excerpt;
	}

	public function isPrimary()
	{
		return (boolean) $this->is_primary;
	}

	public function isDisplayed()
	{
		return $this->status == 2;
	}

	/**
	 * Check if img is a URL String
	 * @return string
	 */
	public function isUrl()
	{
		return !empty($this->url);
	}

	/**
	 * What to do when we receive a URL,
	 * save to file?
	 * @return boolean
	 */
	public function isUrlToFile()
	{
		return $this->urlToFile;
	}

	/**
	 * Return the Folder Path
	 * @return string
	 */
	public function folder()
	{
		$path = zbase_storage_path() . '/' . zbase_tag() . '/' . static::$nodeNamePrefix . '/';
		if(!empty($this->node_id))
		{
			$path .= $this->node_id . '/';
		}
		return $path;
	}

	/**
	 * Return the Path tot file
	 * @return string
	 */
	public function getFilePath()
	{
		$folder = $this->folder();
		$filename = $this->filename;
		return $folder . $filename;
	}

	/**
	 * Delete the file
	 * @return boolean
	 */
	protected function _deleteFile()
	{
		if($this->isUrl())
		{
			return true;
		}
		$path = $this->getFilePath();
		if(file_exists($path))
		{
			return unlink($path);
		}
		return false;
	}

	/**
	 * Serve the File
	 * @param integer $width
	 * @param integer $height
	 * @param integer $quality Image Quality
	 * @param boolean $download If to download
	 * @param boolean $notFound DisplayNot Found image
	 * @return boolean
	 */
	public function serveImage($width, $height = null, $quality = null, $download = false, $notFound = false)
	{
		if($this->isUrl())
		{
			$defaultImageFormat = zbase_config_get('node.files.image.format', 'png');
			$path = $this->url;
			$cachedImage = $this->getImageByPath($path, $width, $height, $quality, $download);
			return \Response::make($cachedImage, 200, array('Content-Type' => 'image/' . $defaultImageFormat));
		}
		else
		{
			$path = $this->getFilePath();
			if(file_exists($path))
			{
				$cachedImage = $this->getImageByPath($path, $width, $height, $quality, $download);
				return \Response::make($cachedImage, 200, array('Content-Type' => $this->mimetype));
			}
			$path = 'http://placehold.it/' . $width . 'x' . $height . '?textsize=25&text=ImageNotFound';
			$image = $this->getImageByPath($path, $width, $height, $quality, $download);
			return \Response::make($image, 200, array('Content-Type' => $this->mimetype));
		}
		return false;
	}

	/**
	 * Return an Image By Path
	 * @param type $path
	 * @return type
	 */
	public function getImageByPath($path, $width, $height, $quality, $download)
	{
		return \Image::cache(function($image) use ($width, $height, $path){
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
	}

	/**
	 * Create a Log
	 * @param string $action
	 * @param array $options
	 * @return void
	 */
	public function log($action, $options = [])
	{

	}

	/**
	 * Return fake images
	 * @param integer $max
	 * @return array
	 */
	public static function fakeImages($max = 1, $options = [])
	{
		$files = [];
		if(!empty($options['tags']))
		{
			$tags = $options['tags'];
			$images = \Zbase\Utility\Service\Flickr::findByTags($tags[rand(0, (count($tags) - 1))], $max);
			if(!empty($images))
			{
				foreach ($images as $img)
				{
					if(!empty($img['url']))
					{
						$files[] = $img['url'];
					}
				}
			}
		}
		else
		{
			for ($x = 0; $x <= $max; $x++)
			{
				// $files[] = 'http://api.adorable.io/avatars/285/' . $x . '.png';
				$files[] = 'https://placeimg.com/640/480/tech.png';
			}
		}
		return $files;
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
				'type' => 'belongsto',
				'class' => [
					'method' => static::$nodeNamePrefix
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
			'name' => static::$nodeNamePrefix . '_files',
			'description' => 'Files Table',
			'primaryKey' => 'file_id',
			'timestamp' => true,
			'alphaId' => true,
			'optionable' => true
		];
		return $entity;
	}

	/**
	 * Return fake values
	 */
	public static function fakeValue()
	{
		$faker = \Faker\Factory::create();
		return [
			'title' => ucfirst($faker->words(rand(3, 10), true)),
			'status' => rand(0, 2),
		];
	}

	/**
	 * Return table minimum columns requirement
	 * @param array $columns Some columns
	 * @param array $entity Entity Configuration
	 * @return array
	 */
	public static function tableColumns($columns = [], $entity = [])
	{
		$columns['node_id'] = [
			'filterable' => [
				'enable' => true
			],
			'sortable' => [
				'enable' => true
			],
			'length' => 16,
			'hidden' => false,
			'fillable' => true,
			'type' => 'integer',
			'index' => true,
			'unique' => true,
			'unsigned' => true,
			'comment' => 'Node Id',
			'foreign' => [
				'table' => static::$nodeNamePrefix,
				'column' => 'node_id',
				'onDelete' => 'cascade'
			],
		];
		$columns['user_id'] = [
			'filterable' => [
				'enable' => true
			],
			'sortable' => [
				'enable' => true
			],
			'hidden' => false,
			'fillable' => false,
			'nullable' => true,
			'type' => 'integer',
			'index' => true,
			'comment' => 'User that add'
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
		$columns['is_primary'] = [
			'filterable' => [
				'name' => 'primary',
				'enable' => true
			],
			'sortable' => [
				'name' => 'primary',
				'enable' => true
			],
			'hidden' => false,
			'fillable' => false,
			'nullable' => true,
			'unsigned' => true,
			'type' => 'boolean',
			'index' => true,
			'comment' => 'Is Primary'
		];
		$columns['filename'] = [
			'hidden' => false,
			'length' => 255,
			'fillable' => false,
			'nullable' => true,
			'type' => 'string',
			'index' => true,
			'comment' => 'Filename'
		];
		$columns['url'] = [
			'hidden' => false,
			'length' => 255,
			'fillable' => false,
			'nullable' => true,
			'type' => 'string',
			'index' => true,
			'comment' => 'URL'
		];
		$columns['filetype'] = [
			'hidden' => false,
			'length' => 32,
			'fillable' => false,
			'nullable' => true,
			'type' => 'string',
			'index' => true,
			'comment' => 'File type'
		];
		$columns['mimetype'] = [
			'filterable' => [
				'name' => 'mimetype',
				'enable' => true
			],
			'sortable' => [
				'name' => 'mimetype',
				'enable' => true
			],
			'hidden' => false,
			'length' => 32,
			'fillable' => false,
			'nullable' => true,
			'type' => 'string',
			'index' => true,
			'comment' => 'Mime Type'
		];
		$columns['size'] = [
			'filterable' => [
				'name' => 'size',
				'enable' => true
			],
			'sortable' => [
				'name' => 'size',
				'enable' => true
			],
			'hidden' => false,
			'length' => 255,
			'fillable' => false,
			'nullable' => true,
			'type' => 'integer',
			'index' => true,
			'comment' => 'File size in byte'
		];
		return $columns;
	}

}
