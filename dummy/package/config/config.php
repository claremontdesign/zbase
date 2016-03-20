<?php

/**
 * Zbase
 *
 * @link http://dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2015 ClaremontDesign/MadLabs-Dx
 * @version 0.0.0.1
 * @since Mar 19, 2016 2:14:40 PM
 * @file config.php
 */
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
