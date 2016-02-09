<?php

/**
 * Zbase-Laravel Helpers-File/Directories
 *
 * Functions and Helpers for File and Directories manipulation
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file file.php
 * @project Zbase
 * @package Zbase/Laravel/Helpers
 */
/**
 * Return the Asset base path
 * @return string
 */
function zbase_path_asset($path = null)
{
	return '/' . zbase_tag() . '/assets/' . $path;
}

/**
 * Return the path to theme.
 *
 * @param string $theme
 * @param string $section
 * @return string
 */
function zbase_path_asset_theme($theme, $section)
{
	return 'templates/' . $section . '/' . $theme . '/';
}

/**
 * Return the package-based asset path
 *
 * @param string $package
 * @return string
 */
function zbase_path_asset_package($package)
{
	return 'packages/' . $package . '/';
}

/**
 * Return the package-theme-based asset path
 *
 * @param string $package
 * @param string $theme
 * @param string $section
 * @return type
 */
function zbase_path_asset_package_theme($package, $theme, $section)
{
	return 'packages/' . $package . '/templates/' . $section . '/' . $theme . '/';
}

/**
 * Application path
 * return laravel\app
 *
 * @return string
 */
function zbase_app_path($path = null)
{
	return app_path($path);
}

/**
 * Application base Path
 * return laravel\
 *
 * @return string
 */
function zbase_base_path($path = null)
{
	return base_path($path);
}

/**
 * Return the Public Path
 * return laravel\public
 *
 * @return string
 */
function zbase_public_path($path = null)
{
	return env('PUBLIC_PATH' . $path,public_path($path));
}

/**
 * Return the Storage Path
 * return laravel\storage
 *
 * @return string
 */
function zbase_storage_path($path = null)
{
	return storage_path($path);
}