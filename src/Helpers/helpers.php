<?php

/**
 * Zbase Helpers
 *
 * Functions and Helpers
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file helpers.php
 * @project Zbase
 * @package Zbase
 */
require_once __DIR__ . '/common/common.php';
$folders = [__DIR__ . '/common/', __DIR__ . '/' . zbase_framework() . '/'];
foreach ($folders as $folder)
{
	$handle = opendir($folder);
	while (false !== ($filename = readdir($handle)))
	{
		if($filename !== '.' && $filename !== '..')
		{
			require_once $folder . $filename;
		}
	}
}
