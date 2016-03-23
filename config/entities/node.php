<?php

/**
 * Entities configuration
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file entity.php
 * @project Zbase
 * @package config
 *
 */
return [
	'entity' => [
		'node' => [
			'enable' => true,
			'model' => Zbase\Entity\__FRAMEWORK__\Node\Node::class,
		],
		'node_category' => [
			'enable' => true,
			'model' => Zbase\Entity\__FRAMEWORK__\Node\Category::class,
		],
		'node_category_pivot' => [
			'enable' => true,
			'model' => Zbase\Entity\__FRAMEWORK__\Node\Category::class,
			'pivot' => true
		],
		'node_files' => [
			'enable' => true,
			'model' => Zbase\Entity\__FRAMEWORK__\Node\File::class
		],
		'node_messages' => [
			'enable' => true,
			'model' => Zbase\Entity\__FRAMEWORK__\Node\Message::class
		],
	],
];
