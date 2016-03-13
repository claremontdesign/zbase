<?php

namespace Zbase\Module;

/**
 * Zbase-Interface Module Interface
 *
 * ModuleInterface
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file AttributeInterface.php
 * @project Zbase
 * @package Zbase/Widgets
 */
interface ModuleInterface
{

	/**
	 * This module has a backend interface
	 * Route will be created dynamically
	 * This module can be accessed: /admin/$moduleId()
	 */
	public function hasBackend();

	/**
	 * Module can be accessed via frontend
	 * Route will be created dynamically
	 * Url: domain.com/$moduleId()
	 */
	public function hasFrontend();

	/**
	 * The URL Key per section
	 * default: /$moduleId()/$action/$record/$task
	 * @param string $section
	 * @param array $params
	 * @return string
	 */
	public function url($section, $params);

	/**
	 * If backend is enabled
	 */
	public function isEnable();

	/**
	 * If current user has access
	 */
	public function hasAccess();

	/**
	 * Check if module is enabled and has access
	 */
	public function isEnableAndAccessible();

	/**
	 * Module unique ID/name
	 * @return string
	 */
	public function id();

	/**
	 * Title
	 * @return string
	 */
	public function title();

	/**
	 * Description
	 * @return string
	 */
	public function description();
}
