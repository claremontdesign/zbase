<?php

namespace Zbase\Models\Data;

/**
 * Datatable Column
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Column.php
 * @project Zbase
 * @package Zbase\Models\View
 */
use Zbase\Interfaces;
use Zbase\Traits;

class Column extends Data implements Interfaces\IdInterface
{

	use Traits\Id;

	/**
	 * Constructor
	 * @param string $name ID/Name
	 * @param array $attributes array of attributes/configuration
	 */
	public function __construct(array $attributes = null)
	{
		parent::__construct($attributes);
	}

}
