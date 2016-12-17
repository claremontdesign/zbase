<?php

namespace Zbase\Utility\RemoteResource;

/**
 * RemoteJSON
 *
 * This file defines the RemoteJSON class, which extends RemoteResource to
 * include JSON processing via json_decode.
 *
 */

/**
 * RemoteJSON
 *
 * Extends the RemoteResource class to automatically handle JSON data.  Instead
 * of returning the raw data from cURL, it automatically parses JSON and returns
 * an objectof type stdClass or an array when successful.
 */
class JSON extends \Zbase\Utility\RemoteResource
{

	/**
	 * error type
	 * @var int
	 */
	protected $errtype = 0;

	const ERRTYPE_CURL = 1;
	const ERRTYPE_SIMPLEXML = 2;
	const ERRTYPE_JSON = 3;

	/**
	 * Make the request and get the JSON object.
	 *
	 * @param int $timeout The timeout in seconds.
	 * @return mixed The PHP representation of the decoded JSON on success, FALSE on failure.
	 */
	public function get($timeout = 30, $assoc = FALSE, $depth = 512, $options = 0)
	{
		$vals = array($timeout);
		parent::get($vals);
		if($this->errno != 0)
		{
			return FALSE;
		}

		$this->initialized = FALSE;

		$data = json_decode($this->data, $assoc);
		if(is_null($data))
		{
			$this->errno = 1;
			$this->error = 'Error decoding JSON';
			$this->errtype = self::ERRTYPE_JSON;
			return FALSE;
		}

		$this->errno = 0;
		$this->error = '';
		$this->errtype = 0;

		return $data;
	}

	/**
	 * Make a get request with parameters passed as an array.
	 *
	 * @param array $params The parameters to pass in the request.
	 * @param integer $timeout The timeout in seconds.
	 * @return mixed The PHP representation of the decoded JSON on success, FALSE on failure
	 */
	public function getWithParams($params, $timeout = 30, $assoc = FALSE, $depth = 512, $options = 0)
	{
		parent::get($params, $timeout);
		if($this->errno != 0)
		{
			return FALSE;
		}

		$this->initialized = FALSE;

		$data = json_decode($this->data, $assoc);
		if(is_null($data))
		{
			$this->errno = 1;
			$this->error = 'Error decoding JSON';
			$this->errtype = self::ERRTYPE_JSON;
			return FALSE;
		}
		$this->errno = 0;
		$this->error = '';
		$this->errtype = 0;

		return $data;
	}

	/**
	 * Make a POST request and get the returned data.
	 *
	 * @param string|array $vars Body of the POST request.
	 * @param type $timeout The timeout in seconds.
	 * @return mixed The PHP representation of the decoded JSON on success, FALSE on failure.
	 */
	public function post($vars, $timeout = 30, $assoc = FALSE, $depth = 512, $options = 0)
	{
		parent::post($vars, $timeout);
		if($this->errno != 0)
		{
			return FALSE;
		}

		$this->initialized = FALSE;

		$data = json_decode($this->data, $assoc);
		if(is_null($data))
		{
			$this->errno = 1;
			$this->error = 'Error decoding JSON';
			$this->errtype = self::ERRTYPE_JSON;
			return FALSE;
		}
		$this->errno = 0;
		$this->error = '';
		$this->errtype = 0;

		return $data;
	}

	/**
	 * Get the type of error that occurred.
	 *
	 * @return int The type of error that occurred.  One of the RemoteJSON::ERRTYPE_* constants.
	 */
	public function get_error_type()
	{
		return $this->errtype;
	}

	/**
	 * Convert Array to XML
	 * @param array $data The Array Data
	 * @param string $nodeName The Node/Root name
	 * @return XMLstring
	 */
	public function toXml($data, $nodeName)
	{
		$data = json_decode(str_replace(array('#', '@'), '', json_encode($data)), TRUE);
		$xml = Array2XML::createXML($nodeName, $data);
		return new \SimpleXMLElement($xml->saveXML());
	}
}
