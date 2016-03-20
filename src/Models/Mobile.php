<?php

namespace Zbase\Models;

/**
 * Zbase-Model-View
 *
 * Model for the Theme,Templates and anything for the view
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Mobile.php
 * @project Zbase
 * @package Zbase/Model
 */
class Mobile
{

	/**
	 * Mobile Detector
	 * @var \Zbase\Utility\Mobile\Mobile_Detect
	 */
	protected $detector = null;

	/**
	 * The Detector
	 * @return \Zbase\Utility\Mobile\Mobile_Detect
	 */
	public function detector()
	{
		if(!$this->detector instanceof \Zbase\Utility\Mobile\Detect)
		{
			$this->detector = new \Zbase\Utility\Mobile\Detect;
		}
		return $this->detector;
	}

}
