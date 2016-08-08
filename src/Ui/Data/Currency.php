<?php

namespace Zbase\Ui\Data;

/**
 * Zbase-Ui-Data-DisplayStatus
 *
 * PageHeader
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file DisplayStatus.php
 * @project Zbase
 * @package Zbase/Ui/Data
 */
class Currency extends Data
{

	/**
	 * Widget Type
	 * @var string
	 */
	protected $_type = 'currency';

	/**
	 * HTML the ui
	 * @return string
	 */
	public function __toString()
	{
		$this->prepare();
		return zbase_string_format_currency($this->getValue());
	}

}
