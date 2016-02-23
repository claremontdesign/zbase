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
function zbase_widget($widgetName)
{
	return zbase()->widget($widgetName);
}

/**
 * Create an Element
 * @param string $name
 * @param array $element
 * @return \Zbase\Ui\Form\ElementInterface
 */
function zbase_ui_form_element($name, $element)
{
	return \Zbase\Ui\Form\Element::factory($name, $element);
}
