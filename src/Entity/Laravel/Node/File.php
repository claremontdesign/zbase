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

class File extends BaseEntity implements WidgetEntityInterface
{

	use \Zbase\Entity\Laravel\Node\Traits\File;

	/**
	 * The Entity Name
	 * @var string
	 */
	protected $entityName = 'node_files';

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
	protected $thWidth = 150;
	protected $thHeight = 150;
	protected $thQuality = 80;

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
	 * Serve the File
	 * @param integer $width
	 * @param integer $height
	 * @param integer $quality Image Quality
	 * @param boolean $download If to download
	 * @return boolean
	 */
	public function serveImage($width, $height = null, $quality = null, $download = false)
	{
		$folder = $this->folder();
		$filename = $this->filename;
		$path = $folder . $filename;
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

}
