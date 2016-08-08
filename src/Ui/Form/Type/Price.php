<?php

namespace Zbase\Ui\Form\Type;

/**
 * Zbase-Form Element-Price
 *
 * Element-Price
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Price.php
 * @project Zbase
 * @package Zbase/Ui/Form/Elements
 */
class Price extends \Zbase\Ui\Form\Element
{

	/**
	 * Widget Type
	 * @var string
	 */
	protected $_type = 'number';
	//min="0.01" step="0.01"


	/**
	 * Return the Input Attributes
	 * @return array
	 */
	public function inputAttributes()
	{
		$attr = parent::inputAttributes();
		$attr['min'] = '0.01';
		$attr['step'] = '0.01';
		$attr['max'] = '999999999';
		return $attr;
	}
}
