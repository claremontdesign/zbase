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
 *
 * nav.front.main.indexName
 * nav.front.main.indexName.breadcrumb if to show in a breadcrumb
 * nav.front.main.indexName.inMenu if to show in Main Menu
 * nav.front.main.indexName.title HTML title-attribute
 * nav.front.main.indexName.label The label to use. default to title
 * nav.front.main.indexName.icon
 * nav.front.main.indexName.enable enable/disable
 * nav.front.main.indexName.url
 * nav.front.main.indexName.url.route
 * nav.front.main.indexName.url.route.name the routeName
 * nav.front.main.indexName.children
 * nav.front.main.indexName.meta
 * nav.front.main.indexName.page.pageTitle
 * nav.front.main.indexName.page.meta.keywords
 * nav.front.main.indexName.page.meta.description
 */
return [
	'nav' => [
		'front' => [
			'main' => [
				'contacts' => [
					'breadcrumb' => false,
					'inMenu' => false,
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
