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

	use Traits\Id,
	 Traits\Html;

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
	 * Is column hidden?
	 */
	public function isHidden()
	{
		return zbase_value_get($this->getAttributes(), 'hidden', false);
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
			$str[] = '<' . $tag . ' ' . $this->renderTagAttribute('td') . '>';
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
	 * The Filter Element
	 */
	public function renderFilterElement()
	{
		if($this->filterable())
		{
			$inputAttributes = zbase_data_get($this->getAttributes(), 'filter.input', []);
			$type = $this->filterType();
			$element = [
				'html' => [
					'attributes' => [
						'input' => [
							'class' => [
								'form-filter input-sm element-data-filter element-data-filter-' . $type
							]
						]
					]
				]
			];
			$element['id'] = $this->filterId();
			$element['enable'] = true;
			$element['label'] = false;
			$element['option'] = ['inputWrapper' => false];
			$element = array_replace_recursive($element, $inputAttributes);
			switch ($type)
			{
				case 'datetime':
				case 'timestamp':
				case 'date':
					$type = 'date';
					$element['type'] = 'date';
					break;
				case 'integer':
					$element['type'] = 'integer';
					break;
				case 'select':
					$element['type'] = 'select';
					$element['multiOptions'] = zbase_data_get($this->getAttributes(), 'filter.selectOptions', []);
					;
					break;
				default;
					$element['type'] = 'text';
			}
			if($type == 'date')
			{
				$element2 = $element;
				$element['id'] = $element2['id'] . '_from';
				$element2['id'] = $element2['id'] . '_to';
				$element['html']['attributes']['input']['placeholder'] = 'From';
				$element2['html']['attributes']['input']['placeholder'] = 'To';
				$e = \Zbase\Ui\Form\Element::factory($element);
				$e2 = \Zbase\Ui\Form\Element::factory($element2);
				return $e->render() . $e2->render();
			}
			$e = \Zbase\Ui\Form\Element::factory($element);
			return $e->render();
		}
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
	 * Is column sortable
	 */
	public function sortable()
	{
		return zbase_data_get($this->getAttributes(), 'sorting.enable', false);
	}

	/**
	 * Sort Id
	 * @return string
	 */
	public function sortableId()
	{
		return zbase_string_camel_case($this->id() . '_sort');
	}

	/**
	 * Return the sort queryString
	 * @return string
	 */
	public function sortRequestString()
	{
		$sort = 'asc';
		$sortDirection = $this->sortableDirection();
		if(!empty($sortDirection))
		{
			$sort = $sortDirection;
		}
		return strtolower($this->id() . '_' . $sort);
	}

	/**
	 * Column current sort direction
	 * @return null|string asc or desc; null if no sorting
	 */
	public function sortableDirection()
	{
		$sorts = zbase_request_input('sort', zbase_request_query_input('sort', []));
		/**
		 * sorts[]=id_desc&sort[]=id2_asc
		 */
		$dirs = ['asc', 'desc'];
		if(!empty($sorts))
		{
			/**
			 * Each sort is: id2_asc
			 */
			foreach ($sorts as $sort)
			{
				$sort = explode('_', $sort);
				if($sort[0] == $this->id() && in_array($sort[1], $dirs))
				{
					return $sort[1];
				}
			}
		}
		return null;
	}

	/**
	 * Return the Sortable Index. The DB column to index
	 * @return string column name like: COLUMNANME or TABLE.COLUMNANME or TABLE_ALIAS.COLUMNANME
	 */
	public function sortableIndex()
	{
		return zbase_data_get($this->getAttributes(), 'sorting.column', zbase_data_get($this->getAttributes(), 'sorting.index', $this->id()));
	}

	/**
	 * if filterable
	 * @return boolean
	 */
	public function filterable()
	{
		return zbase_data_get($this->getAttributes(), 'filter.enable', false);
	}

	/**
	 * Return the FilterType
	 * @return string
	 */
	public function filterType()
	{
		return zbase_data_get($this->getAttributes(), 'filter.type', $this->getDataType());
	}

	/**
	 * Return the FilterType
	 * @return string
	 */
	public function filterId()
	{
		return zbase_string_camel_case($this->id() . '_filter');
	}

	/**
	 * array of FilterIds
	 * @return array
	 */
	public function filterIds()
	{
		$type = $this->filterType();
		switch ($type)
		{
			case 'datetime':
			case 'timestamp':
			case 'date':
				return [
					zbase_string_camel_case($this->id() . '_filter_to'),
					zbase_string_camel_case($this->id() . '_filter_from'),
				];
				break;
			default;
				return [
					zbase_string_camel_case($this->id() . '_filter'),
				];
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

	public function __renderTagAttribute($tag)
	{
		$attributes = [];
		if(method_exists($this, 'getAttributes'))
		{
			$attributes = $this->getAttributes();
		}
		$attributes = zbase_value_get($attributes, 'html.attributes.' . $tag, []);
		$styles = !empty($attributes['style']) ? $attributes['style'] : [];
		$classes = [];
		$ariaLabel = $this->getLabel();
		if(!empty($attributes['class']))
		{
			if(is_array($attributes['class']))
			{
				$classes = $attributes['class'];
			}
			else
			{
				$classes[] = $attributes['class'];
			}
		}
		if(!is_array($styles))
		{
			$styles = [$styles];
		}
		if($tag == 'th')
		{
			if($this->sortable())
			{
				$sortDirection = $this->sortableDirection();
				$classes[] = 'zbase-td-sorting';
				if(!empty($sortDirection))
				{
					$classes[] = 'sorting_' . $sortDirection;
					if($sortDirection == 'asc')
					{
						$ariaLabel .= ' Activate to sort column ascending.';
					}
					else
					{
						$ariaLabel .= ' Activate to sort column descending.';
					}
				}
				else
				{
					$ariaLabel .= ' Activate to sort ascending.';
					$classes[] = 'sorting';
				}
				$attributes['data-sorting'] = $this->id();
			}
			$attributes['id'] = 'datatable_th_' . $this->id();
		}
		if($this->isHidden())
		{
			$styles[] = 'display:none;';
		}
		$classes[] = 'column-' . $tag . '-' . $this->getDataType();
		$attributes['style'] = implode('', $styles);
		$attributes['class'] = implode(' ', $classes);
		if($tag == 'th')
		{
			$attributes['role'] = 'columnheader';
			$attributes['aria-controls'] = 'datatable_orders';
			$attributes['aria-label'] = $ariaLabel;
		}
		return $this->renderHtmlAttributes($attributes);
	}

}
