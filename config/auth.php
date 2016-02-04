<?php

/**
 * Auth-User configuration
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file auth.php
 * @project Zbase
 * @package config
 *
 * auth.enable disable/enable user authentication; default: true
 * auth.messages
 * auth.messages.failed message to show if auth failed
 */
return [
	'auth' => [
		'enable' => true,
		'messages' => [
			'failed' => 'These credentials do not match our records'
		],
	],
];
