<?php

namespace Zbase\Models;

/**
 * Zbase-Model-Ui
 *
 * Model for the Ui and Ui collections
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Ui.php
 * @project Zbase
 * @package Zbase/Model
 */
class Ui
{

	/**
	 * The Tabs Model
	 * @var \Zbase\Models\Ui\Tabs
	 */
	protected $tabs = null;

	/**
	 * Return the Tabs Model
	 * @return \Zbase\Models\Ui\Tabs
	 */
	public function tabs()
	{
		if(!$this->tabs instanceof \Zbase\Models\Ui\Tabs)
		{
			$className = zbase_model_name('ui.tabs', null, '\Zbase\Models\Ui\Tabs');
			$this->tabs = new $className;
		}
		return $this->tabs;
	}

	/**
	 * Return the Mobile Framework to use
	 */
	public function themeFramework()
	{
		return 'angular';
	}

}
