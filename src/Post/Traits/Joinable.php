<?php

namespace Zbase\Post\Traits;

/**
 * Zbase-Entity
 *
 * Joinable Trait
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Joinable.php
 * @project Zbase
 * @package Zbase/Entity
 *
 * type:
 * 	left = The LEFT JOIN keyword returns all rows from the left table (table1),
 * 			with the matching rows in the right table (table2).
 * 			The result is NULL in the right side when there is no match.
 * 	right = Return all rows from the right table,
 * 			and the matched rows from the left table
 * 	join = Returns all rows when there is at least one match in BOTH tables
 */
//	protected function _joins()
//	{
//		$joins = [
//			[
//
//				'type' => 'left|join|right',
//				'model' => $productTable . ' as alias',
//				'foreign_key' => $productTable . '.id',
//				'local_key' => $productRelatedProductsTable . '.ritem_objectid',
//				'conditions' => [
//					[othertable.columns, '=', tableTwo.column]
//				]
//				// $join->on('arrival','>=',DB::raw("'2012-05-01'"));
//			]
//		];
//		return $joins;
//	}


trait Joinable
{

	/**
	 * @return Illuminate\Database\Eloquent\Builder
	 */
	public function scopeJoinModels($query, $models = array())
	{
		if(!empty($models))
		{
			if(is_array($models))
			{
				foreach ($models as $model)
				{
					$joinType = !empty($model['type']) ? $model['type'] : 'left';
					if($joinType == 'left')
					{
						if(!empty($model['foreign_key']) && $model['local_key'])
						{
							$query->leftJoin($model['model'], $model['foreign_key'], '=', $model['local_key']);
						}
						if(!empty($model['conditions']))
						{
							$conditions = $model['conditions'];
							$query->leftJoin($model['model'], function($join) use ($conditions){
								foreach ($conditions as $condition)
								{
									/**
									 * [othertable.columns, '=', tableTwo.column]
									 */
									if(count($condition) == 3)
									{
										$join->on($condition[0], $condition[1], $condition[2]);
									}
								}
							});
						}
					}
					if($joinType == 'join')
					{
						if(!empty($model['foreign_key']) && $model['local_key'])
						{
							$query->join($model['model'], $model['foreign_key'], '=', $model['local_key']);
						}
						if(!empty($model['conditions']))
						{
							$conditions = $model['conditions'];
							$query->join($model['model'], function($join) use ($conditions){
								foreach ($conditions as $condition)
								{
									/**
									 * [othertable.columns, '=', tableTwo.column]
									 */
									if(count($condition) == 3)
									{
										$join->on($condition[0], $condition[1], $condition[2]);
									}
								}
							});
						}
					}
					if($joinType == 'right')
					{
						if(!empty($model['foreign_key']) && $model['local_key'])
						{
							$query->rightJoin($model['model'], $model['foreign_key'], '=', $model['local_key']);
						}
						if(!empty($model['conditions']))
						{
							$conditions = $model['conditions'];
							$query->join($model['model'], function($join) use ($conditions){
								foreach ($conditions as $condition)
								{
									/**
									 * [othertable.columns, '=', tableTwo.column]
									 */
									if(count($condition) == 3)
									{
										$join->on($condition[0], $condition[1], $condition[2]);
									}
								}
							});
						}
					}
				}
			}
		}
		return $query;
	}

}
