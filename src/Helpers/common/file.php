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
 * Check if file exists
 * @param string $filename Path to file
 * @return boolean
 */
function zbase_file_exists($filename)
{
	return file_exists(zbase_directory_separator_fix($filename));
}

/**
 * Fix double forward slash to single forward slash
 * @param string $path
 * @return string
 */
function zbase_directory_separator_fix($path)
{
	return str_replace(array('//'), array('/'), $path);
}

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

/**
 * Copy a folder
 * @param string $src Source folder
 * @param string $dest Destination folder
 */
function zbase_file_copy_folder($src, $dest)
{
	\File::copyDirectory($src, $dest);
}

/**
 * Copy Folder Recursively
 * @param string $source
 * @param string $dest
 */
function zbase_copy_recursively($source, $dest)
{
	if(is_dir($source))
	{
		$dir_handle = opendir($source);
		while ($file = readdir($dir_handle))
		{
			if($file != "." && $file != "..")
			{
				if(is_dir($source . "/" . $file))
				{
					if(!is_dir($dest . "/" . $file))
					{
						mkdir($dest . "/" . $file);
					}
					zbase_copy_recursively($source . "/" . $file, $dest . "/" . $file);
				}
				else
				{
					copy($source . "/" . $file, $dest . "/" . $file);
				}
			}
		}
		closedir($dir_handle);
	}
	else
	{
		copy($source, $dest);
	}
}

/**
 * Download and save file from a URL
 * @param string $url The URL
 * @param string $savePath The Path to save the resource
 * @return boolean|string False if not save, else the path
 */
function zbase_file_download_from_url($url, $savePath)
{
	\Image::make($url)->save($savePath);
	if(zbase_file_exists($savePath))
	{
		return $savePath;
	}
	return null;
}
