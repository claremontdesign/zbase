<?php

namespace Zbase\Traits;

/**
 * Zbase-Module
 *
 * Reusable Methods Module
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Module.php
 * @project Zbase
 * @package Zbase/Traits
 *
 * domain.com/api/username-key/format=xml|json/module/object/method/paramOne/paramTwo/paramThree/paramFour/paramFive/paramSix
 * zbase.com/api/username/key/json/module/object/method/paramOne/paramTwo/paramThree/paramFour/paramFive/paramSix
 */
trait Api
{

	/**
	 * The Attributes
	 *
	 * @var array
	 */
	protected $module;

	/**
	 * API Username
	 * @var string
	 */
	protected $apiUser = null;

	/**
	 * API Key
	 * @var string
	 */
	protected $apiKey = null;

	/**
	 * The Return format
	 * @var string
	 */
	protected $apiFormat = 'json';

	/**
	 * The resource object to access
	 * @var string
	 */
	protected $apiObject = null;

	/**
	 * The object method to call
	 * @var string
	 */
	protected $apiMethod = null;

	/**
	 * The API Configuration
	 * @var array
	 */
	protected $apiConfiguration = array();

	/**
	 * Array of Errors
	 * @var array
	 */
	protected $apiErrors = array();

	/**
	 * The Parameters
	 * @var array
	 */
	protected $params = [];
	protected $returns = array();

	/**
	 * Set the Module
	 * @param \Zbase\Http\Controllers\Laravel\Module\ModuleInterface $module
	 * @return \Zbase\Http\Controllers\Laravel\BackendModuleController
	 */
	public function setModule(\Zbase\Module\ModuleInterface $module)
	{
		$this->module = $module;
		return $this;
	}

	/**
	 *
	 * @return \Zbase\Module\ModuleInterface
	 */
	public function getModule()
	{
		return $this->module;
	}

	/**
	 * Check for API Acces
	 * @return boolean
	 */
	public function apiAccess()
	{
		return true; //true;
	}

	public function apiIndex()
	{
		$this->apiUser = zbase_route_input('username');
		$this->apiKey = zbase_route_input('key');
		$this->apiFormat = zbase_route_input('format');
		$this->apiModule = zbase_route_input('module');
		$this->apiObject = zbase_route_input('object');
		$this->apiMethod = zbase_route_input('method');
		zbase_response_format_set($this->apiFormat);
		if(!$this->checkModule())
		{
			return $this->notfound();
		}
		/**
		 * Allowed method get|post
		 */
		if(!$this->checkAllowedMethod())
		{
			return $this->methodNotAllowed();
		}
		if(!$this->apiAccess())
		{
			return $this->unathorized();
		}
		return $this->api();
	}

	/**
	 * Check allowed method
	 * @return boolean
	 */
	// <editor-fold defaultstate="collapsed" desc="CheckAllowedMethod">
	public function checkAllowedMethod()
	{
		$requestMethod = zbase_request_method();
		$allowedMethods = !empty($this->apiConfiguration['requestMethod']) ? $this->apiConfiguration['requestMethod'] : ['get'];
		if(!empty($allowedMethods))
		{
			if(is_array($allowedMethods) && !in_array($requestMethod, $allowedMethods))
			{
				return false;
			}
			if(!is_array($allowedMethods) && $allowedMethods != $requestMethod)
			{
				return false;
			}
		}
		return true;
	}

	// </editor-fold>

	/**
	 * Check if we have the module, object and method
	 *
	 * @return booleaan
	 */
	// <editor-fold defaultstate="collapsed" desc="checkModule">
	public function checkModule()
	{
		$module = zbase()->module($this->apiModule);
		if(empty($module))
		{
			return false;
		}
		$this->setModule($module);
		$moduleConfig = $this->getModule()->getConfiguration();
		if(!empty($moduleConfig['api']) && !empty($moduleConfig['api'][$this->apiObject . '.' . $this->apiMethod]))
		{
			$this->apiConfiguration = $moduleConfig['api'][$this->apiObject . '.' . $this->apiMethod];
			if(isset($this->apiConfiguration['enable']) && empty($this->apiConfiguration['enable']))
			{
				return false;
			}
			/**
			 * Check if Object can be created
			 * Check for Object->method
			 */
			if(!class_exists($this->apiConfiguration['class']) || !method_exists($this->apiConfiguration['class'], $this->apiConfiguration['method']))
			{
				return false;
			}
			return true;
		}
		return false;
	}

	// </editor-fold>

	/**
	 * Process the API
	 *
	 *
	 */
	// <editor-fold defaultstate="collapsed" desc="api">
	public function api()
	{
		$validation = $this->validateApi();
		if(!$validation)
		{
			$this->returns['message'] = 'Validation error. Kindly check error messages.';
			$this->returns['errors'] = $this->apiErrors;
		}
		else
		{
			$objectClass = $this->apiConfiguration['class'];
			$objectClassMethod = $this->apiConfiguration['method'];
			$objectResult = $objectClass::$objectClassMethod($this->params);
			/**
			 * Entity
			 */
			if($objectResult instanceof \Zbase\Interfaces\EntityInterface)
			{
				$this->returns['result'] = $objectResult->toArray();
			}
			/**
			 * Array
			 */
			if(is_array($objectResult))
			{
				$this->returns['result'] = $objectResult;
			}
		}
		$this->returns['parameters'] = $this->params;
		zbase()->json()->setVariable('api', $this->returns);
	}

	// </editor-fold>

	/**
	 * Validate the API
	 */
	// <editor-fold defaultstate="collapsed" desc="validateAPI">
	protected function validateApi()
	{
		if(!empty($this->apiConfiguration['params']))
		{
			$notParams = $this->apiConfiguration['notParams'];
			$inputs = zbase_route_inputs();
			unset($inputs['username']);
			unset($inputs['key']);
			unset($inputs['format']);
			unset($inputs['module']);
			unset($inputs['object']);
			unset($inputs['method']);
			$rules = array();
			$messages = array();
			if(zbase_request_is_post())
			{
				$inputs = zbase_request_inputs();
			}
			foreach ($this->apiConfiguration['params'] as $paramName => $param)
			{
				$pRules = array();
				if(!empty($param['validations']))
				{
					foreach ($param['validations'] as $ruleName => $ruleConfig)
					{
						$enable = true;
						$rule = $ruleName;
						if(isset($ruleConfig['enable']))
						{
							$enable = $ruleConfig['enable'];
						}
						if(!empty($enable))
						{
							if(!empty($ruleConfig['text']))
							{
								$rule = zbase_data_get($ruleConfig, 'text');
							}
							$pRules[] = $rule;
							if(!empty($ruleConfig['message']))
							{
								$messages[$paramName . '.' . $ruleName] = $ruleConfig['message'];
							}
						}
					}
				}
				if(!empty($pRules))
				{
					$rules[$paramName] = implode('|', $pRules);
				}
				if(isset($inputs[$paramName]))
				{
					if(!empty($param['varname']))
					{
						$this->params[$param['varname']] = $inputs[$paramName];
					}
					else
					{
						$this->params[$paramName] = $inputs[$paramName];
					}
				}
			}
			if(!empty($notParams))
			{
				foreach ($notParams as $nParam)
				{
					if(isset($this->params[$nParam]))
					{
						unset($this->params[$nParam]);
					}
				}
			}
			$validator = \Validator::make($inputs, $rules, $messages);
			if($validator->fails())
			{
				foreach ($validator->errors()->all() as $msg)
				{
					$this->apiErrors[] = $msg;
				}
				return false;
			}
		}
		return true;
	}

	// </editor-fold>
}
