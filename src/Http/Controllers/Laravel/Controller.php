<?php

namespace Zbase\Http\Controllers\Laravel;

/**
 *
 * Base Controller
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Controller.php
 * @project Zbase
 * @package Zbase\Models\View
 */

/**
 * Abstract Controller
 */
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController implements \Zbase\Interfaces\ControllerInterface
{

	use AuthorizesRequests,
	 DispatchesJobs,
	 ValidatesRequests;

	/**
	 * The Zbase
	 * @var \Zbase\Zbase
	 */
	protected $zbase = null;

	/**
	 * Controller name
	 * @var string
	 */
	protected $name = null;

	/**
	 * Controller action name
	 * @var string
	 */
	protected $actionName = null;

	/**
	 * Route parameters
	 * @var array
	 */
	protected $routeParameters = [];

	/**
	 * Not Found
	 *
	 * @param string $msg
	 * @return type
	 */
	public function notfound($msg = null)
	{
		return zbase_abort(404, $msg);
	}

	/**
	 * UnAuthorized
	 *
	 * @param string $msg
	 * @return type
	 */
	public function unathorized($msg = null)
	{
		return zbase_abort(401, $msg);
	}

	/**
	 * Page Error
	 *
	 * @param string $msg
	 * @return type
	 */
	public function error($msg = null)
	{
		return zbase_abort(505, $msg);
	}

	/**
	 * Return Zbase
	 * @return \Zbase\Zbase
	 */
	public function zbase()
	{
		return $this->zbase;
	}

	/**
	 * REturn a content based from a view file
	 * @param string $file
	 * @return \Illuminate\View\View
	 */
	public function view($file, $params = [])
	{
		return zbase_view_render($file, $params);
	}

	/**
	 * Set \Zbase\Zbase
	 * @param \Zbase\Zbase $zbase
	 */
	public function setZbase(\Zbase\Zbase $zbase)
	{
		$this->zbase = $zbase;
	}

	/**
	 * @see $name
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @see $actionName
	 * @return string
	 */
	public function getActionName()
	{
		return $this->actionName;
	}

	/**
	 * @see $name
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @see $actionName
	 * @param string $actionName
	 */
	public function setActionName($actionName)
	{
		$this->actionName = $actionName;
		return $this;
	}

	/**
	 * Set the route parameters
	 * @param array $routeParameters
	 * return $this;
	 */
	public function setRouteParameters($routeParameters)
	{
		$this->routeParameters = $routeParameters;
		return $this;
	}

	/**
	 * Return the route parameters
	 * @return array
	 */
	public function getRouteParameters()
	{
		return $this->routeParameters;
	}

	/**
	 * Return a route parameter by $key
	 * @param string $key
	 * @param strings $default
	 * @return mixed
	 */
	public function getRouteParameter($key, $default)
	{
		if(!empty($this->routeParameters[$key]))
		{
			return $this->routeParameters[$key];
		}
		return $default;
	}

	/**
	 * Check if POSTing
	 *
	 * @return boolean
	 */
	public function isPost()
	{
		return zbase_is_post();
	}

	/**
	 * Check if request is from ajax
	 *
	 * @return boolean
	 */
	public function isAjax()
	{
		return zbase_request_is_ajax();
	}

	/**
	 * Check if to return JSON
	 * @return boolean
	 */
	public function isJson()
	{
		return zbase_is_json();
	}

	/**
	 * Validate
	 *
	 * @param array $inputs
	 * @param array $rules
	 * @param array $messages
	 * @return array
	 */
	public function validateInputs($inputs, $rules, $messages)
	{
		$validator = \Validator::make($inputs, $rules, $messages);
		if($validator->fails())
		{
			zbase_alert(\Zbase\Zbase::ALERT_ERROR, $validator->messages());
			return false;
		}
		return true;
	}

	/**
	 * Format the validation errors to be returned.
	 *
	 * @param  \Illuminate\Contracts\Validation\Validator  $validator
	 * @return array
	 */
	protected function formatValidationErrors(\Illuminate\Contracts\Validation\Validator $validator)
	{
		zbase_alert(\Zbase\Zbase::ALERT_ERROR, $validator->getMessageBag(), ['formvalidation' => true]);
		return $validator->errors()->getMessages();
	}

	/**
	 * Add Message
	 *
	 * @param string $type
	 * @param string $msg
	 * @param array $options
	 * @return void
	 */
	public function message($type, $msg, $options = [])
	{
		zbase_alert($type, $msg, $options);
	}
}
