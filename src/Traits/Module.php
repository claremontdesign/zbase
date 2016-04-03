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
 */
trait Module
{

	/**
	 * The Attributes
	 *
	 * @var array
	 */
	protected $module;

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

	public function controllerIndex()
	{
		if(!$this->getModule()->hasAccess())
		{
			if(zbase_auth_has())
			{
				return $this->unathorized(_zt('You don\'t have enough access to the resource.'));
			}
			else
			{
				return redirect()->to(zbase_url_from_route('login'));
			}
		}
		/**
		 * Check for widgets
		 */
		$action = $this->getRouteParameter('action', 'index');
		$isAjax = zbase_request_is_ajax();
		if($isAjax)
		{
			$action = 'json-' . $action;
			$htmls = [];
		}
		$widgets = $this->getModule()->pageProperties($action)->widgetsByControllerAction($action);
		if(empty($widgets))
		{
			return zbase_abort(404);
		}
		foreach ($widgets as $widget)
		{
			if($widget instanceof \Zbase\Widgets\ControllerInterface)
			{
				$v = $widget->validateWidget();
				if($v instanceof \Illuminate\Contracts\Validation\Validator)
				{
					if($isAjax)
					{
						zbase()->json()->addVariable('errors', $v->errors()->getMessages());
					}
					else
					{
						return redirect()->to($this->getRedirectUrl())
										->withInput(zbase_request_inputs())
										->withErrors($v->errors()->getMessages());
					}
				}
				$ret = $widget->controller($this->getRouteParameter('action', 'index'));
				if($ret instanceof \Illuminate\Http\RedirectResponse)
				{
					if($isAjax)
					{
						zbase()->json()->addVariable('redirect', $ret->getTargetUrl());
					}
					else
					{
						return $ret;
					}
				}
				if($isAjax)
				{
					$htmls[$widget->id()] = $widget->render();
				}
			}
		}
		if(!empty($isAjax))
		{
			zbase()->json()->addVariable('html', $htmls);
		}
		else
		{
			return $this->view(zbase_view_file('module.index'), array('module' => $this->getModule(), 'widgets' => $widgets));
		}
	}

}
