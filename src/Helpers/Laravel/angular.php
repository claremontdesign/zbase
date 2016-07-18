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
		$apiName = ucfirst(zbase_string_camel_case($module->id() . '_datatable_' . $widget->getHtmlId())) . 'Service';
		$apiNameVariable = strtolower($apiName);
		$ret['scope'] = "\$scope.{$apiNameVariable} = new {$apiName}();";
		$apiFactory = "app.factory('{$apiName}', function (\$rootScope, \$http) {
			var {$apiName} = function () {
				this.items = [];
				this.busy = false;
				this.page = 1;
				this.maxPage = 1;
			};
			{$apiName}.prototype.nextPage = function () {
				if(this.page > this.maxPage)
				{
					return;
				}
				if (this.busy)
				{
					return;
				}
				\$rootScope.loading = true;
				this.busy = true;
				\$http.jsonp('" . zbase_url_from_route($routeName) . "?page=' + (this.page + 1) + '&jsonp=JSON_CALLBACK&angular=1').success(function (data) {
					if(data.{$apiName} !== undefined)
					{
						if(data.{$apiName}.rows !== undefined)
						{
							var items = data.{$apiName}.rows;
							for (var i = 0; i < items.length; i++) {
								this.items.push(items[i]);
							}
						}
						this.page = data.{$apiName}.page;
						this.maxPage = data.{$apiName}.maxPage;
					}
					this.busy = false;
					\$rootScope.loading = false;
				}.bind(this));
			};
			return {$apiName};
		});";

		if(!empty($controllerName))
		{
			if(!empty($templateFile))
			{
				$templateString = zbase_view_render($templateFile, ['index' => $apiName . 'Item']);
			}
			if(!empty($templateFormat))
			{
				$templateString = str_replace('APINAME', 'item', $templateFormat);
			}
			$template = '<div ui-content-for="title">
								<span>'.$module->_v('controller.back.action.index.page.title', $module->_v('controller.action.index.page.title', null)).'</span>
							</div>
							<div class="scrollable" ng-controller="'.$controllerName.'">
								<div class="scrollable-content" ui-scroll-bottom="' . $apiNameVariable . '.nextPage()" infinite-scroll="' . $apiNameVariable . '.nextPage()" infinite-scroll-disabled="' . $apiNameVariable . '.busy" infinite-scroll-distance="1">

									<div class="list-group">
										<div ng-repeat="item in ' . $apiNameVariable . '.items">
											<a href="#" class="list-group-item ng-binding ng-scope">
												' . $templateString . '
												<i class="fa fa-chevron-right pull-right"></i>
											</a>
										</div>
									</div>
									<div ng-show="' . $apiNameVariable . '.busy" class="zbase-datatable-loader">Loading data...</div>

								</div>
							</div>';
			$ret['template'] = $template;
		}
		$ret['factory'] = $apiFactory;
		$ret['name'] = $apiName;
		return $ret;
	}
	return false;
}