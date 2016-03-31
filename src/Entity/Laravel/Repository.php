<?php

namespace Zbase\Entity\Laravel;

/**
 * Zbase-Model-Repository
 *
 * Repository Entity Base Model
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Repository.php
 * @project Zbase
 * @package Zbase/Entity
 */
use Zbase\Exceptions\InvalidArgumentException;
use Zbase\Interfaces;

class Repository implements Interfaces\EntityRepositoryInterface
{

	/**
	 *
	 * @var Interfaces\EntityInterface
	 */
	protected $model = null;

	/**
	 * If true, will debug the Query, displays the SQL and the bindings
	 * @var boolean
	 */
	protected $debug = false;

	/**
	 * If to include softDeleted columns
	 * @var boolean
	 */
	protected $withTrashed = false;

	/**
	 * Constructor
	 * @param Interfaces\EntityInterface $model
	 */
	public function __construct(Interfaces\EntityInterface $model)
	{
		$this->setModel($model);
	}

	/**
	 *
	 * @param type $id
	 * @param type $columns
	 * @return Interfaces\EntityInterface
	 * @throws InvalidArgumentException
	 */
	public function byId($id, $columns = ['*'])
	{
		if(is_null($id) || empty($id))
		{
			throw new InvalidArgumentException('Invalid id');
		}
		$withTrashed = $this->withTrashed;
		return zbase_cache(
				zbase_cache_key($this, __FUNCTION__, func_get_args()), function() use ($id, $columns, $withTrashed){
			if(!empty($withTrashed))
			{
				return $this->getModel()->withTrashed()->find(intval($id), $columns);
			}
			return $this->getModel()->find(intval($id), $columns);
				}, [$this->getModel()->getTable()]
		);
	}
	/**
	 * Find by Alpha id
	 * @param string $id
	 * @param array $columns
	 * @return Interfaces\EntityInterface
	 * @throws InvalidArgumentException
	 */
	public function byAlphaId($id, $columns = ['*'])
	{
		if(is_null($id) || empty($id))
		{
			throw new InvalidArgumentException('Invalid id');
		}
		$withTrashed = $this->withTrashed;
		return zbase_cache(
				zbase_cache_key($this, __FUNCTION__, func_get_args()), function() use ($id, $columns, $withTrashed){
			if(!empty($withTrashed))
			{
				return $this->by('alpha_id', $id, $columns)->first();
			}
			return $this->by('alpha_id', $id, $columns)->first();
				}, [$this->getModel()->getTable()]
		);
	}
	/**
	 * Find by Slug
	 * @param string $id
	 * @param array $columns
	 * @return Interfaces\EntityInterface
	 * @throws InvalidArgumentException
	 */
	public function bySlug($id, $columns = ['*'])
	{
		if(is_null($id) || empty($id))
		{
			throw new InvalidArgumentException('Invalid id');
		}
		$withTrashed = $this->withTrashed;
		return zbase_cache(
				zbase_cache_key($this, __FUNCTION__, func_get_args()), function() use ($id, $columns, $withTrashed){
			if(!empty($withTrashed))
			{
				return $this->by('slug', $id, $columns)->first();
			}
			return $this->by('slug', $id, $columns)->first();
				}, [$this->getModel()->getTable()]
		);
	}

	/**
	 * Find rows by attributes
	 * @param string $attribute Attribute name
	 * @param string $value Attribute value
	 *
	 * @see $this->all();
	 */
	public function by($attribute, $value, $columns = ['*'], $sorting = null, $joins = null, $paginate = null, $unions = null, $group = null, $options = null)
	{
		if(is_null($value) || empty($attribute))
		{
			throw new InvalidArgumentException('Invalid arguments for $attribute or $value');
		}
		$filters = [];
		if(is_array($attribute) && is_array($value))
		{
			$counter = 0;
			foreach ($attribute as $att)
			{
				$filters[] = [$att => $value[$counter]];
				$counter++;
			}
		}
		else
		{
			$filters = [$attribute => $value];
		}
		return $this->all($columns, $filters, $sorting, $joins, $paginate, $unions, $group, $options);
	}

	/**
	 *
	 * @param array $columns
	 * @param array $filters
	 * [
	 * 		'indexName' => [
	 * 			['gt' => [
	 * 				'field' => 'columnName',
	 * 				'value' => 0
	 * 			]
	 * 		]
	 * ]
	 * @param array $sorting
	 * 		e.g ['created_at' => 'desc']
	 * 			['created_at' => 'desc', 'id' => 'asc']
	 * 			['created_at' => 'desc', 'id' => 'asc', 'table.column' => 'asc']
	 *
	 * @param array $joins
	 * [
	 * 		[
	 * 			'type' => 'left|join|right',
	 * 			'model' => 'otherTable as otherTableAlias',
	 * 			'foreign_key' => 'otherTableAlias.id',
	 * 			'local_key' => 'table.object_id'
	 * 		]
	 * ]
	 * @param integer|boolean $paginate
	 * @param array $unions Illuminate\Database\Query\Builder[] https://laravel.com/docs/5.0/queries#unions
	 * @param array $group
	 * @param array $options
	 * @return Interfaces\EntityInterface[]
	 */
	public function all($columns = ['*'], $filters = null, $sorting = null, $joins = null, $paginate = null, $unions = null, $group = null, $options = null)
	{
		$builder = $this->_query($columns, $filters, $sorting, $joins, $unions, $group);
		if(is_numeric($paginate))
		{
			$paginate = intval($paginate);
		}
		if(is_bool($paginate))
		{
			$paginate = $this->getModel()->getPerPage();
		}
		if(!empty($paginate))
		{
			return zbase_cache(
					zbase_cache_key($this, __FUNCTION__, func_get_args(), $this->getModel()->getTable()), function() use ($builder, $paginate, $columns){
				return $builder->paginate($paginate, $columns);
				}, [$this->getModel()->getTable()]
			);
		}

		return zbase_cache(
				zbase_cache_key($this, __FUNCTION__, func_get_args(), $this->getModel()->getTable()), function() use ($builder){
			return $builder->get();
				}, [$this->getModel()->getTable()]
		);
	}

	/**
	 * Create a new item
	 *
	 * @param array $data
	 * @throws InvalidArgumentException
	 * @return Authentication\Mappers\Eloquent saved object
	 */
	public function create(array $data = array())
	{
		$this->getModel()->fill($data)->save();
		zbase_cache_flush([$this->getModel()->getTable()]);
		return $this->getModel();
	}

	/**
	 * Update an existing item
	 *
	 * @param array $data
	 * @param array $filters
	 * @throws RuntimeException
	 * @throws InvalidArgumentException
	 * @return integer number of affected rows
	 */
	public function update(array $data = null, array $filters = null)
	{
		if(!empty($data))
		{
			$updates = $this->getModel()->filter($filters)->update($data);
			if(!empty($updates))
			{
				zbase_cache_flush([$this->getModel()->getTable()]);
				return $updates;
			}
		}
		return false;
	}

	/**
	 * Delete Records
	 * @param array $filters
	 * @return integer number of affected rows
	 */
	public function delete($filters = [])
	{
		$deletes = $this->getModel()->filter($filters)->delete();
		if($deletes)
		{
			zbase_cache_flush([$this->getModel()->getTable()]);
			return $deletes;
		}
		return false;
	}

	/**
	 * Restore an item identified by a given id
	 *
	 * @param array|integer $filters
	 * @throws InvalidArgumentException
	 * @throws RuntimeException
	 */
	public function restore($filters = [])
	{
		$this->getModel()->filters($filters)->restore();
		zbase_cache_flush([$this->getModel()->getTable()]);
	}

	/**
	 * Fix Query to model
	 * @param array $columns
	 * @param array $filters
	 * @param array $sorting
	 * @param array $joins
	 * @param array $unions
	 * @param array $group
	 * @return Builder
	 */
	protected function _query($columns, $filters, $sorting, $joins, $unions, $group)
	{
		$model = $this->getModel();
		if(!empty($this->withTrashed))
		{
			$model = $model->withTrashed();
		}
		if(!empty($joins))
		{
			$model = $model->joinModels($joins);
		}
		if(!empty($sorting))
		{
			if(is_array($sorting))
			{
				foreach ($sorting as $sField => $sDir)
				{
					if(!empty($sField) && !empty($sDir))
					{
						$model = $model->sort($sField, $sDir);
					}
				}
			}
		}
		if(!empty($filters))
		{
			$model = $model->filter($filters);
		}
		if(!empty($group))
		{
			foreach ($group as $groupBy)
			{
				$model = $model->groupBy($groupBy);
			}
		}
		if(!empty($unions))
		{
			foreach ($unions as $union)
			{
				if($union instanceof \Illuminate\Database\Query\Builder)
				{
					$model = $model->union($union);
				}
			}
		}
		$model->from($this->getModel()->getTable() . ' as ' . $this->getModel()->getTable());
		$model->select($columns);
		if(!empty($this->getDebug()))
		{
			var_dump($model->getQuery()->toSql(), $model->getQuery()->getBindings());
		}
		return $model;
	}

	/**
	 *
	 * @see $this->model
	 */
	public function getModel()
	{
		return $this->model;
	}

	/**
	 *
	 * @see $this->model
	 */
	public function setModel(Interfaces\EntityInterface $model)
	{
		$this->model = $model;
		return $this;
	}

	/**
	 *
	 * @see $this->debug
	 */
	public function getDebug()
	{
		if(zbase_request_query_input('__debug', false))
		{
			return true;
		}
		return $this->debug;
	}

	/**
	 *
	 * @see $this->debug
	 */
	public function setDebug($debug)
	{
		$this->debug = $debug;
		return $this;
	}

	/**
	 * If to include trashed rows
	 * @param boolean $flag
	 */
	public function withTrashed($flag = true)
	{
		$this->withTrashed = $flag;
		return $this;
	}

}
