<?php

namespace Zbase\Traits;

/**
 * Zbase-Cache
 *
 * Reusable Methods Attribute
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Cache.php
 * @project Zbase
 * @package Zbase/Traits
 */

use Illuminate\Database\Eloquent\Relations\Relation;

trait Cache
{

	/**
	 * generate a Cache Key
	 * @param object $relObj
	 * @return type
	 */
	public function cacheKey($relObj)
	{
		$obj = $relObj instanceof Relation ? $relObj : $this;
		$bindings = $relObj instanceof Relation ? $obj->getQuery()->getBindings() : [];
		$statement = $obj->toSql();
		return md5(str_slug($this->getTable() . $statement . serialize($bindings)));
	}
}
