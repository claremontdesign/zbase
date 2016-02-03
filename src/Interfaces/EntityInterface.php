<?php

namespace Zbase\Interfaces;

/**
 * Zbase-Interface-Entity Interface
 *
 * EntityInterface
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file EntityInterface.php
 * @project Zbase
 * @package Zbase/Interfaces
 */
interface EntityInterface
{

	/**
	 * FixData
	 * @param array $data
	 * @param type $mode
	 */
	public function fixDataArray(array $data, $mode = null);

	/**
	 * REturn the tableName
	 */
	public function getTable();

	/**
	 * Create or return repository
	 */
	public function repository();
}
