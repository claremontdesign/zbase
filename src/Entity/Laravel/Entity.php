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

}
