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
 * auth.password.loginAfterReset = boolean, if to login after reset
 * auth.emailVerify
 * auth.emailVerify.enable = if to enable email verification
 * auth.role
 * auth.role.default = the default role to a new registered user
 * auth.register
 * auth.register.enable
 * auth.register.defaultStatus = ok
 */
return [
	'auth' => [
		'enable' => true
	],
];
