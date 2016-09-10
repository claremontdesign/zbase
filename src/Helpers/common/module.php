<?php

/**
 * Dx
 *
 * @link http://dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2015 ClaremontDesign/MadLabs-Dx
 * @version 0.0.0.1
 * @since Sep 10, 2016 11:36:34 AM
 * @file module.php
 * @project Zbase
 * @package Expression package is undefined on line 14, column 15 in Templates/Scripting/EmptyPHP.php.
 */

/**
 * Return contents for a certain module and widget
 *
 * @param string $module The module that is asking for content
 * @param string $widget The widget that is asking for content
 * @param string $section The current section [default: front] front|back
 * @param boolean $adminView IF for admin [default: false]
 * @param string $key key to search
 * @param array $contents Some contents
 * @return array
 */
function zbase_module_widget_contents($module, $widget, $section = 'front', $adminView = false, $key = null, $contents = [])
{
	$modules = zbase()->modules();
	foreach ($modules as $mod)
	{
		$widgetContent = $mod->widgetContents($widget, $module, $section, $adminView, $key);
		if(!empty($widgetContent))
		{
			foreach ($widgetContent as $index => $content)
			{
				$contents[] = $content;
			}
		}
	}
	if(!empty($contents))
	{
		return zbase_collection($contents)->sortByDesc(function ($itm) {
				return !empty($itm['position']) ? $itm['position'] : 0;
		})->toArray();
	}
}

/**
 * Render The Module Contents
 * @param array $contents
 * @return html
 */
function zbase_module_widget_render_contents($contents, $groupId = null)
{
	$str = [];
	if(!empty($contents))
	{
		foreach ($contents as $content)
		{
			$gId = !empty($content['groupId']) ? $content['groupId'] : null;
			if(!empty($groupId))
			{
				if($gId == $groupId)
				{
					$str[] = zbase_data_get($content, 'content', null);
				}
				continue;
			}
			$str[] = zbase_data_get($content, 'content', null);
		}
	}
	return implode(PHP_EOL, $str);
}
