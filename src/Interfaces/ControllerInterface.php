<?php

namespace Zbase\Interfaces;

/**
 * Zbase-Interface-Controller
 *
 * ControllerInterface
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file ControllerInterface.php
 * @project Zbase
 * @package Zbase/Interfaces
 */
interface ControllerInterface
{

	/**
	 * Set Zbase
	 * @param \Zbase\Zbase $zbase
	 */
	public function setZbase(\Zbase\Zbase $zbase);

	/**
	 * Return zbase
	 */
	public function zbase();

	/**
	 * Return controller name
	 */
	public function getName();

	/**
	 * Return Controller action name
	 */
	public function getActionName();

	/**
	 * Return a content based from a view file
	 * @param string $file
	 */
	public function view($file);

	/**
	 * Set the route parameters
	 * @param array $routeParameters
	 * return $this;
	 */
	public function setRouteParameters($routeParameters);

	/**
	 * Return the route parameters
	 * @return array
	 */
	public function getRouteParameters();

	/**
	 * Return a route parameter by $key
	 * @param string $key
	 * @param strings $default
	 * @return mixed
	 */
	public function getRouteParameter($key, $default);

	/**
	 * Check if POSTing
	 *
	 * @return boolean
	 */
	public function isPost();

	/**
	 * Check if request is from ajax
	 *
	 * @return boolean
	 */
	public function isAjax();

	/**
	 * Check if to return JSON
	 * @return boolean
	 */
	public function isJson();

	/**
	 * Validate
	 *
	 * @param array $inputs
	 * @param array $rules
	 * @param array $messages
	 * @return array
	 */
	public function validateInputs($inputs, $rules, $messages);

	/**
	 * Add Message
	 * @param string $type
	 * @param string $message
	 * @return void
	 */
	public function message($type, $message);
}
