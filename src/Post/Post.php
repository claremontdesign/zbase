<?php

namespace Zbase\Post;

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

class Post extends LaravelModel implements \Zbase\Interfaces\EntityInterface
{

	use \Zbase\Post\Traits\Filterable,
	 \Zbase\Post\Traits\Joinable,
//	 \Illuminate\Database\Eloquent\SoftDeletes,
	 \Zbase\Post\Traits\Sortable;

	const STATUS_HIDDEN = 0;
	const STATUS_DRAFT = 1;
	const STATUS_DISPLAY = 2;

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
	 * The Table Description
	 * @var string
	 */
	protected $tableDescription = null;

	/**
	 * Table Configuration
	 * @var array
	 */
	protected $tableConfiguration = [];

	/**
	 * columns/fields that can only be filled by user through form
	 * @var array
	 */
	protected $fillable = ['*'];

	/**
	 * The attributes/field that are  excluded from the model's JSON form
	 * 	when converting data to array or json
	 * @var array
	 */
	protected $hidden = [];

	/**
	 * Create a new Eloquent model instance.
	 *
	 * @param  array  $attributes
	 * @return void
	 */
	public function __construct(array $attributes = [])
	{
		parent::__construct($attributes);
	}

}
