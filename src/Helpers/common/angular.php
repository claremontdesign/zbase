<?php

/**
 * Dx
 *
 * @link http://dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2015 ClaremontDesign/MadLabs-Dx
 * @version 0.0.0.1
 * @since Jul 11, 2016 6:42:51 PM
 * @file angular.php
 * @project Expression project.name is undefined on line 13, column 15 in Templates/Scripting/EmptyPHP.php.
 * @package Expression package is undefined on line 14, column 15 in Templates/Scripting/EmptyPHP.php.
 */

/**
 * check if UI is angular
 * @return boolean
 */
function zbase_is_angular()
{
	return env('UI_THEME_FRAMEWORK', false) == 'angular';
}

/**
 * Check if to serve angular template
 * or to use an angular template
 * @return booleaan
 */
function zbase_is_angular_template()
{
	return zbase_request_query_input('at', false);
}

/**
 * Create Angular URL/Link
 * @param string $name
 * @param array $params
 * @return string
 */
function zbase_angular_url($name, $params = [])
{
	if(is_array($name) && !empty($name['name']))
	{
		$name = $name['name'];
	}
	$home = route('index');
	$url = str_replace($home, '', route($name, $params));
	return '#' . str_replace('/admin/', '/', $url);
}

/**
 * Create angular Route
 * @param string $name
 * @param array $params
 * @return string
 */
function zbase_angular_route($name, $params)
{
	$home = route('index');
	$url = str_replace($home, '', route($name, $params));
	return str_replace('/admin/', '/', $url);
}

/**
 * Create Route TEMPLATE Url
 * @param string $name
 * @param array $params
 * @return string
 */
function zbase_angular_template_url($name, $params)
{
	$url = zbase_url_from_route($name, [], true);
	return str_replace('#/', '/', $url);
}

/**
 * Create ServiceName based on Module and Widget
 *
 * @param Module $module
 * @param Widget $widget
 * @return string;
 */
function zbase_angular_module_servicename($module, $widget)
{
	if(!$module instanceof \Zbase\Module\Module)
	{
		$module = zbase()->module($module);
	}
	if(!$widget instanceof \Zbase\Widgets\Widget)
	{
		$widget = zbase()->widget($widget);
	}
	if($module instanceof \Zbase\Module\Module && $widget instanceof \Zbase\Widgets\Widget)
	{
		$serviceName = ucfirst(zbase_string_camel_case($module->id() . '_datatable_service'));
		return $serviceName;
	}
	return null;
}

/**
 * ScopeName
 * @param type $module
 * @param type $widget
 * @return type
 */
function zbase_angular_module_scopename($module, $widget)
{
	$serviceName = zbase_angular_module_servicename($module, $widget);
	if(!empty($serviceName))
	{
		return strtolower($serviceName) . 'Scope';
	}
	return null;
}

/**
 * Create a Datatable Angular template and script
 * @param type $module
 * @param type $widget
 * @param type $options
 * @return boolean|array
 */
function zbase_angular_widget_datatable($module, $widget, $options = [])
{
	/**
	 * @TODO Cache the output
	 */
	$ret = [];
	if(!$module instanceof \Zbase\Module\Module)
	{
		$module = zbase()->module($module);
	}
	if(!$widget instanceof \Zbase\Widgets\Widget)
	{
		$widget = zbase()->widget($widget);
	}
	if($module instanceof \Zbase\Module\Module && $widget instanceof \Zbase\Widgets\Widget)
	{
		$routeName = $widget->_v('angular.route.name', null);
		$templateFile = $widget->_v('angular.view.file', null);
		$templateFormat = $widget->_v('angular.view.format', null);
		$controllerName = $widget->_v('angular.controller', null);
		$selectedItemUrl = $widget->_v('angular.view.list.url', null);
		$pageTitle = $module->_v('controller.back.action.index.page.title', $module->_v('controller.action.index.page.title', null));
		$templateListingType = $widget->_v('angular.view.list.type', null);

		$dataUrl = zbase_url_from_route($widget->_v('angular.route.name', null));
		$serviceName = zbase_angular_module_servicename($module, $widget);
		$serviceScopeVariable = zbase_angular_module_scopename($module, $widget);
		$ret['scope'] = "\$scope.{$serviceScopeVariable} = {$serviceName};"
				. "\$scope.{$serviceScopeVariable}Item = \$routeParams.itemId ? {$serviceName}.getSelectedItem() : {};";
		$apiFactory = "
		app.factory('{$serviceName}', {$serviceName});
		{$serviceName}.\$inject = ['\$rootScope', '\$http','\$location'];
		function {$serviceName}(\$rootScope, \$http, \$location)
		{
			var service = {};
			service.items = [];
			service.busy = false;
			service.page = 0;
			service.maxPage = 1;
			service.selectedItem = null;
			service.nextPage = nextPage;
			service.getSelectedItem = getSelectedItem;
			service.setSelectedItem = setSelectedItem;
			service.updateSelectedItem = updateSelectedItem;
			service.deleteSelectedItem = deleteSelectedItem;
			return service;
			function nextPage(){
				if(service.page == service.maxPage)
				{
					return;
				}
				if (service.busy)
				{
					return;
				}
				if(service.page == 0)
				{
					\$rootScope.loading = true;
				}
				service.busy = true;
				\$http.jsonp('{$dataUrl}?page=' + (service.page + 1) + '&jsonp=JSON_CALLBACK&angular=1').success(function (data) {
					if(data.{$serviceName} !== undefined)
					{
						if(data.{$serviceName}.rows !== undefined)
						{
							var items = data.{$serviceName}.rows;
							for (var i = 0; i < items.length; i++)
							{
								service.items.push(items[i]);
							}
						}
						service.page = data.{$serviceName}.page;
						service.maxPage = data.{$serviceName}.maxPage;
					}
					\$rootScope.loading = false;
					service.busy = false;
				});
			}
			function getSelectedItem()
			{
				if(service.selectedItem === null){
					\$location.path('/users/');
				}
				return service.selectedItem;
			}
			function setSelectedItem(item)
			{
				service.selectedItem = item;
				\$location.path('{$selectedItemUrl}' + item.id);
				if(item.viewTitle !== undefined)
				{
					\$rootScope.viewTitle = item.viewTitle;
				}
			}
			function updateSelectedItem()
			{
				console.log('updateSelectedItem');
			}
			function deleteSelectedItem()
			{
				console.log('deleteSelectedItem');
			}
		}";

		if(!empty($controllerName))
		{
			if(!empty($templateFile))
			{
				$templateString = zbase_view_render($templateFile, ['index' => $serviceName . 'Item']);
			}
			if(!empty($templateFormat))
			{
				$templateString = str_replace('APINAME', 'item', $templateFormat);
			}

			$templateListingLink = str_replace('APINAME', 'item', $widget->_v('angular.view.list.link', '#'));
			$template = '<div ui-content-for="title">
								<span>' . $pageTitle . '</span>
							</div>
							<div class="scrollable" ng-controller="' . $controllerName . '">
								<div class="scrollable-content" ui-scroll-bottom="' . $serviceScopeVariable . '.nextPage()" infinite-scroll="' . $serviceScopeVariable . '.nextPage()" infinite-scroll-disabled="' . $serviceScopeVariable . '.busy" infinite-scroll-distance="1">

									<div class="list-group">
										<div ng-repeat="item in ' . $serviceScopeVariable . '.items">
											<a href="" class="list-group-item ng-binding ng-scope" ng-click="' . $serviceScopeVariable . '.setSelectedItem(item)">
												' . $templateString . '
												<i class="fa fa-chevron-right pull-right"></i>
											</a>
										</div>
									</div>
									<div ng-show="' . $serviceScopeVariable . '.busy" class="zbase-datatable-loader">Loading data...</div>
								</div>
							</div>';
			$ret['template'] = $template;
		}
		$ret['factory'] = $apiFactory;
		$ret['serviceName'] = $serviceName;
		$ret['serviceScopeVariable'] = $serviceScopeVariable;
		$ret['serviceGetSelectedItem'] = $serviceScopeVariable . '.getSelectedItem()';
		return $ret;
	}
	return false;
}
