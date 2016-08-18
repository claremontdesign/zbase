<?php

namespace Zbase\Entity\Laravel\Node;

/**
 * Zbase-Node Nested
 * https://github.com/etrepat/baum
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
use Zbase\Entity\Laravel\Traits as LaravelTraits;
use Zbase\Traits;

class Nested extends \Baum\Node
{

	use LaravelTraits\Filterable,
	 LaravelTraits\Joinable,
	 \Illuminate\Database\Eloquent\SoftDeletes,
	 LaravelTraits\Sortable,
	 LaravelTraits\Entity,
	 \Zbase\Entity\Laravel\Node\Traits\Node,
	 Traits\Cache;

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
	 * Return table minimum columns requirement
	 * @return array
	 */
	public static function nestedNodeDefaultColumns()
	{
		$columns = [];
		$columns['parent_id'] = [
			'hidden' => false,
			'fillable' => false,
			'type' => 'integer',
			'length' => 16,
			'nullable' => true,
			'unsigned' => true,
			'index' => true,
			'comment' => 'Nested Parent Id'
		];
		$columns['lft'] = [
			'hidden' => false,
			'fillable' => false,
			'type' => 'integer',
			'unsigned' => true,
			'nullable' => true,
			'length' => 16,
			'index' => true,
			'comment' => 'Nested Left'
		];
		$columns['rgt'] = [
			'hidden' => false,
			'fillable' => false,
			'type' => 'integer',
			'nullable' => true,
			'length' => 16,
			'index' => true,
			'comment' => 'Nested Right'
		];
		$columns['depth'] = [
			'hidden' => false,
			'fillable' => false,
			'type' => 'integer',
			'nullable' => true,
			'length' => 16,
			'index' => true,
			'comment' => 'Nested Depth'
		];
		return $columns;
	}

}
