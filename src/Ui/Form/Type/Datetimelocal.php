<?php

namespace Zbase\Ui\Form\Type;

/**
 * Zbase-Form Element-Date
 *
 * Element-Date
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Date.php
 * @project Zbase
 * @package Zbase/Ui/Form/Elements
 */
class Datetimelocal extends \Zbase\Ui\Form\Type\Date
{

	/**
	 * Widget Type
	 * @var string
	 */
	protected $_type = 'datetime-local';
	protected $_dateFormat = 'Y-m-d\TH:i:s';
}
