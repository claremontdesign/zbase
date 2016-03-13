<?php

namespace Zbase\Widgets\Type;

/**
 * Zbase-Widgets Widget-Type Datatable
 *
 * https://developer.mozilla.org/en-US/docs/Web/Accessibility/ARIA/ARIA_Techniques/Using_the_aria-labelledby_attribute
 * http://v4-alpha.getbootstrap.com/components/forms/#form-controls
 * Process and Displays a dynamic form
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Datatable.php
 * @project Zbase
 * @package Zbase/Widgets/Type
 *
 *
 */
use Zbase\Widgets;

class Datatable extends Widgets\Widget implements Widgets\WidgetInterface, Widgets\ControllerInterface
{

	/**
	 * Widget Type
	 * @var string
	 */
	protected $_type = 'datatable';

	/**
	 * The ViewFile string
	 * @var string
	 */
	protected $_viewFile = 'ui.datatable';

	/**
	 * The View Type grid|row|list
	 * @var string
	 */
	protected $_viewType = 'grid';

	/**
	 *
	 * @var type
	 */
	protected $_entity = null;

	/**
	 * Processed Grid Columns
	 * @var array
	 */
	protected $_columns = [];

	/**
	 * Processed Grid Columns
	 * @var \Zbase\Models\Data\Column[]
	 */
	protected $_processedColumns = [];

	/**
	 * Columns prepared?
	 * @var boolean
	 */
	protected $_columnsPrepared = false;

	/**
	 * 	Rows
	 * @var \Zbase\Entity\EntityInterface
	 */
	protected $_rows = [];

	/**
	 * Rows are prepared?
	 * @var boolean
	 */
	protected $_rowsPrepared = false;
	protected $_htmlWrapperAttributes = ['class' => ['table-responsive']];

	/**
	 * Prepare
	 */
	protected function _pre()
	{
		parent::_pre();
		$this->entity();
		$this->_rows();
		$this->_columns();
	}

	// <editor-fold defaultstate="collapsed" desc="Rows">
	/**
	 * Prepare and fetch all rows
	 */
	protected function _rows()
	{
		if(empty($this->_rowsPrepared))
		{
			$this->entity();
			$this->_rows = $this->_entity->all();
			$this->_rowsPrepared = true;
		}
	}

	/**
	 * Return the fetch rows
	 * @var \Zbase\Entity\EntityInterface[]
	 */
	public function getRows()
	{
		$this->_rows();
		return $this->_rows;
	}

	// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc="COLUMNS">
	/**
	 * Prepare Columns
	 */
	protected function _columns()
	{
		if(empty($this->_columnsPrepared))
		{
			if(!empty($this->_columns))
			{
				foreach ($this->_columns as $name => $config)
				{
					if(empty($config['id']))
					{
						$config['id'] = $name;
					}
					$col = new \Zbase\Models\Data\Column($config);
					$this->_processedColumns[$name] = $col;
				}
			}
			$this->_columnsPrepared = true;
			$this->_processedColumns = $this->sortPosition($this->_processedColumns);
		}
	}

	/**
	 * Set Columns
	 * @param array $columns
	 */
	public function setColumns($columns)
	{
		$this->_columnsPrepared = false;
		$this->_processedColumns = null;
		$this->_columns = $columns;
		$this->_columns();
	}

	/**
	 * Return all columns
	 * @return array
	 */
	public function getColumns()
	{
		$this->_columns();
		return $this->_columns;
	}

	/**
	 * Return the Processed Columns
	 * @return \Zbase\Models\Data\Column[]
	 */
	public function getProcessedColumns()
	{
		return $this->_processedColumns;
	}

	// </editor-fold>

	/**
	 * Controller Action
	 * 	This will be called validating the form
	 * @param string $action
	 */
	public function controller($action)
	{

	}

	/**
	 * Validate widget
	 */
	public function validateWidget()
	{

	}


	/**
	 * Return the Wrapper Attributes
	 * @return array
	 */
	public function wrapperAttributes()
	{
		$attr = parent::wrapperAttributes();
		$attr['class'][] = 'zbase-widget-wrapper';
		$attr['id'] = 'zbase-widget-wrapper-' . $this->id();
		return $attr;
	}

}
