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

	/**
	 * https://developers.google.com/maps/documentation/geocoding/intro
	 * Convert an Address to LatLng
	 * 	$array = [
	 * 		api => google
	 * 		apiKey
	 * 		address1
	 * 		address2
	 * 		city
	 * 		state
	 * 		country
	 * 		zip
	 * ];
	 * @return array lat|lng
	 */
	/**
	 *
		$address = [
			'address1' => '1600 Amphitheatre Parkway',
			'address2' => '',
			'city' => 'Mountain View',
			'state' => 'CA ',
			'zip' => '94043',
			'country' => 'USA',
		];
		dd(\Zbase\Utility\Geo::geocodeAddressToLatLng($address));
	 */
	public static function geocodeAddressToLatLng($array)
	{
		$api = !empty($array['api']) ? $array['api'] : 'google';
		$apiKey = !empty($array['apiKey']) ? $array['apiKey'] : zbase_config_get('geo.' . $api . '.geocoding.apikey', false);
		if(!empty($api) && !empty($apiKey))
		{
			$addresses = [];
			if(!empty($array['address1']))
			{
				$addresses[] = $array['address1'];
			}
			if(!empty($array['address2']))
			{
				$addresses[] = $array['address2'];
			}
			if(!empty($array['city']))
			{
				$addresses[] = $array['city'];
			}
			if(!empty($array['state']))
			{
				$addresses[] = $array['state'] . ' ' . (!empty($array['zip']) ? $array['zip'] : '');
			}
			if(!empty($array['country']))
			{
				$addresses[] = $array['country'];
			}
			$url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode(implode(', ', $addresses)) . '&key=' . $apiKey;
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			$response = curl_exec($ch);
			curl_close($ch);
			return self::extractGoogleMapAddressFromGeocoding(json_decode($response));
			//$remote = new RemoteJSON($url);
			//return self::extractGoogleMapAddressFromGeocoding($remote->get());
		}
	}

	/**
	 * Extract Google Map Address information from Geocoding result
	 * @param string $json
	 * @return array
	 */
	public static function extractGoogleMapAddressFromGeocoding($json)
	{
		if(empty($json))
		{
			return false;
		}
		if(!empty($json->results[0]))
		{
			$address = [];
			foreach ($json->results[0]->address_components as $add)
			{
				if(isset($add->types[0]) && !empty($add->types[0]))
				{
					$address[$add->types[0]] = $add->short_name;
					$address[$add->types[0] . '_longname'] = $add->long_name;
				}
			}
			$return = [];
			if(!empty($json->results[0]->formatted_address))
			{
				$return['formatted_address'] = $json->results[0]->formatted_address;
			}
			if(!empty($json->results[0]->geometry->location_type))
			{
				$return['location_type'] = $json->results[0]->geometry->location_type;
			}
			if(!empty($json->results[0]->geometry->location->lat))
			{
				$return['lat'] = $json->results[0]->geometry->location->lat;
			}
			if(!empty($json->results[0]->geometry->location->lng))
			{
				$return['lng'] = $json->results[0]->geometry->location->lng;
			}
			if(!empty($address['route']))
			{
				$return['address'] = $address['route'];
			}
			if(isset($address['street_number']) && !empty($address['street_number']))
			{
				if(!empty($return['address']))
				{
					$return['address'] = $address['street_number'] . ' ' . $return['address'];
				}
				else
				{
					$return['address'] = $address['street_number'];
				}
			}
			if(isset($address['locality']) && !empty($address['locality']))
			{
				$return['city'] = $address['locality'];
			}
			if(!empty($address['administrative_area_level_1']))
			{
				$return['state'] = $address['administrative_area_level_1'];
				if(!empty($address['administrative_area_level_1_longname']))
				{
					$return['state_name'] = $address['administrative_area_level_1_longname'];
				}
			}
			if(!empty($address['country']))
			{
				$return['countryCode'] = $address['country'];
				if(!empty($address['country_longname']))
				{
					$return['country'] = $address['country_longname'];
				}
			}
			return $return;
		}
		return FALSE;
	}

}
