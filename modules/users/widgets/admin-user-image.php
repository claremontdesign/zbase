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
	'enable' => function(){
		return zbase_config_get('modules.account.widgets.image.enable', true);
	},
	'config' => [
		'entity' => [
			'name' => 'user',
			'node' => [
				'enable' => true,
			],
			'repo' => [
				'byId' => [
					'route' => 'id'
				]
			],
		],
		'event' => [
			'back' => [
				'image' => [
					'post' => [
						'post' => [
							'url' => function(){
								return zbase_url_previous();
							},
							'redirect' => [
								'enable' => true
							]
						]
					]
				],
			]
		],
		'form' => [
			'startTag' => [
				'action' => function(){
					return zbase_url_from_route('admin.users', ['action' => 'image', 'id' => zbase_route_input('id')]);
				},
			]
		],
		'elements' => [
			'file' => [
				'type' => 'file',
				'id' => 'file',
				'label' => 'Update Image',
				'html' => [
					'content' => [
						'pre' => [
							'enable' => true,
							'view' => zbase_view_file_contents('node.files.files')
						]
					],
				],
			],
		]
	],
];
