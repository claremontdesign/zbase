<?php

/**
 * Zbase Widgets
 *
 * Functions and Helpers Widget Helpers
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file common.php
 * @project Zbase
 * @package Zbase\Helpers
 */

/**
 * Return a widget by name
 * @param string $widgetName The widget index name
 * @return \Zbase\Widgets\WidgetInterface
 */
function zbase_widget($widgetName, $config = [], $clone = false, $overrideConfig = [])
{
	return zbase()->widget($widgetName, $config, $clone, $overrideConfig);
}

/**
 * Create a UI Element
 * @param array $configuration
 * @return Ui\UiInterface
 */
function zbase_ui($configuration)
{
	return \Zbase\Ui\Ui::factory($configuration);
}

/**
 * Create a Tab UI
 * @param array $tabs Multiple Tab Configuration
 * @return \Zbase\Ui\Tabs
 */
function zbase_ui_tabs($tabs)
{
	if(!empty($tabs))
	{
		foreach ($tabs as $tab)
		{
			if(is_array($tab))
			{
				$tab = zbase_ui($tab);
			}
		}
		return $tab->group();
	}
}

/**
 * Create an Element
 * @param array $configuration
 * @return \Zbase\Ui\Form\ElementInterface
 */
function zbase_ui_form_element($configuration)
{
	return \Zbase\Ui\Form\Element::factory($configuration);
}
