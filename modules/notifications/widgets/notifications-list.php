<?php

/**
 * Dx
 *
 * @link http://dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2015 ClaremontDesign/MadLabs-Dx
 * @version 0.0.0.1
 * @since Mar 8, 2016 10:37:59 AM
 * @file widget.php
 * @project Expression project.name is undefined on line 13, column 15 in Templates/Scripting/EmptyPHP.php.
 * @package Expression package is undefined on line 14, column 15 in Templates/Scripting/EmptyPHP.php.
 *
 * Will display items by category
 */
return [
	'type' => 'datatable',
	'enable' => true,
	'config' => [
		'entity' => [
			'node' => [
				'enable' => true,
			],
			'name' => 'user_notifications',
			'filter' => [
				'currentUser' => true
			],
		],
		'actions' => [],
		'columns' => [
			'created_at' => [
				'label' => 'Created',
				'enable' => true,
				'data' => [
					'type' => 'timestamp',
					'index' => 'created_at'
				],
			],
			'remarks' => [
				'label' => 'Message',
				'enable' => true,
				'data' => [
					'type' => 'string',
					'index' => 'remarks'
				],
			],
		],
	],
];
