<?php

namespace Zbase\Entity\Laravel\Traits;

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
