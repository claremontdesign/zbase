<?php

/**
 * Zbase widgets implementation
 *
 * @link http://dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2015 ClaremontDesign/MadLabs-Dx
 * @version 0.0.0.1
 * @since Feb 17, 2016 3:37:15 PM
 * @file widgets.php
 */
return [
	'widgets' => [
		'widgetName' => [
			'type' => 'form',
			'enable' => true,
			/**
			 * string|array
			 * 	string: minimum|admin
			 *  array: [admin, user]
			 * Who has access.
			 * minimum|role name
			 * minimum is the minimum role for the current section, else a role name or array of role names
			 */
			// 'access' => 'minimum',
			/**
			 * Widget configuration
			 */
			'config' => [],
		],
	],
];
