<?php

namespace Zbase\Widgets;

/**
 * Zbase-Interface Widget Entity Interface
 *
 * WidgetEntityInterface
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file AttributeInterface.php
 * @project Zbase
 * @package Zbase/Widgets
 */
interface EntityInterface
{
	public function widgetController($method, $action, $data, \Zbase\Widgets\Widget $widget);
}
