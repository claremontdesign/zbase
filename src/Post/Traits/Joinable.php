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
 *	left = The LEFT JOIN keyword returns all rows from the left table (table1),
 *			with the matching rows in the right table (table2).
 *			The result is NULL in the right side when there is no match.
 *	right = Return all rows from the right table,
 *			and the matched rows from the left table
 *	join = Returns all rows when there is at least one match in BOTH tables
 */

//	protected function _joins()
//	{
//		$joins = [
//			[
//
//				'type' => 'left|join|right',
//				'model' => $productTable . ' as alias',
//				'foreign_key' => $productTable . '.id',
//				'local_key' => $productRelatedProductsTable . '.ritem_objectid'
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
						$query->leftJoin($model['model'], $model['foreign_key'], '=', $model['local_key']);
					}
					if($joinType == 'join')
					{
						$query->join($model['model'], $model['foreign_key'], '=', $model['local_key']);
					}
					if($joinType == 'right')
					{
						$query->rightJoin($model['model'], $model['foreign_key'], '=', $model['local_key']);
					}
				}
			}
		}
		return $query;
	}

}
