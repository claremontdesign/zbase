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
		'frontend' => 'account/{action?}/{task?}',
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
