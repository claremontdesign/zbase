<?php

namespace Zbase\Traits;

/**
 * Zbase-Status
 *
 * ReUsable Traits - Status
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Status.php
 * @project Zbase
 * @package Zbase/Traits
 */
trait Status
{
	/**
	 * Enabled/Disabled
	 * @var boolean
	 */
	protected $enabled = false;

	/**
	 * Check if enabled
	 *
	 * @return boolean
	 */
	public function enabled()
	{
		if(property_exists($this, 'enable'))
		{
			return $this->enable;
		}
		if(property_exists($this, 'attributes'))
		{
			if(!empty($this->attributes['enable']))
			{
				return (boolean) $this->attributes['enable'];
			}
		}
		return false;
	}

}
