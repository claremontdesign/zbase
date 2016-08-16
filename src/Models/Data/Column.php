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
	 * The Row
	 * @var EntityInterface
	 */
	protected $_row = null;

	/**
	 *
	 * @var UiInterface
	 */
	protected $_value = null;

	/**
	 * is prepared?
	 * @var boolean
	 */
	protected $_prepared = false;

	/**
	 * If Template Mode
	 * @var integer
	 */
	protected $_templateMode = false;

	/**
	 * Constructor
	 * @param string $name ID/Name
	 * @param array $attributes array of attributes/configuration
	 */
	public function __construct(array $attributes = null)
	{
		parent::__construct($attributes);
	}

	/**
	 * Template Mode
	 * @param boolean $flag
	 */
	public function setTemplateMode($flag)
	{
		$this->_templateMode = $flag;
		return $this;
	}

	/**
	 * Check if enabled
	 */
	public function enable()
	{
		if($this->json() && !zbase_is_json())
		{
			return false;
		}
		return zbase_value_get($this->getAttributes(), 'enable', false);
	}

	/**
	 * Check if enabled by request
	 * If true, this column is only for JSON request
	 *
	 * @return boolean
	 */
	public function json()
	{
		return zbase_value_get($this->getAttributes(), 'json', false);
	}

	/**
	 * Render the Value
	 * @param string $tag The HTML Tag
	 * @return string
	 */
	public function renderValue($tag = null)
	{
		$this->prepare();
		if(!empty($tag))
		{
			$str = [];
			$str[] = '<' . $tag . '>';
			if(!empty($this->_templateMode))
			{
				$str[] = '__' . $this->id() . '__';
			}
			else
			{
				$str[] = $this->_value;
			}
			$str[] = '</' . $tag . '>';
			return implode("\n", $str);
		}
		return $this->_value;
	}

	/**
	 * Prepare the Column
	 */
	public function prepare()
	{
		if(empty($this->_prepared))
		{
			$this->_value();
		}
	}

	/**
	 * Return the data type
	 * @return string
	 */
	public function getDataType()
	{
		return zbase_data_get($this->data, 'type', 'string');
	}

	/**
	 * Return the value index relative to the $this->_row
	 * @return string
	 */
	public function getValueIndex()
	{
		return zbase_data_get($this->data, 'index', null);
	}

	/**
	 * Extract value from row
	 */
	protected function _value()
	{
		if(!empty($this->_templateMode))
		{
			return;
		}
		$noUiDataTypes = ['integer', 'string'];
		/**
		 * Classname and will call className::columnValue
		 */
		$dataCallback = zbase_data_get($this->data, 'callback', null);
		$dataType = $this->getDataType();
		$valueIndex = $this->getValueIndex();
		if(!empty($dataCallback))
		{
			$value = $dataCallback::entityDataValue($this->getRow(), $this);
		}
		else
		{
			$value = zbase_data_get($this->getRow(), $valueIndex);
		}
		if(in_array($dataType, $noUiDataTypes))
		{
			$this->_value = $value;
		}
		else
		{
			$dataTypeConfiguration = [
				'id' => $dataType,
				'type' => 'data.' . $dataType,
				'enable' => true,
				'value' => $value,
				'hasAccess' => true,
				'options' => $this->getAttribute('options'),
			];
			$this->_value = \Zbase\Ui\Ui::factory($dataTypeConfiguration);
		}
	}

	/**
	 *
	 * @param type $value
	 * @return \Zbase\Models\Data\Column
	 */
	public function setValue($value)
	{
		$this->_value = $value;
		return $this;
	}

	/**
	 *
	 * @return type
	 */
	public function getValue()
	{
		return $this->_value;
	}

	/**
	 *
	 * @param \Zbase\Interfaces\EntityInterface $row
	 * @return \Zbase\Models\Data\Column
	 */
	public function setRow(\Zbase\Interfaces\EntityInterface $row)
	{
		$this->_row = $row;
		$this->_prepared = false;
		return $this;
	}

	/**
	 *
	 * @return \Zbase\Interfaces\EntityInterface
	 */
	public function getRow()
	{
		return $this->_row;
	}

}
