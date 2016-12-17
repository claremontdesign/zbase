<?php

namespace Zbase\Utility;

/**
 * RemoteResource
 *
 * Description of RemoteResource
 */
use Zbase\Exceptions\CurlException;

class RemoteResource
{

	// <editor-fold defaultstate="collapsed" desc="Property Definitions">
	/**
	 * The URL passed to the constructor.
	 * @var string
	 */
	protected $url;

	/**
	 * The cURL error description.
	 * @var string
	 */
	protected $error;

	/**
	 * Raw data retrieved by cURL.
	 * @var string
	 */
	protected $data;

	/**
	 * The timeout passed to the constructor.
	 * @var integer
	 */
	protected $timeout;

	/**
	 * The cURL error number.
	 * @var integer
	 */
	protected $errno;

	/**
	 * Request parameters used.
	 * @var array
	 */
	protected $params;

	/**
	 * Request info from cURL.
	 * @var array
	 */
	protected $info;

	/**
	 * The cURL resource handle.
	 * @var resource
	 */
	protected $ch = NULL;

	/**
	 * Initialized
	 * @var bool
	 */
	protected $initialized = FALSE;

	/**
	 * If this parameter is true, the detail message is provided.
	 * @var boolean
	 */
	protected $provideDetailedMessage = FALSE;

	// </editor-fold>

	/**
	 * Constructor
	 *
	 * @param string $url The URL of the remote resource.
	 * @param integer $timeout The timeout in seconds.
	 */
	public function __construct($url, $timeout = 30)
	{
		$this->url = $url;
		$this->timeout = $timeout;
		$this->params = array();
		$this->info = array();
		$this->errno = 0;
		$this->error = '';
		$this->data = '';
		$this->initialized = FALSE;
		$this->init();
	}

	/**
	 * Initializes the cURL resource handle.
	 */
	protected function init()
	{
		$this->ch = curl_init($this->url);
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($this->ch, CURLINFO_HEADER_OUT, 1);
		curl_setopt($this->ch, CURLOPT_TIMEOUT, $this->timeout);

		$this->initialized = TRUE;
	}

	/**
	 * Closes the cURL resource and marks as deinitialized.
	 */
	protected function close()
	{
		curl_close($this->ch);
		$this->ch = NULL;
		$this->initialized = FALSE;
	}

	/**
	 * Set a cURL option for the request.
	 *
	 * @param int $option The CURLOPT_XXX option to set.
	 * @param mixed $value The value to set.
	 * @return bool TRUE on success, FALSE on failure.
	 */
	public function setopt($option, $value)
	{
		return curl_setopt($this->ch, $option, $value);
	}

	/**
	 * Set cURL options for the request.
	 *
	 * @param array $options An array of cURL options.
	 * @return bool TRUE on success, FALSE on failure.
	 */
	public function setopt_array($options)
	{
		return curl_setopt_array($this->ch, $options);
	}

	/**
	 * Make a get request with parameters passed as an array.
	 *
	 * @param array $params The parameters to pass in the request.
	 * @return string The returned data.
	 * @throws CurlException
	 */
	public function get($params = array())
	{
		if(!empty($params))
		{
			$this->params = $params;
			if(strpos($this->url, '?') === FALSE)
			{
				$this->url .= '?';
			}
			elseif(substr($this->url, -1) == '?')
			{
				// Do nothing
			}
			else
			{
				$this->url .= '&';
			}

			$parts = array();
			foreach ($params as $k => $v)
			{
				$parts[] = $k . '=' . urlencode($v);
			}
			$this->url .= implode('&', $parts);
		}
		$this->setopt(CURLOPT_URL, $this->url);
		$this->data = curl_exec($this->ch);
		$this->info = curl_getinfo($this->ch);
		$this->close();
		return $this->data;
	}

	/**
	 * Make a POST request and get the returned data.
	 *
	 * @param array|string $params Body of the POST request.
	 * @return string The returned data.
	 * @throws CurlException
	 */
	public function post($vars, $timeout = 30, $assoc = FALSE, $depth = 512, $options = 0)
	{
		$this->params = $vars;
		if(!empty($vars) && is_array($vars))
		{
			$parts = array();
			foreach ($vars as $k => $v)
			{
				if(is_array($v))
				{
					foreach ($v as $val)
					{
						$parts[] = $k . '[]=' . urlencode($val);
					}
				}
				else
				{
					$parts[] = $k . '=' . urlencode($v);
				}
			}
			$params = implode('&', $parts);
		}
		curl_setopt($this->ch, CURLOPT_POST, TRUE);
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, $params);
		$this->data = curl_exec($this->ch);
		$this->info = curl_getinfo($this->ch);
		if(curl_errno($this->ch) !== 0 || substr($this->info['http_code'], 0, 1) != '2')
		{
			$this->errno = curl_errno($this->ch);
			$this->error = curl_error($this->ch);
			$this->close();
			throw new CurlException($this);
		}
		$this->close();

		return $this->data;
	}

	/**
	 * Execute a PUT request
	 *
	 * @param array $params Key => value parameters
	 * @return string
	 * @throws CurlException
	 */
	public function put(array $params = array())
	{
		$this->params = $params;

		if(!empty($params) && is_array($params))
		{
			$params = http_build_query($params);
		}

		curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, $params);

		$this->data = curl_exec($this->ch);
		$this->info = curl_getinfo($this->ch);

		if(curl_errno($this->ch) !== 0 || substr($this->info['http_code'], 0, 1) != '2')
		{
			$this->errno = curl_errno($this->ch);
			$this->error = curl_error($this->ch);
			$this->close();
			throw new CurlException($this);
		}

		$this->close();

		return $this->data;
	}

	// <editor-fold defaultstate="collapsed" desc="Getter Methods">

	/**
	 * Get the data from curl call
	 * @return array $this->data
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * Get the info
	 * @return array $this->info
	 */
	public function getInfo()
	{
		return $this->info;
	}

	/**
	 * Get the url for curl call
	 * @return string $this->url
	 */
	public function getUrl()
	{
		return $this->url;
	}

	/**
	 * Get the error
	 * @return string $this->error
	 */
	public function getError()
	{
		return $this->error;
	}

	/**
	 * Get the error number
	 * @return integer $this->errno
	 */
	public function getErrno()
	{
		return $this->errno;
	}

	/**
	 * Get the timeout duration
	 * @return integer $this->timeout
	 */
	public function getTimeout()
	{
		return $this->timeout;
	}

	/**
	 * Get the parameters for curl call
	 * @return array $this->params
	 */
	public function getParams()
	{
		return $this->params;
	}

	/**
	 * getter for provideDetailedMessage parameter
	 * @param boolean $provideDetailedMessage
	 */
	public function getProvideDetailedMessage()
	{
		return $this->provideDetailedMessage;
	}

	/**
	 * setter for provideDetailedMessage parameter
	 * @param boolean $provideDetailedMessage
	 */
	public function setProvideDetailedMessage($provideDetailedMessage)
	{
		$this->provideDetailedMessage = $provideDetailedMessage;
	}

	// </editor-fold>
}
