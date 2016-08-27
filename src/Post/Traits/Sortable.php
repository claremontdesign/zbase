<?php

namespace Zbase\Post\Traits;

/**
 * Zbase-Entity
 *
 * Sortable Trait
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Sortable.php
 * @project Zbase
 * @package Zbase/Entity
 */
trait Sortable
{

	/**
	 * Allows all columns on the current database table to be sorted through
	 * query scope
	 *
	 * @param Illuminate\Database\Eloquent\Builder $query
	 * @param string|array $field
	 * 	if array:
	 * 		e.g ['created_at' => 'desc']
	 * 			['created_at' => 'desc', 'id' => 'asc']
	 * 			['created_at' => 'desc', 'id' => 'asc', 'name' => 'asc']
	 * @param string $sort desc|asc
	 * @return Illuminate\Database\Eloquent\Builder
	 */
	public function scopeSort($query, $field = NULL, $sort = NULL)
	{
		if(!empty($field) && is_array($field))
		{
			foreach ($field as $sort => $direction)
			{
				/*
				 * Return the query sorted
				 */
				$query->orderBy($sort, $direction);
			}
			return $query;
		}
		/*
		 * Make sure both the field and sort variables are present
		 */
		if(is_string($field) && is_string($sort))
		{

			/*
			 * Make sure the sort input is equal to asc or desc
			 */
			if(strtolower($sort) === 'asc' || strtolower($sort) === 'desc')
			{
				/*
				 * Return the query sorted
				 */
				return $query->orderBy($field, strtoupper($sort));
			}
		}
	}

}
