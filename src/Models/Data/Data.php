<?php

namespace Zbase\Models\Data;

/**
 * Datatable Data
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Data.php
 * @project Zbase
 * @package Zbase\Models\Data
 */
use Zbase\Interfaces;
use Zbase\Traits;

class Data implements Interfaces\AttributeInterface, Interfaces\FakerInterface
{

	use Traits\Attribute,
	 Traits\Faker;

	/**
	 * The Data Type
	 * @var string
	 */
	protected $type = null;

	/**
	 * Data length
	 * @var integer
	 */
	protected $length = null;

	/**
	 * Data subType
	 * @var string
	 */
	protected $subType = null;

	/**
	 * Map of Values
	 * @var array
	 */
	protected $valueMap = [];

	/**
	 * Constructor
	 * @param array $attributes array of attributes/configuration
	 */
	public function __construct(array $attributes = null)
	{
		$this->setAttributes($attributes);
	}

	/**
	 *
	 * @see $type
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 *
	 * @see $length
	 */
	public function getLength()
	{
		return $this->length;
	}

	/**
	 *
	 * @see $subType
	 */
	public function getSubType()
	{
		return $this->subType;
	}

	/**
	 *
	 * @see $type
	 */
	public function setType($type)
	{
		$this->type = $type;
	}

	/**
	 *
	 * @see $length
	 */
	public function setLength($length)
	{
		$this->length = $length;
	}

	/**
	 *
	 * @see $subType
	 */
	public function setSubType($subType)
	{
		$this->subType = $subType;
	}

	/**
	 *
	 * @see $valueMap
	 */
	public function getValueMap()
	{
		return $this->valueMap;
	}

	/**
	 *
	 * @see $valueMap
	 */ public function setValueMap(array $valueMap)
	{
		$this->valueMap = $valueMap;
	}

}
