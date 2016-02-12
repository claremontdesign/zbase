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
use Zbase\Entity\Laravel\Relations\HasMany;
use Zbase\Entity\Laravel\Relations\BelongsToMany;
//use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\HasOne;
//use Illuminate\Database\Eloquent\Relations\MorphTo;
//use Illuminate\Database\Eloquent\Relations\MorphOne;
//use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
//use Illuminate\Database\Eloquent\Relations\MorphToMany;
//use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Model as LaravelModel;
use Zbase\Entity\Laravel\Traits as LaravelTraits;
use Zbase\Interfaces;
use Zbase\Traits;

class Entity extends LaravelModel implements Interfaces\EntityInterface
{

	use LaravelTraits\Filterable,
	 LaravelTraits\Joinable,
	 LaravelTraits\Sortable,
	 Traits\Cache;

	/**
	 * Entity name as described in the config
	 * @var string
	 */
	protected $entityName = null;

	/**
	 * The Relation mode
	 * if relationshipMode == result, will return results when relationship method is called
	 * else, will return the Relation Model
	 * @var string
	 */
	protected $relationshipMode = 'result';

	/**
	 *
	 * @var Interfaces\EntityRepositoryInterface
	 */
	protected $repository = null;

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
	 * Column Map
	 * @var array
	 */
	protected $dbColumns = [];

	/**
	 * Array of Relationships
	 * @var array
	 */
	protected $relationship = [];

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

	/**
	 * Create a new Eloquent model instance.
	 *
	 * @param  array  $attributes
	 * @return void
	 */
	public function __construct(array $attributes = [])
	{
		$entityAttributes = zbase_config_get('entity.' . $this->entityName);
		$this->table = zbase_data_get($entityAttributes, 'table.name', $this->table);
		$this->primaryKey = zbase_data_get($entityAttributes, 'table.primaryKey', false);
		if(empty($this->primaryKey))
		{
			$this->incrementing = false;
		}
		$this->dbColumns = zbase_data_get($entityAttributes, 'table.columns', $this->dbColumns);
		$this->relationship = zbase_data_get($entityAttributes, 'relations', $this->relationship);
		$this->timestamps = zbase_data_get($entityAttributes, 'table.timestamp', false);
		$this->perPage = zbase_data_get($entityAttributes, 'table.perPage', $this->perPage);
		if(!empty($this->dbColumns))
		{
			foreach ($this->dbColumns as $columnName => $column)
			{
				$type = $column['type'];
				if(!empty($column['fillable']))
				{
					$this->fillable[] = $columnName;
				}
				if(!empty($column['hidden']))
				{
					$this->hidden[] = $columnName;
				}
				if($type == 'timestamp')
				{
					$this->dates[] = $columnName;
				}
			}
		}
		parent::__construct($attributes);
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
	 * Dynamically retrieve attributes on the model.
	 *
	 * @param  string  $key
	 * @return mixed
	 */
	public function __get($key)
	{
		if(!empty($this->relationship))
		{
			foreach ($this->relationship as $rK => $rV)
			{
				$r = $this->_relationship($key, $rK, $rV);
				if($r !== false)
				{
					return $r;
				}
			}
		}
		return parent::__get($key);
	}

	/**
	 * Handle dynamic method calls into the model.
	 *
	 * https://laravel.com/docs/5.1/eloquent-relationships#one-to-one
	 * @param  string  $method
	 * @param  array  $parameters
	 * @return mixed
	 */
	public function __call($method, $parameters)
	{
		if(!empty($this->relationship))
		{
			foreach ($this->relationship as $rK => $rV)
			{
				$r = $this->_relationship($method, $rK, $rV);
				if($r !== false)
				{
					return $r;
				}
			}
		}
		return parent::__call($method, $parameters);
	}

	/**
	 *
	 * @param string $method The property or method to be called
	 * @param string $name The name of the relationship index
	 * @param array $config The config of the relationship
	 * @return HasOne|BelongsTo
	 */
	protected function _relationship($method, $name, $config)
	{
		$rType = zbase_value_get($config, 'type', false);
		if(!empty($rType))
		{
			$rMethod = zbase_value_get($config, 'class.method', zbase_string_camel_case($name));
			$isFetching = preg_match('/fetch/', $method);
			if($isFetching)
			{
				$method = zbase_string_camel_case(str_replace('fetch', '', $method));
			}
			if($rMethod == $method)
			{
				$rEntity = zbase_value_get($config, 'entity', false);
				$rInverse = zbase_value_get($config, 'inverse', false);
				$model = zbase_entity($rEntity);
				$lKey = zbase_value_get($config, 'keys.local', null);
				$fKey = zbase_value_get($config, 'keys.foreign', null);
				if(!empty($rEntity))
				{
					switch (strtolower($rType))
					{
						case 'onetoone':
							if(!empty($rInverse))
							{
								$relObj = new BelongsTo($model->newQuery(), $this, $model->getTable() . '.' . $fKey, $lKey, $name);
							}
							else
							{
								$relObj = new HasOne($model->newQuery(), $this, $model->getTable() . '.' . $fKey, $lKey);
							}
							break;
						case 'onetomany':
							if(!empty($rInverse))
							{
								$relObj = new BelongsTo($model->newQuery(), $this, $model->getTable() . '.' . $fKey, $lKey, $name);
							}
							else
							{
								return new HasMany($model->newQuery(), $this, $model->getTable() . '.' . $fKey, $lKey);
							}
							break;
						case 'manytomany':
							$pivot = zbase_value_get($config, 'pivot', null);
							return new BelongsToMany($model->newQuery(), $this, $pivot, $fKey, $lKey, $name);
						case 'hasmanythrough':
							break;
						case 'morphto':
							break;
						case 'morphtomany':
							break;
						default;
					}
					if(!empty($relObj))
					{
						if($this->relationshipMode == 'result')
						{
							return zbase_cache(
									$this->cacheKey($relObj), function() use ($relObj){
								return $relObj->getResults();
							}, [$this->getTable()]
							);
						}
						return $relObj;
					}
				}
			}
		}
		return false;
	}

	/**
	 * @see $this->repository
	 * @return Interfaces\EntityRepositoryInterface
	 */
	public function repository()
	{
		if(!$this->repository instanceof Interfaces\EntityRepositoryInterface)
		{
			$this->repository = new Repository($this);
		}
		return $this->repository;
	}

	/**
	 * Toggle the relationMode
	 * @return void
	 */
	public function toggleRelationshipMode()
	{
		if($this->relationshipMode == 'result')
		{
			$this->relationshipMode = false;
		}
		else
		{
			$this->relationshipMode = 'result';
		}
	}

}
