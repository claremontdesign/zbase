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
class Date extends \Zbase\Ui\Form\Element
{

	/**
	 * Widget Type
	 * @var string
	 */
	protected $_type = 'date';
	protected $_dateFormat = 'Y-m-d';

	public function getValue()
	{
		$value = parent::getValue();
		if($value instanceof \DateTime)
		{
			return $value->format($this->getDateFormat());
		}
	}

	/**
	 * The Date Format
	 * @return string
	 */
	public function getDateFormat()
	{
		return $this->_dateFormat;
	}

	/**
	 * Set the Date Format
	 * @param string $dateFormat
	 * @return $this;
	 */
	public function setDateFormat($dateFormat)
	{
		$this->_dateFormat = $dateFormat;
	}

}
