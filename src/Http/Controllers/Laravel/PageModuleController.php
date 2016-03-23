<?php

namespace Zbase\Http\Controllers\Laravel;

/**
 * PageModuleController
 *
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file PageModuleController.php
 * @project Zbase
 * @package Zbase\Http\Controllers
 */
use Zbase\Http\Controllers\Laravel\Controller;
use Zbase\Interfaces;
use Zbase\Traits;

class PageModuleController extends Controller implements Interfaces\AttributeInterface
{

	use Traits\Attribute,
	 Traits\Module;

	public function index()
	{
		if(!$this->getModule()->hasAccess())
		{
			return $this->unathorized(_zt('You don\'t have enough access to the resource.'));
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


	public function image()
	{

	}
}
