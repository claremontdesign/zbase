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
	 * Mobile VarName to use
	 * @var string
	 */
	protected $mobileVarName = 'zMobile';

	/**
	 * If to use a mobile Theme
	 * @var null|boolean
	 */
	protected $isMobileTheme = null;

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

	/**
	 * Check if to use a mobile theme
	 *
	 * @return boolean
	 */
	public function isMobileTheme()
	{
		if(is_bool($this->isMobileTheme))
		{
			return $this->isMobileTheme;
		}

		if(!env('APP_MOBILE_THEME', false))
		{
			return false;
		}
		/**
		 * check if really a mobile
		 * check cookie
		 * check session
		 * check user theme to use
		 */
		if(env('APP_ENV_MOBILE', false))
		{
			return true;
		}
		if($this->detector()->isMobile())
		{
			$this->isMobileTheme = true;
			return true;
		}
		if($this->detector()->isTablet())
		{
			$this->isMobileTheme = true;
			return true;
		}
		if(!empty(zbase_cookie($this->mobileVarName)))
		{
			$this->isMobileTheme = true;
			return true;
		}
		if(!empty(zbase_session_has($this->mobileVarName)) && !empty(zbase_session_get($this->mobileVarName)))
		{
			$this->isMobileTheme = true;
			return true;
		}
		if(!empty(zbase_request_query_input($this->mobileVarName)))
		{
			if(!empty(zbase_request_query_input($this->mobileVarName . 'Cookie')))
			{
				zbase_cookie_forever($this->mobileVarName, 1);
			}
			$this->isMobileTheme = true;
			return true;
		}
		$this->isMobileTheme = false;
		return false;
	}

	/**
	 * Check if theme is Angular
	 * @return boolean
	 */
	public function isAngular()
	{
		return zbase_is_angular();
	}

}
