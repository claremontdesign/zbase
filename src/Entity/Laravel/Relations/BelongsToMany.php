<?php

namespace Zbase\Entity\Laravel\Relations;

use Illuminate\Database\Eloquent\Relations\BelongsToMany as LaravelBelongsToMany;
use Zbase\Traits;

class BelongsToMany extends LaravelBelongsToMany
{

	use Traits\Cache;

	/**
	 * Execute the query as a "select" statement.
	 *
	 * @param  array  $columns
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function get($columns = ['*'])
	{
		$columns = $this->query->getQuery()->columns ? [] : $columns;
		$thisObject = $this;
		return zbase_cache(
				$this->cacheKey($this), function() use ($thisObject, $columns){
			$select = $thisObject->getSelectColumns($columns);
			$models = $thisObject->query->addSelect($select)->getModels();
			$thisObject->hydratePivotRelation($models);
			if(count($models) > 0)
			{
				$models = $thisObject->query->eagerLoadRelations($models);
			}
			return $thisObject->related->newCollection($models);
			}, [$this->parent->getTable()]
		);
	}


}
