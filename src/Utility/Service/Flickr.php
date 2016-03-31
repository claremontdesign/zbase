<?php

namespace Zbase\Utility\Service;

/**
 * Flickr PHP API class
 * API Documentation: http://www.flickr.com/services/api/
 * Documentation and usage in README file
 *
 * @author Jonas De Smet - Glamorous
 * @date 02.05.2010
 * @copyright Jonas De Smet - Glamorous
 * @version 0.6.1
 * @license BSD http://www.opensource.org/licenses/bsd-license.php
 */
class Flickr
{

	const JSON = 'json';
	const XML = 'rest';
	const PHP = 'php_serial';
	const SOAP = 'soap';
	const API_URL = 'https://api.flickr.com/services/rest/';
	const VERSION = '0.6.1';

	/**
	 * The available return formats
	 *
	 * @var array
	 */
	private static $_formats = array(Flickr::JSON, Flickr::XML, Flickr::PHP, Flickr::SOAP);

	/**
	 * The default parameters-array to include with the API-call
	 *
	 * @var array
	 */
	private static $_defaults = array();

	/**
	 * The default format to include with the API-call
	 *
	 * @var const
	 */
	private static $_default_format = Flickr::JSON;

	/**
	 * Default constructor
	 *
	 * @return void
	 */
	final private function __construct()
	{
		// This is a static class
	}

	/**
	 * Set API-key for all requests
	 *
	 * @param string $apikey
	 * @return void
	 */
	public static function setApikey($apikey)
	{
		self::$_defaults['api_key'] = (string) $apikey;
	}

	/**
	 * Set default format for all requests
	 *
	 * @param const Flickr::JSON, Flickr::XML, Flickr::PHP, Flickr::SOAP $format
	 * @return void
	 */
	public static function setFormat($format)
	{
		if(in_array($format, self::$_formats))
		{
			self::$_defaults['format'] = $format;
			self::$_default_format == $format;
		}
		else
		{
			self::$_defaults['format'] = self::$_default_format;
		}
	}

	/**
	 * Makes the call to the API
	 *
	 * @param array $params	parameters for the request
	 * @return mixed
	 */
	public static function makeCall($params)
	{
		$params += self::$_defaults;
		// check if an API-key is provided
		if(!isset($params['api_key']))
		{
			throw new \Zbase\Exceptions\Exception('API-key must be set');
		}
		// check if a method is provided
		if(!isset($params['method']))
		{
			throw new \Zbase\Exceptions\Exception("Without a method this class can't call the API");
		}
		// check if a format is provided
		if(!isset($params['format']))
		{
			$params['format'] = self::$_default_format;
		}
		if($params['format'] == self::JSON)
		{
			$params['nojsoncallback'] = 1;
		}
		$url = Flickr::API_URL . '?' . http_build_query($params, NULL, '&');
		$url = str_replace(array('%5B0%5D','%5B1%5D','%5B2%5D','%5B3%5D','%5B4%5D','%5B5%5D','%5B6%5D'), '', $url);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		$results = curl_exec($ch);
		$headers = curl_getinfo($ch);
		$error_number = (int) curl_errno($ch);
		$error_message = curl_error($ch);
		curl_close($ch);
		// invalid headers
		if(!in_array($headers['http_code'], array(0, 200)))
		{
			throw new \Zbase\Exceptions\Exception('Bad headercode', (int) $headers['http_code']);
		}
		// are there errors?
		if($error_number > 0)
		{
			throw new \Zbase\Exceptions\Exception($error_message, $error_number);
		}
		return $results;
	}

	/**
	 * Find Photos by Tags
	 * @param array $tags Array of tags
	 */
	public static function findByTags($tags, $perPage = 1, $page = 1)
	{
		if(!empty($tags))
		{
			self::setApikey(zbase_config_get('service.flickr.apikey', 'cfe9310079925c6010c325dc03dba1ff'));
			self::setFormat(self::JSON);
			$params = array(
				'method' => 'flickr.photos.search',
				'tags' => $tags,
				'per_page' => $perPage,
				'page' => $page
			);
			$photos = self::makeCall($params);
			if(!empty($photos))
			{
				$photos = json_decode($photos);
				if(!empty($photos->photos))
				{
					$ph = [];
					foreach ($photos->photos->photo as $photo)
					{
						$p = [];
						$p['url'] = 'https://farm' . $photo->farm . '.staticflickr.com/' . $photo->server . '/' . $photo->id . '_' . $photo->secret . '.jpg';
						$p['title'] = $photo->title;
						$ph[] = $p;
					}
					return $ph;
				}
			}
		}
		return null;
	}

}
