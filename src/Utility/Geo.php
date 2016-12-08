<?php

namespace Zbase\Utility;

class Geo
{

	/**
	 * Country Code to Name
	 * @param string $countryCode the Country Code
	 */
	public static function countryCodeToName($countryCode)
	{
		$countries = require zbase_path_library('Geo/countries.php');
		if(!empty($countries) && !empty($countries[strtoupper($countryCode)]))
		{
			return $countries[strtoupper($countryCode)];
		}
		return null;
	}

	/**
	 * CountryName to Country Code
	 * @param type $countryName
	 */
	public static function countryNameToCountryCode($countryName)
	{
		$countries = require zbase_path_library('Geo/countries.php');
		if(!empty($countries))
		{
			foreach ($countries as $code => $name)
			{
				if(strtolower($countryName) == strtolower($name))
				{
					return $code;
				}
			}
		}
		return null;
	}

}
