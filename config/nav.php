<?php

/**
 * Navigations configuration
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file nav.php
 * @project Zbase
 * @package config
 */
return [
	'nav' => [
		'front' => [
			'main' => [
				'repositories' => [
					'breadcrumb' => false,
					'title' => 'Repositories',
					'label' => 'Repositories',
					'icon' => 'fa fa-home',
					'enable' => true,
					'url' => [
						'route' => [
							'name' => 'home'
						],
					],
					'children' => [
						'repositories' => [
							'breadcrumb' => false,
							'title' => 'Repositories',
							'label' => 'Repositories',
							'icon' => 'fa fa-home',
							'enable' => true,
							'url' => [
								'route' => [
									'name' => 'home'
								],
							],
							'children' => []
						],
						'contacts' => [
							'breadcrumb' => false,
							'title' => 'Contacts',
							'label' => 'Contact',
							'icon' => 'fa fa-home',
							'enable' => true,
							'url' => [
								'route' => [
									'name' => 'home'
								],
							],
							'children' => [
								'repositories' => [
									'breadcrumb' => false,
									'title' => 'Repositories',
									'label' => 'Repositories',
									'icon' => 'fa fa-home',
									'enable' => true,
									'url' => [
										'route' => [
											'name' => 'home'
										],
									],
									'children' => []
								],
								'contacts' => [
									'breadcrumb' => false,
									'title' => 'Contacts',
									'label' => 'Contact',
									'icon' => 'fa fa-home',
									'enable' => true,
									'url' => [
										'route' => [
											'name' => 'home'
										],
									],
									'children' => []
								],
							]
						],
					]
				],
				'contacts' => [
					'breadcrumb' => false,
					'title' => 'Contacts',
					'label' => 'Contact',
					'icon' => 'fa fa-home',
					'enable' => true,
					'url' => [
						'route' => [
							'name' => 'home'
						],
					],
					'children' => []
				],
			]
		]
	],
];
