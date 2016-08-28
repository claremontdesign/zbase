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
class Timestamp extends Data
{

	/**
	 * Widget Type
	 * @var string
	 */
	protected $_type = 'timestamp';

	/**
	 * Set Value
	 * @param type $value
	 * @return \Zbase\Ui\PageHeader
	 */
	public function setValue($value)
	{
		parent::setValue($value);
		$this->_viewParams['date'] = $value;
		return $this;
	}

	/**
	 * HTML the ui
	 * @return string
	 */
	public function __toString()
	{
		$this->prepare();
		try
		{
			if(!is_null($this->_viewFile) && empty($this->_rendered))
			{
				$this->_viewParams['ui'] = $this;
				$str = $this->htmlPreContent();
				$str .= zbase_view_render(zbase_view_file_contents($this->_viewFile), $this->getViewParams());
				$str .= $this->htmlPostContent();
				$this->_rendered = true;
				return $str;
			}
			return '';
		} catch (\Exception $e)
		{
			if(zbase_is_dev())
			{
				dd($e);
			}
			zbase_abort(500);
		}
	}
}
