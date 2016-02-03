<?php

/**
 * Zbase Helpers - File
 *
 * Functions and Helpers File Manipulation
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file file.php
 * @project Zbase
 * @package Zbase\Helpers
 */

/**
 * Copy source to destination
 *
 * @param string $src
 * @param string $dst
 * @param boolean $overwrite
 */
function zbase_file_copy($src, $dst, $overwrite = false)
{
	if(is_file($src))
	{
		if(!is_dir(dirname($dst)))
		{
			mkdir(dirname($dst), 0777, true);
		}
		copy($src, $dst);
	}
}
