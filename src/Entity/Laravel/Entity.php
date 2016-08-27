<?php

namespace Zbase\Entity\Laravel;

/**
 * Zbase-Model-Entity
 *
 * Entity Base Model
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Entity.php
 * @project Zbase
 * @package Zbase/Entity
 */
use Illuminate\Database\Eloquent\Model as LaravelModel;
use Zbase\Entity\Laravel\Traits as LaravelTraits;
use Zbase\Interfaces;
use Zbase\Traits;

class Entity extends LaravelModel implements Interfaces\EntityInterface
{

	use LaravelTraits\Filterable,
	 LaravelTraits\Joinable,
	 LaravelTraits\Sortable,
	 LaravelTraits\Entity,
	 Traits\Cache;

	/**
	 * The database table used by the model.
	 * @var string
	 */
	protected $table = null;

	/**
	 * The Primary Key
	 * @var string
	 */
	protected $primaryKey = null;

	/**
	 * columns/fields that can only be filled by user through form
	 * @var array
	 */
	protected $fillable = [];

	/**
	 * The attributes/field that are  excluded from the model's JSON form
	 * 	when converting data to array or json
	 * @var array
	 */
	protected $hidden = [];

	/**
	 * Attributes that will be included on this model that is not a column db's table
	 * 	protected $append = ['is_admin']
	 * 	should have a getter:
	 *
	 * public function getIsAdminAttribute()
	 * {
	 * 		return $this->attributes['is_admin'] == 'yes';
	 * }
	 *
	 * @var array
	 */
	protected $appends = [];
	protected $casts = [];

	/**
	 * Create a new Eloquent model instance.
	 *
	 * @param  array  $attributes
	 * @return void
	 */
	public function __construct(array $attributes = [])
	{
		parent::__construct($attributes);
		$this->__initEntity();
	}

	/**
	 * Fix/Manipulate entity data
	 *
	 * @param array $data
	 * @param string $mode The data mode insert|update|delete
	 * @return array
	 */
	public function fixDataArray(array $data, $mode = null)
	{
		return $data;
	}

	/**
	 * Table Entity Configuration
	 * @param array $entity Configuration default data
	 * @return array
	 */
	public static function entityConfiguration($entity = [])
	{
		return $entity;
	}

	/**
	 * Cast this Model to another Model
	 * @return \Zbase\Entity\Laravel\Entity
	 */
	public function cast()
	{
		return $this;
	}

	/**
	 * Return a User byID -- using cached data
	 *
	 * @return User
	 */
	public function byId($id)
	{
		$cacheKey = zbase_cache_key(zbase_entity($this->entityName()), 'byId_' . $id);
		return zbase_cache($cacheKey, function() use ($id, $cacheKey){
			return $this->repo()->byId($id);
		}, [$this->entityName()], (60 * 24), ['forceCache' => true, 'driver' => 'file']);
	}

	/**
	 * Return a User By attribute
	 * @param type $attribute
	 * @param type $value
	 */
	public function by($attribute, $value)
	{
		$cacheKey = zbase_cache_key(zbase_entity($this->entityName()), 'by_' . $attribute . '_' . $value);
		return zbase_cache($cacheKey, function() use ($attribute, $value){
			return $this->repo()->by($attribute, $value)->first();
		}, [$this->entityName()], (60 * 24), ['forceCache' => true, 'driver' => 'file']);
	}

	// <editor-fold defaultstate="collapsed" desc="CLEAR CACHES">
	public function fill(array $attributes)
	{
		$this->clearEntityCacheByTableColumns();
		return parent::fill($attributes);
	}

	public function update(array $attributes = [], array $options = [])
	{
		$this->clearEntityCacheByTableColumns();
		$this->clearEntityCacheById();
		return parent::update($attributes, $options);
	}

	public function save(array $options = [])
	{
		$this->clearEntityCacheByTableColumns();
		$this->clearEntityCacheById();
		return parent::save($options);
	}

	public function delete()
	{
		$this->clearEntityCacheByTableColumns();
		$this->clearEntityCacheById();
		return parent::delete();
	}

	/**
	 * Clear entity cache by Attributes/Value
	 *
	 * @return void
	 */
	public function clearEntityCacheByTableColumns()
	{
		foreach ($this->getColumns() as $columnName => $columnConfig)
		{
			if(is_string($this->{$columnName}))
			{
				$cacheKey = zbase_cache_key(zbase_entity($this->entityName()), 'by_' . $columnName . '_' . $this->{$columnName});
				if($columnName == $this->getKeyName())
				{
					$idValue = $this->{$columnName};
				}
				zbase_cache_remove($cacheKey, [$this->entityName()], ['driver' => 'file']);
			}
		}
		if(!empty($idValue))
		{
			$relations = static::tableRelations();
			if(!empty($relations))
			{
				foreach ($relations as $relationName => $relationConfig)
				{
					$cacheKey = zbase_cache_key($this, 'byrelation_' . $relationName . '_' . $idValue);
					zbase_cache_remove($cacheKey, [$this->entityName], ['driver' => 'file']);
				}
			}
		}
	}

	/**
	 * Clear entity cache by Id
	 *
	 * @return void
	 */
	public function clearEntityCacheById()
	{
		$cacheKey = zbase_cache_key(zbase_entity($this->entityName()), 'byId_' . $this->id());
		zbase_cache_remove($cacheKey, [$this->entityName()], ['driver' => 'file']);
		$cacheKey = zbase_cache_key(zbase_entity($this->entityName()), 'byId_' . $this->id() . '_withtrashed');
		zbase_cache_remove($cacheKey, [$this->entityName()], ['driver' => 'file']);
		$cacheKey = zbase_cache_key(zbase_entity($this->entityName()), 'byId_' . $this->id() . '_onlytrashed');
		zbase_cache_remove($cacheKey, [$this->entityName()], ['driver' => 'file']);
	}

	protected static function boot()
	{
		parent::boot();
		static::saved(function($entity) {
			$entity->clearEntityCacheById();
		});
	}

	// </editor-fold>
}
