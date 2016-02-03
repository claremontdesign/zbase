<?php

namespace Zbase\Interfaces;

/**
 * Zbase-EntityRepositoryInterface
 *
 * EntityRepositoryInterface
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file EntityRepositoryInterface.php
 * @project Zbase
 * @package Zbase/Interfaces
 */
interface EntityRepositoryInterface
{

	public function all($columns = ['*'], $filters = null, $sorting = null, $joins = null, $paginate = null, $unions = null, $group = null, $options = null);

	public function byId($id, $columns = ['*']);

	public function create(array $data = array());

	public function update(array $data = null, array $filters = null);

	public function delete($filters = []);

	public function restore($filters = []);

	public function getModel();

	public function setDebug($debug);
}
