<?php

/**
 * Dx
 *
 * @link http://dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2015 ClaremontDesign/MadLabs-Dx
 * @version 0.0.0.1
 * @since Mar 5, 2016 11:51:42 PM
 * @file profile.php
 * @project Expression project.name is undefined on line 13, column 15 in Templates/Scripting/EmptyPHP.php.
 * @package Expression package is undefined on line 14, column 15 in Templates/Scripting/EmptyPHP.php.
 *
 * zbase()->loadModuleFrom(PATH_TO_MODULES);
 *		- widgets will be added automatically if a "widget" folder is found (zbase()->loadWidgetsFrom(PATH_TO_WIDGETS))
 *
 * modules.module.id = unique id
 * modules.module.enable = enable/disable
 * modules.module.access = minimum access level
 * modules.module.class = The classname to use
 * modules.module.backend = true|false
 * modules.module.frontend = true|false
 * modules.module.url.front = the front URL key
 * modules.module.url.back = the back URL key; default to module.id
 * @TODO
 * modules.module.controller
 * modules.module.controller.class = If you want to call an action, this is required; else, will load the default module controller
 * modules.module.controller.action = will be called, do whatever you want with the action. be sure to define a controller.class value
 * modules.module.controller.action.view = Will load the value of this returning a view() response
 *
 * controller will be check first, then widgets.
 *
 * modules.module.widgets
 * modules.module.widgets.controller
 * modules.module.widgets.controller = array|string; Widget/s will be loaded when actions will is defined, unless specific action is defined.
 * modules.module.widgets.controller.actionName = array|string; widgets[]|widget, will be loaded;
 * modules.module.widgets.controller.actionName.widgetId = null (will check from added widget)
 * modules.module.widgets.controller.actionName.widgetId = PATH_TO_WIDGET (will load widget and configuration by the path provided)
 */
return [
	'id' => 'account',
	'enable' => true,
	'access' => 'user',
	'class' => null,
	'backend' => true,
	'frontend' => true,
	'url' => [
		'backend' => 'account/{action?}',
		'frontend' => 'account/{action?}',
	],
	'controller' => [
		'action' => []
	],
	'widgets' => [
		'controller' => [
			'index' => [
				'account' => null
			]
		],
	],
];
