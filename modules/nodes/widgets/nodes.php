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
	'type' => 'datatable',
	'enable' => true,
	'config' => [
		'entity' => [
			'name' => 'node'
		],
		'actions' => [
			'create' => [
				'enable' => true,
				'label' => 'Create new node',
				'route' => [
					'name' => 'admin.nodes',
					'params' => ['action' => 'create']
				],
			],
			'update' => [
				'enable' => true,
				'route' => [
					'name' => 'admin.nodes',
					'params' => ['action' => 'update', 'id' => 'row::node_id']
				],
			],
			'delete' => [
				'enable' => true,
				'route' => [
					'name' => 'admin.nodes',
					'params' => ['action' => 'delete', 'id' => 'row::node_id']
				],
			],
			'restore' => [
				'enable' => true,
				'route' => [
					'name' => 'admin.nodes',
					'params' => ['action' => 'restore', 'id' => 'row::node_id']
				],
			],
			'ddelete' => [
				'enable' => true,
				'route' => [
					'name' => 'admin.nodes',
					'params' => ['action' => 'ddelete', 'id' => 'row::node_id']
				],
			],
		],
		'columns' => [
			'id' => [
				'label' => 'ID',
				'enable' => true,
				'data' => [
					'type' => 'integer',
					'index' => 'node_id'
				],
			],
			'title' => [
				'label' => 'Title',
				'enable' => true,
				'data' => [
					'type' => 'string',
					'index' => 'title'
				],
			],
			'status' => [
				'label' => 'Status',
				'enable' => true,
				'data' => [
					'type' => 'displayStatus',
					'index' => 'status'
				],
			],
		],
	],
];
