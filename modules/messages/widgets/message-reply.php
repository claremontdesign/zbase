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
		'view' => [
			'placeholder' => 'message-reply'
		],
		'access' => [
			'enable' => true,
			'role' => 'user',
			'noauth' => [
				'route' => 'login',
			]
		],
		'entity' => [
			'node' => [
				'enable' => true
			],
			'repo' => [
				'byAlphaId' => [
					'route' => 'id'
				],
			],
			'name' => 'messages',
			'filter' => ['public' => true, 'currentUser' => true]
		],
		'submit' => [
			'button' => [
				'label' => 'Send',
				'html' => [
					'attributes' => [
						'class' => [
							'btn-success'
						],
					],
				],
			],
		],
		'elements' => [
			'headerCategory' => [
				'ui' => [
					'type' => 'component.pageHeader',
					'id' => 'header',
					'text' => 'Reply',
					'tag' => 'h4'
				],
			],
			'content' => [
				'type' => 'textarea',
				'id' => 'content',
				'label' => null,
				'html' => [
					'attributes' => [
						'input' => [
							'onfocus' => 'jQuery(this).attr(\'rows\',10);',
							'rows' => '2',
							'style' => [
								'max-width:100%'
							],
						],
					],
				],
				'validations' => [
					'required' => [
						'enable' => true,
						'message' => 'Message is empty.'
					],
				],
			],
			'msg' => [
				'type' => 'hidden',
				'id' => 'msg',
				'label' => null,
				'entity' => [
					'property' => 'alpha_id'
				],
				'validations' => [
					'required' => [
						'enable' => true,
						'message' => 'Message is empty.'
					],
				],
			],
		],
	],
];
