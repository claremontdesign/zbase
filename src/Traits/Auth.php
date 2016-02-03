<?php

namespace Zbase\Traits;

/**
 * Zbase-Auth
 *
 * ReUsable Traits - Auth
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Auth.php
 * @project Zbase
 * @package Zbase/Traits
 */
trait Auth
{

	/**
	 * Check if user has access
	 *
	 * @return boolean
	 */
	public function hasAccess()
	{
		if(property_exists($this, 'access'))
		{
			return $this->access;
		}
		if(property_exists($this, 'attributes'))
		{
			if(!empty($this->attributes['access']))
			{
				return $this->attributes['access'];
			}
		}
		return false;
	}

}
