<?php

/**
 * Zbase-Laravel Helpers-Views
 *
 * Functions and Helpers for View, themes and templates
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file view.php
 * @project Zbase
 * @package Zbase/Laravel/Helpers
 */
// <editor-fold defaultstate="collapsed" desc="HTML Renders">
/**
 * Return asset absolute URL
 * 	http://domain.com/zbase/asset/$file.ext
 *
 * @param string $file
 * @return string
 */
function zbase_view_asset($file = null)
{
	$path = zbase_path_asset() . $file;
	return asset($path);
}

/**
 * Return a theme-based asset
 * 	http://domain.com/zbase/assets/themes/$section/$theme/$file.ext
 *
 * @param string $file
 * @param string $theme [optional] default to current theme
 */
function zbase_view_asset_theme($file, $theme = null, $section = 'front')
{
	$path = zbase_path_asset_theme((empty($theme) ? zbase_view_template_theme() : $theme), $section) . $file;
	return zbase_view_asset($path);
}

/**
 * Return a package-based asset
 *
 * @param string $file asset file relative to your resources folder
 * @param string $package package name
 * @return string
 */
function zbase_view_asset_package($file, $package)
{
	$path = zbase_path_asset_package($package) . $file;
	return zbase_view_asset($path);
}

/**
 * Return a package-theme-based asset
 *
 * @param string $file asset file relative to your resources folder
 * @param string $package package name
 * @param string $theme theme name
 * @return string
 */
function zbase_view_asset_package_theme($file, $package, $theme)
{
	$path = zbase_path_asset_package_theme($package, (empty($theme) ? zbase_view_template_theme() : $theme), zbase_section()) . $file;
	return zbase_view_asset($path);
}

/**
 * Render a view file
 *
 * 	zbase_view_render('index', []);
 *
 * @param string $file
 * @param array $params
 * @return \View
 */
function zbase_view_render($file, $params = [])
{
	if(!empty($file))
	{
		return view(zbase_view_file($file), $params);
	}
}

// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="Template">
/**
 * Return the systems template package to use
 * This is the main package to load
 * 	default: zbase
 *
 * @param string $tag [optional]
 * @return string
 */
function zbase_view_template_package($tag = null)
{
	$tag = !empty($tag) ? $tag . '.' : null;
	$section = zbase_section();
	return zbase_config_get('view.templates.' . $tag . $section . '.package', zbase_tag());
}

/**
 * REturn the system's theme to use
 *
 * @param string $tag [optional]
 * @return string
 */
function zbase_view_template_theme($tag = null)
{
	$tag = !empty($tag) ? $tag . '.' : null;
	$section = zbase_section();
	return zbase_config_get('view.templates.' . $tag . $section . '.theme', 'default');
}

/**
 * Return the view file
 * 	It search for the view file in the order of:
 * 		- $package.templates.$section.theme.$name (resource.views.templates)
 * 		- $package.contents.$name (resource.views.contents)
 * 		- zbase.templates.$section.theme.$name
 * 		- zbase.contents.$name
 *
 * @param string $name
 * @return string
 */
function zbase_view_file($name, $section = 'front')
{
	if(preg_match('/\:\:/', $name))
	{
		return $name;
	}


	$package = zbase_view_template_package();
	$theme = zbase_view_template_theme();
	// - check $package.templates.$section.theme.$name
	$viewFile = $package . '::templates.' . $section . '.' . $theme . '.' . $name;
	if(\View::exists($viewFile))
	{
		return $viewFile;
	}

	// - check contents.$name
	$viewFile = $package . '::contents.' . $section . '.' . $name;
	if(\View::exists($viewFile))
	{
		return $viewFile;
	}

	// - check contents.$name
	$viewFile = $package . '::contents.' . $name;
	if(\View::exists($viewFile))
	{
		return $viewFile;
	}

	// check default from templates
	$viewFile = zbase_tag() . '::templates.' . $section . '.default.' . $name;
	if(\View::exists($viewFile))
	{
		return $viewFile;
	}

	// check default from contents
	$viewFile = zbase_tag() . '::contents.' . $name;
	if(\View::exists($viewFile))
	{
		return $viewFile;
	}
	return $name;
}

/**
 * Return the Main Template Layout
 *
 * 	The main template configuration:
 * 		view.templates.front.package = The package to use
 * 		view.templates.front.theme = The theme to use
 * 		view.templates.$tag.front.package = Tag a package to use
 * 		view.templates.$tag.front.theme = Tag a package to use
 *
 * @param string $tag
 * @return string
 */
function zbase_view_template_layout($tag = null)
{
	$section = zbase_section();
	$package = zbase_view_template_package($tag);
	$theme = zbase_view_template_theme($tag);
	$viewFile = $package . '::templates.' . $section . '.' . $theme . '.layout';
	if(\View::exists($viewFile))
	{
		return $viewFile;
	}
	return zbase_tag() . '::templates.' . $section . '.default.layout';
}

// </editor-fold>