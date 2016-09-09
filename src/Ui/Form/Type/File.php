<?php

namespace Zbase\Ui\Form\Type;

/**
 * Zbase-Form Element-File
 *
 * Element-File
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file File.php
 * @project Zbase
 * @package Zbase/Ui/Form/Elements
 */
class File extends \Zbase\Ui\Form\Element
{

	/**
	 * Widget Type
	 * @var string
	 */
	protected $_type = 'file';
	protected $_viewFile = 'ui.form.type.file';

	/**
	 * If to use multiple Uploader
	 * @return boolean
	 */
	public function isMultiple()
	{
		return $this->_v('uploader.multiple', false);
	}

	/**
	 * Uplaod will be on Form Submit
	 * @return boolean
	 */
	public function uploadOnFormSubmit()
	{
		return $this->_v('uploader.onFormSubmit', false);
	}

}
