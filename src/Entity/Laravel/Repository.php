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
	 * View only trashed rows
	 * @var boolean
	 */
	protected $onlyTrashed = false;

	/**
	 * Query Name
	 * @var type
	 */
	protected $queryName = null;
	protected $perPage = null;

	/**
	 * Constructor
	 * @param Interfaces\EntityInterface $model
	 */
	public function __construct(Interfaces\EntityInterface $model)
	{
		$this->setModel($model);
		$this->perPage = $model->getPerPage();
	}

	/**
	 *
	 * @param type $perPage
	 * @return \Zbase\Entity\Laravel\Repository
	 */
	public function perPage($perPage)
	{
		$this->perPage = $perPage;
		return $this;
	}

	/**
	 * Generate Cache Key
	 * @param type $builder
	 *
	 * @TODO Generate cache key based on SQL Statement and Bindings
	 * @return string
	 */
	public function cacheKey($builder)
	{

	}

	public function setQueryName($queryName)
	{
		$this->queryName = $queryName;
		return $this;
	}

	public function getQueryName()
	{
		return $this->queryName;
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
		$onlyTrashed = $this->onlyTrashed;
		$prefix = null;
		if(!empty($withTrashed))
		{
			$prefix .= '_withtrashed';
		}
		if(!empty($onlyTrashed))
		{
			$prefix .= '_onlytrashed';
		}
		$cacheKey = zbase_cache_key($this->getModel(), 'byId_' . $id . $prefix);
		return zbase_cache($cacheKey, function() use ($id, $columns, $withTrashed, $onlyTrashed){
			if(!empty($withTrashed))
			{
				return $this->getModel()->withTrashed()->find(intval($id), $columns);
			}
			if(!empty($onlyTrashed))
			{
				return $this->getModel()->onlyTrashed()->find(intval($id), $columns);
			}
			return $this->getModel()->find(intval($id), $columns);
			}, [$this->getModel()->getTable()], (60 * 24), ['forceCache' => true, 'driver' => 'file']
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
		$onlyTrashed = $this->onlyTrashed;
		$prefix = null;
		if(!empty($withTrashed))
		{
			$prefix .= '_withtrashed';
		}
		if(!empty($onlyTrashed))
		{
			$prefix .= '_onlytrashed';
		}
		$prefix .= '__id__' . $id . '_' . implode('_', $columns);
		// $cacheKey = zbase_cache_key($this, __FUNCTION__, func_get_args(), $prefix);
		$cacheKey = zbase_cache_key($this->getModel(), 'by_alpha_id_' . $id);
		return zbase_cache($cacheKey, function() use ($id, $columns){
			return $this->by('alpha_id', $id, $columns)->first();
			}, [$this->getModel()->getTable()], (60 * 24), ['forceCache' => true, 'driver' => 'file']
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
		$onlyTrashed = $this->onlyTrashed;
		$prefix = null;
		if(!empty($withTrashed))
		{
			$prefix .= '_withtrashed';
		}
		if(!empty($onlyTrashed))
		{
			$prefix .= '_onlytrashed';
		}
		$cacheKey = zbase_cache_key($this->getModel(), 'by_slug_' . $id);
		return zbase_cache($cacheKey, function() use ($id, $columns){
			return $this->by('slug', $id, $columns)->first();
			}, [$this->getModel()->getTable()], (60 * 24), ['forceCache' => true, 'driver' => 'file']
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
			$filters = [$attribute => ['eq' => ['field' => $attribute, 'value' => $value]]];
		}
		return $this->all($columns, $filters, $sorting, $joins, $paginate, $unions, $group, $options);
	}

	/**
	 * return the First Row
	 * @param type $filters
	 * @param type $columns
	 * @param type $sorting
	 * @param type $joins
	 * @param type $options
	 * @return type
	 * @throws InvalidArgumentException
	 */
	public function first($filters, $columns = ['*'], $sorting = null, $joins = null, $options = null)
	{
		$unions = [];
		$group = [];
		$paginate = null;
		$row = $this->all($columns, $filters, $sorting, $joins, $paginate, $unions, $group, $options);
		if($row instanceof \Illuminate\Database\Eloquent\Collection && count($row) > 0)
		{
			return $row->first();
		}
		return null;
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
			$paginate = $this->perPage;
		}
		$withTrashed = $this->withTrashed;
		$onlyTrashed = $this->onlyTrashed;
		$prefix = $this->getModel()->getTable();
		if(!empty($withTrashed))
		{
			$prefix .= '_withtrashed';
		}
		if(!empty($onlyTrashed))
		{
			$prefix .= '_onlytrashed';
		}
		$logMsg = __METHOD__ . PHP_EOL;
		$logMsg .= $this->getSqlStatement($builder) . PHP_EOL;
		$logMsg .= json_encode($this->getSqlBindings($builder)) . PHP_EOL;
		$prefix .= $this->getSqlStatement($builder) . json_encode($this->getSqlBindings($builder));
		if(zbase_is_dev())
		{
			// zbase()->json()->addVariable(__METHOD__, [$this->getQueryName() => [$this->getSqlStatement($builder), $this->getSqlBindings($builder)]]);
		}
		if(!empty($paginate))
		{
			return $builder->paginate($paginate, $columns);
		}
		return $builder->get();
	}

	/**
	 * REturn the number of rows
	 * @param array $filters
	 * @param array $joins
	 * @param array $unions
	 * @param array $group
	 * @param array $options
	 */
	public function count($filters = null, $joins = null, $unions = null, $group = null, $options = null)
	{
		$builder = $this->_query(['COUNT(1)'], $filters, null, $joins, $unions, $group, $options);
		$logMsg = __METHOD__ . PHP_EOL;
		$logMsg .= $this->getSqlStatement($builder) . PHP_EOL;
		$logMsg .= json_encode($this->getSqlBindings($builder)) . PHP_EOL;
		return zbase_cache(
				zbase_cache_key($this, __FUNCTION__, func_get_args(), $this->getModel()->getTable()), function() use ($builder){
			return $builder->count();
				}, [$this->getModel()->getTable()], null, ['logFile' => 'Repo_' . $this->getModel()->getTable(), 'logMsg' => $logMsg]
		);
	}

	/**
	 * Return the SQL statement
	 * @param object $builder
	 * @return string
	 */
	protected function getSqlStatement($builder)
	{
		if(method_exists($builder, 'getQuery'))
		{
			return $builder->getQuery()->toSql();
		}
		return $sqlStmt = $builder->toSql();
	}

	/**
	 * Return the SQL Bindings
	 *
	 * @param object $builder
	 * @return string
	 */
	protected function getSqlBindings($builder)
	{
		if(method_exists($builder, 'getQuery'))
		{
			return $builder->getQuery()->getBindings();
		}
		return $builder->getBindings();
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
		if($model->hasSoftDelete())
		{
			if(!empty($this->withTrashed))
			{
				$model = $model->withTrashed();
			}
			if(!empty($this->onlyTrashed))
			{
				$model = $model->onlyTrashed();
			}
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

	/**
	 * If to include trashed rows
	 * @param boolean $flag
	 */
	public function onlyTrashed($flag = true)
	{
		$this->onlyTrashed = $flag;
		return $this;
	}

}
