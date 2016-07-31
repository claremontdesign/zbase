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
 */
return [
	'type' => 'form',
	'enable' => true,
	'config' => [
		'form_tab' => false,
		'form' => [
			'startTag' => [
				'html' => [
					'attributes' => [
						'ng-controller' => 'adminUsersController',
					],
				],
			],
		],
		'submit' => [
			'button' => [
				'label' => 'Update',
				'html' => [
					'attributes' => [
						'ng-click' => 'ANGULAR_WIDGET_MODULE_SCOPENAME.updateSelectedItem()'
					]
				],
			],
		],
		'entity' => [
			'name' => 'user',
			'repo' => [
				'byAlphaId' => [
					'route' => 'id'
				],
			],
		],
		'elements' => [
			'email' => [
				'type' => 'email',
				'id' => 'email',
				'enable' => true,
				'label' => 'Email Address',
				'angular' => [
					'ngModel' => 'ANGULAR_WIDGET_MODULE_SCOPENAMEItem.email'
				],
				'entity' => [
					'property' => 'email'
				]
			],
			'first_name' => [
				'type' => 'text',
				'id' => 'first_name',
				'enable' => true,
				'label' => 'First Name',
				'angular' => [
					'ngModel' => 'ANGULAR_WIDGET_MODULE_SCOPENAMEItem.first_name'
				],
				'entity' => [
					'property' => 'first_name'
				]
			],
			'last_name' => [
				'type' => 'text',
				'id' => 'last_name',
				'enable' => false,
				'label' => 'Last Name',
				'angular' => [
					'ngModel' => 'ANGULAR_WIDGET_MODULE_SCOPENAMEItem.last_name'
				],
				'entity' => [
					'property' => 'last_name'
				]
			],
		],
	],
];
