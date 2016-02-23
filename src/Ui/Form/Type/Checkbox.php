<?php

namespace Zbase\Ui\Form\Type;

/**
 * Zbase-Form Element-Checkbox
 *
 * Element-Checkbox
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Checkbox.php
 * @project Zbase
 * @package Zbase/Ui/Form/Elements
 */
class Checkbox extends \Zbase\Ui\Form\Type\Radio
{

	/**
	 * Widget Type
	 * @var string
	 */
	protected $_type = 'checkbox';

	/**
	 * The view File to use
	 * @var string
	 */
	protected $_viewFile = 'ui.form.type.checkbox';

	/**
	 * If only to render an option
	 * @var boolean
	 */
	protected $_renderOption = false;
}
