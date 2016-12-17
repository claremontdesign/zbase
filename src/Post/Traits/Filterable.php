<?php

namespace Zbase\Post\Traits;

/**
 * Zbase-Entity Filterable Trait
 *
 * Filterable Trait
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Filterable.php
 * @project Zbase
 * @package Zbase/Entity
 */
trait Filterable
{

	/**
	 *
	 * $filter = [
	 * 		// Callable
	 * 		// Result: AND (`column` is null OR `column` = 'value')
	 * 		function($query){
	 * 				return $query
	 * 					->whereNull('column')
	 * 					->orWhere('column', '=', 'value');
	 * 		}
	 * 		'columnName' => [
	 * 			['gt' => [
	 * 				'field' => 'columnName',
	 * 				'value' => 0
	 * 			]
	 * 		],
	 * 		['gt' => [
	 * 				'field' => 'columnName',
	 * 				'value' => 0
	 * 			]
	 * 		],
	 * 		['between' => [
	 * 				'field' => 'columnName',
	 * 				'from' => 0
	 * 				'to' => 0
	 * 			]
	 * 		],
	 * 		['in' => [
	 * 				'field' => 'columnName',
	 * 				'values' => [values, values,....]
	 * 			]
	 * 		],
	 * 		['notin' => [
	 * 				'field' => 'columnName',
	 * 				'values' => [values, values,....]
	 * 			]
	 * 		],
	 * 		['like' => [
	 * 				'field' => 'columnName',
	 * 				'value' => value
	 * 			]
	 * 		],
	 * 		['raw' => [
	 * 				'statement' => 'columnName',
	 * 			]
	 * 		],
	 * 		field_name REGEXP '"key_name":"([^"])key_word([^"])"'
	 * 		['json' => [
	 * 				'field' => 'columnName',
	 * 				'value' => value,
	 * 				'keyName' => 'key_name',
	 * 			]
	 * 		],
	 * ];
	 *
	 * @param string $sort desc|asc
	 * @return Illuminate\Database\Eloquent\Builder
	 */
	public function scopeFilter($query, $filters = null)
	{
		if(!empty($filters) && is_array($filters))
		{
			foreach ($filters as $attribute => $filter)
			{
				if(is_callable($filter))
				{
					$query->where($filter);
					continue;
				}
				if(!is_array($filter))
				{
					$query->where($attribute, '=', $filter);
					continue;
				}

				foreach ($filter as $k => $v)
				{
					$k = zbase_data_get($k);
					if(is_array($v))
					{
						foreach ($v as $vK => $vV)
						{
							if($vV instanceof \Closure)
							{
								$v[$vK] = zbase_data_get($vV);
							}
							else
							{
								$v[$vK] = $vV;
							}
						}
					}
					switch (strtolower($k))
					{
						case 'between':
							if(isset($v['from']) && isset($v['to']))
							{
								$query->whereBetween($v['field'], array($v['from'], $v['to']));
							}
							break;
						case 'in':
							if(is_array($v['values']))
							{
								$query->whereIn($v['field'], $v['values']);
							}
							break;
						case 'notin':
							if(is_array($v['values']))
							{
								$query->whereNotIn($v['field'], $v['values']);
							}
							break;
						case 'like':
							if(isset($v['value']))
							{
								$query->where($v['field'], 'LIKE', $v['value']);
							}
							break;
						case 'gt':
							if(isset($v['value']))
							{
								$query->where($v['field'], '>', $v['value']);
							}
							break;
						case 'gte':
							if(isset($v['value']))
							{
								$query->where($v['field'], '>=', $v['value']);
							}
							break;
						case 'lt':
							if(isset($v['value']))
							{
								$query->where($v['field'], '<', $v['value']);
							}
							break;
						case 'lte':
							if(isset($v['value']))
							{
								$query->where($v['field'], '<=', $v['value']);
							}
							break;
						case 'eq':
							if(isset($v['value']))
							{
								$query->where($v['field'], '=', $v['value']);
							}
							break;
						case 'neq':
							if(isset($v['value']))
							{
								$query->where($v['field'], '!=', $v['value']);
							}
							break;
						case 'json':
							if(isset($v['value']) && isset($v['keyName']))
							{
								$query->where($v['field'], 'REGEXP', '"' . $v['keyName'] . '":"' . $v['value'] . '"');
							}
							break;
						case 'raw':
							if(isset($v['statement']))
							{
								$query->whereRaw($v['statement']);
							}
							break;
						case 'json':
							if(isset($v['value']) && isset($v['keyName']))
							{
								$query->where($v['field'], 'REGEXP', '"' . $v['keyName'] . '":"' . $v['value'] . '"');
							}
							break;
						case 'notnull':
							if(isset($v['field']))
							{
								$query->whereNotNull($v['field']);
							}
						case 'isnull':
							if(isset($v['field']))
							{
								$query->whereNull($v['field']);
							}
							break;
						case 'notempty':
							if(isset($v['field']))
							{
								function($query){
									$column = $v['field'];
									return $query
										->where($v['field'], '<>', "''")
										->orWhereNotNull($column);
								};
							}
						default;
					}
				}
			}
		}
		return $query;
	}

}
