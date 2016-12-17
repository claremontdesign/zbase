<?php

namespace Zbase\Utility\RemoteResource;

/**
 * RemoteXML
 *
 * Description of RemoteXML
 * @package Component
 */
use Zbase\Exceptions\CurlException;
use Zbase\Exceptions\XmlException;

/**
 * RemoteXML
 *
 * Description of RemoteXML
 */
class Xml extends \Zbase\Utility\RemoteResource
{

	/**
	 * Make a get request with parameters passed as an array.
	 *
	 * @param array $params The parameters to pass in the request.
	 * @return SimpleXMLELement The XML object on success
	 * @throws CurlException
	 * @throws XmlException
	 */
	public function get($params = array())
	{
		$result = parent::get($params);
		$result = str_replace(' & ', ' &amp; ', $result);

		libxml_use_internal_errors(TRUE);
		$xml = simplexml_load_string($result, NULL, LIBXML_NOCDATA);
		if($xml === FALSE)
		{
			$errors = libxml_get_errors();
			throw new XmlException($errors, $result);
		}

		return $xml;
	}

	/**
	 * Make a POST request and get the returned data.
	 *
	 * @param array $params Body of the POST request.
	 * @return string|bool The returned data on success
	 * @throws CurlException
	 * @throws XmlException
	 */
	public function post($params = array())
	{
		$result = parent::post($params);

		libxml_use_internal_errors(TRUE);
		$xml = simplexml_load_string($result, NULL, LIBXML_NOCDATA);
		if($xml === FALSE)
		{
			$errors = libxml_get_errors();
			throw new XmlException($errors, $result);
		}

		return $xml;
	}

	/**
	 * Make a PUT request and get the returned data.
	 *
	 * @param array $params Body of the PUT request.
	 * @return string|bool The returned data on success
	 * @throws CurlException
	 * @throws XmlException
	 */
	public function put($params = array())
	{
		$result = parent::put($params);

		libxml_use_internal_errors(TRUE);
		$xml = simplexml_load_string($result, NULL, LIBXML_NOCDATA);
		if($xml === FALSE)
		{
			$errors = libxml_get_errors();
			throw new XmlException($errors, $result);
		}

		return $xml;
	}

}
