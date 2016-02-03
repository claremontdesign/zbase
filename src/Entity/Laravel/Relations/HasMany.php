<?php

namespace Zbase\Entity\Laravel\Relations;

use Illuminate\Database\Eloquent\Relations\HasMany as LaravelHasMany;
use Zbase\Traits;

class HasMany extends LaravelHasMany
{

	use Traits\Cache;

	/**
	 * Get the results of the relationship.
	 *
	 * @return mixed
	 */
	public function getResults()
	{
		$thisObject = $this;
		return zbase_cache(
				$this->cacheKey($this), function() use ($thisObject){
			return $thisObject->query->get();
			}, [$this->parent->getTable()]
		);
	}

}
