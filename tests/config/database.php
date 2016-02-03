<?php

/**
 * database testing configuration
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file database.php
 * @project Zbase
 * @package config
 */
return array(
	'default' => 'sqlite',
	'connections' => array(
		'sqlite' => array(
			'driver' => 'sqlite',
			'database' => ':memory:',
			'prefix' => ''
		),
	)
);
