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

class Db extends Datatable implements Widgets\WidgetInterface, Widgets\ControllerInterface
{

	/**
	 * Widget Type
	 * @var string
	 */
	protected $_type = 'db';

	/**
	 * The ViewFile string
	 * @var string
	 */
	protected $_viewFile = false;

	// <editor-fold defaultstate="collapsed" desc="Rows">
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

	/**
	 * Controller Action
	 * 	This will be called validating the form
	 * @param string $action
	 */
	public function controller($action)
	{
		if(!$this->checkUrlRequest())
		{
			return zbase_abort(404);
		}
		$this->_rows();
	}

	/**
	 * Validate widget
	 */
	public function validateWidget()
	{
		$this->_pre();
	}

	protected function _pre()
	{
		$this->entity();
	}

	/**
	 * Prepare and fetch all rows
	 */
	protected function _rows()
	{
		if(empty($this->_rowsPrepared))
		{
			try
			{
				if(!empty($this->_entity))
				{
					$repo = $this->_repo();
					$repoMethod = $this->_v('repo.method', 'count');
					if($repoMethod == 'count')
					{
						if($this->_entity->hasSoftDelete())
						{
							$this->_rows = $repo->withTrashed()->count($this->_repoFilters, $this->_repoJoins);
						}
						else
						{
							$this->_rows = $repo->count($this->_repoFilters, $this->_repoJoins);
						}
					}
				}
				$this->_rowsPrepared = true;
			} catch (\Zbase\Exceptions\RuntimeException $e)
			{
				if(zbase_in_dev($e))
				{
					dd($e);
				}
				else
				{
					zbase_abort(500);
				}
			}
		}
	}

	/**
	 *
	 */
	public function __toString()
	{
		$v = $this->_rows;
		return (string) $v;
	}

}
