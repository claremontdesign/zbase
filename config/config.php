<?php

/**
 * Main configuration
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file config.php
 * @project Zbase
 * @package config
 */
//$config = [];
//return array_merge($config, require __DIR__ . '/db.php',require __DIR__ . '/page.php', require __DIR__ . '/widgets.php', require __DIR__ . '/email.php', require __DIR__ . '/entity.php', require __DIR__ . '/auth.php', require __DIR__ . '/view.php', require __DIR__ . '/controller.php', require __DIR__ . '/routes.php', require __DIR__ . '/ui.php', require __DIR__ . '/nav.php');

$config = [];
$folders = [ __DIR__ . '/'];
foreach ($folders as $folder)
{
	$handle = opendir($folder);
	while (false !== ($filename = readdir($handle)))
	{
		if($filename !== '.' && $filename !== '..' && is_file($folder . $filename))
		{
			$c = require_once $folder . $filename;
			if(is_array($c))
			{
				$config = array_merge($config, $c);
			}
		}
	}
}
return $config;
