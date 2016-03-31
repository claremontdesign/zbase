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
		$widgets = $this->getModule()->pageProperties($action)->widgetsByControllerAction($action);
		foreach ($widgets as $widget)
		{
			if($widget instanceof \Zbase\Widgets\ControllerInterface)
			{
				$v = $widget->validateWidget();
				if($v instanceof \Illuminate\Contracts\Validation\Validator)
				{
					return redirect()->to($this->getRedirectUrl())
									->withInput(zbase_request_inputs())
									->withErrors($v->errors()->getMessages());
				}
				$ret = $widget->controller($this->getRouteParameter('action', 'index'));
				if($ret instanceof \Illuminate\Http\RedirectResponse)
				{
					return $ret;
				}
			}
		}
		return $this->view(zbase_view_file('module.index'), array('module' => $this->getModule(), 'widgets' => $widgets));
	}

}
