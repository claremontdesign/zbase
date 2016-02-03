<?php

namespace Zbase\Traits;

/**
 * Zbase-Url
 *
 * ReUsable Traits - Url
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Url.php
 * @project Zbase
 * @package Zbase/Traits
 */
trait Url
{

	/**
	 * The URL
	 * @var string
	 */
	protected $href = null;

	/**
	 * Check if user has access
	 *
	 * @return boolean
	 */
	public function getUrl()
	{
		if(property_exists($this, 'url'))
		{
			if(!is_null($this->href))
			{
				return $this->href;
			}
		}
		if(property_exists($this, 'attributes'))
		{
			if(!empty($this->attributes['url']))
			{
				$this->href = $this->createUrl($this->attributes['url']);
			}
		}
		return $this->href;
	}

	/**
	 * Create URL based from configuration
	 *
	 * @param array $config
	 * @return string
	 */
	public function createUrl($config)
	{
		return zbase_url_from_config($config);
	}

}
