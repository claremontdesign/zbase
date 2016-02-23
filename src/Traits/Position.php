<?php

namespace Zbase\Traits;

/**
 * Reusable Position Methods
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Position.php
 * @project Zbase
 * @package Zbase/Traits
 */
trait Position
{

	/**
	 * The position
	 * @var integer
	 */
	protected $position = null;

	/**
	 * Sort Objects by position
	 * @param array $collection
	 * @return Collection
	 */
	public function sortPosition($collection)
	{
		return $collection->sortBy(function ($itm) {
					return $itm->getPosition();
		});
	}

	/**
	 * Return the position
	 *
	 * @return integer
	 */
	public function getPosition()
	{
		if(is_null($this->position))
		{
			$attributes = [];
			if(method_exists($this, 'getAttributes'))
			{
				$attributes = $this->getAttributes();
			}
			$this->position = zbase_data_get($attributes, 'position', 0);
		}
		return $this->position;
	}

	/**
	 * Set the Position
	 *
	 * @param integer $position
	 */
	public function setPosition($position)
	{
		$this->position = $position;
	}

	/**
	 * Return the position
	 *
	 * @return integer
	 */
	public function position()
	{
		return $this->getPosition();
	}

}
