<?php

namespace Zbase\Post;

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
use Zbase\Post\PostInterface;

class Repository
{

	/**
	 *
	 * @var PostInterface
	 */
	protected $model = null;

	/**
	 * If to include Trashed/Deleted rows
	 * @var boolean
	 */
	protected $withTrashed = false;

	/**
	 * Return the Trashed/Deleted Rows Only
	 * @var boolean
	 */
	protected $onlyTrashed = false;

	/**
	 * If to Debug
	 * @var boolean
	 */
	protected $debug = false;

	/**
	 * Constructor
	 * @param PostInterface $model
	 */
	public function __construct(PostInterface $model)
	{
		$this->setModel($model);
	}

	/**
	 *
	 * @param type $id
	 * @param type $columns
	 * @return PostInterface
	 * @throws InvalidArgumentException
	 */
	public function byId($id, $columns = ['*'])
	{
		if(is_null($id) || empty($id))
		{
			throw new InvalidArgumentException('Invalid id');
		}
		return $this->getModel()->find(intval($id), $columns);
	}

	/**
	 * Find by Alpha id
	 * @param string $id
	 * @param array $columns
	 * @return PostInterface
	 * @throws InvalidArgumentException
	 */
	public function byAlphaId($id, $columns = ['*'])
	{
		if(is_null($id) || empty($id))
		{
			throw new InvalidArgumentException('Invalid id');
		}
		return $this->by('alpha_id', $id, $columns)->first();
	}

	/**
	 * Find by Slug
	 * @param string $id
	 * @param array $columns
	 * @return PostInterface
	 * @throws InvalidArgumentException
	 */
	public function bySlug($id, $columns = ['*'])
	{
		if(is_null($id) || empty($id))
		{
			throw new InvalidArgumentException('Invalid id');
		}
		return $this->by('slug', $id, $columns)->first();
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
	 * @return PostInterface[]
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
			$paginate = $this->model->postRowsPerPage();
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
		return $builder->count();
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
	public function setModel(PostInterface $model)
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
