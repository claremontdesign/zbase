<?php

/**
 * Email configuration
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file email.php
 * @project Zbase
 * @package config
 *
 * emai.no-reply
 * emai.no-reply.email = The Email address of the no-reply sender
 * emai.no-reply.name = Name of the no-reply sender
 */
return [
	'email' => [
		'noreply' => [
			'email' => 'no-reply@zbase.com',
			'name' => 'No-Reply at Zbase.com',
		],
		'account-noreply' => [
			'email' => 'no-reply@zbase.com',
			'name' => 'No-Reply at Zbase.com',
		],
		'contactus' => [
			'email' => 'info@zbase.com',
			'Name' => 'Contact Us',
		],
	],
];