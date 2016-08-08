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
	 * The Node name e.g. file or category
	 * For Node generic support
	 * @var string
	 */
	protected $nodeName;

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
	 * The Nodename
	 * @param string $node
	 */
	public function setNode($node)
	{
		$this->nodeName = $node;
		return $this;
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
		$widgetsAction = $action = str_replace('.', '-', $this->getRouteParameter('action', 'index'));
		$requestMethod = zbase_request_method();
		if(!empty($this->nodeName))
		{
			$widgetsAction = $requestMethod . '-node-' . $this->nodeName . '-' . $action;
			$htmls = [];
		}
		$isAjax = zbase_request_is_ajax();
		if($isAjax)
		{
			$widgetsAction = (!empty($this->nodeName) ? $requestMethod . '-node-' . $this->nodeName . '-' : '') . 'json-' . $action;
			$htmls = [];
		}
		$widgets = $this->getModule()->pageProperties($action)->widgetsByControllerAction($widgetsAction);
		zbase()->json()->addVariable('_widget', $this->getModule()->id() . '_' . str_replace('-','',$action));
		if(zbase_is_dev())
		{
			zbase()->json()->addVariable(__METHOD__, $widgetsAction);
			if(zbase_request_is_post())
			{
				zbase()->json()->addVariable('_POST_PARAMETERS', zbase_request_inputs());
			}
			zbase()->json()->addVariable('_ROUTE_PARAMETERS', zbase_route_inputs());
			zbase()->json()->addVariable('_GET_PARAMETERS', zbase_request_query_inputs());
		}
		// dd($this->getModule(), $widgetsAction, $widgets);
		if(empty($widgets))
		{
			return zbase_abort(404);
		}
		foreach ($widgets as $widget)
		{
			if(!empty($this->nodeName))
			{
				zbase()->json()->addVariable('node', ['prefix' => $this->getModule()->nodeNamespace(), 'name' => $this->nodeName, 'support' => 1]);
				$widget->setNodename($this->nodeName)->setNodeSupport(true);
			}
			if($widget instanceof \Zbase\Widgets\ControllerInterface)
			{
				$v = $widget->validateWidget($action);
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
				if($ret instanceof \Zbase\Exceptions\NotFoundHttpException)
				{
					return $this->notFound();
				}
				if($ret instanceof \Zbase\Exceptions\UnauthorizedException)
				{
					return $this->unathorized();
				}
				if($ret instanceof \Zbase\Exceptions\Exception)
				{
					return $this->error();
				}
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
				if(zbase_is_json())
				{
					zbase_response_format_set('json');
					$jsonIndexName = $widget->getWidgetPrefix();
					if(zbase_is_angular())
					{
						if($widget instanceof \Zbase\Widgets\Type\Datatable)
						{
							$angularTemplate = zbase_angular_widget_datatable($this->getModule(), $widget);
							$jsonIndexName = $angularTemplate['serviceName'];
						}
					}
					if(zbase_is_dev())
					{
						zbase()->json()->addVariable('$jsonIndexName', $jsonIndexName);
					}
					zbase()->json()->addVariable($jsonIndexName, $widget->toArray());
				} else {
					if($isAjax)
					{
						$htmls[str_replace('-', '_', $widget->id())] = $widget->render();
					}
				}
				$widget->pageProperties($widgetsAction);
			}
		}
		if(!empty($isAjax))
		{
			zbase()->json()->addVariable('_widgets', 1);
			zbase()->json()->addVariable('html', $htmls);
		}
		else
		{
			return $this->view(zbase_view_file('module.index'), array('module' => $this->getModule(), 'widgets' => $widgets));
		}
	}

}
