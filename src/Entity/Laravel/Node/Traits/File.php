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
				$this->_actionMessages[$action]['success'][] = _zt('Created!', ['%title%' => $this->title, '%id%' => $this->id()]);
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
		return zbase_url_from_route('nodeImage', $params);
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
			return zbase_url_from_route('admin.' . $this->routeName . '_files', $params);
		}
		return zbase_url_from_route($this->routeName . '_files', $params);
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
	 * @return boolean
	 */
	public function serveImage($width, $height = null, $quality = null, $download = false)
	{
		if($this->isUrl())
		{
			$path = $this->url;
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
			return \Response::make($cachedImage, 200, array('Content-Type' => 'image/png'));
		}
		else
		{
			$path = $this->getFilePath();
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
		}
		return false;
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
				'name' => 'nodeid',
				'enable' => true
			],
			'sortable' => [
				'name' => 'nodeid',
				'enable' => true
			],
			'hidden' => false,
			'length' => 255,
			'fillable' => false,
			'nullable' => false,
			'type' => 'integer',
			'index' => true,
			'comment' => 'Node Id'
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
