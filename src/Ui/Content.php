<?php

namespace Zbase\Ui;

/**
 * Zbase-Form Tab
 *
 * Tab Base Model
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Tab.php
 * @project Zbase
 * @package Zbase/Widgets
 */
use Zbase\Interfaces;
use Zbase\Traits;
use Zbase\Ui as UIs;

class Content extends UIs\Ui implements UIs\UiInterface, Interfaces\IdInterface
{

	use Traits\Attribute,
	 Traits\Id,
	 Traits\Position,
	 Traits\Html;

	/**
	 * UI Type
	 * @var string
	 */
	protected $_type = 'content';

	/**
	 * The Content
	 * @var string
	 */
	protected $_content = null;

	/**
	 * The View File
	 * @var string
	 */
	protected $_viewFile = 'ui.content';

	/**
	 * Return the Content
	 * @return string
	 */
	public function getContent()
	{
		return $this->_content;
	}

	/**
	 * Set the Content
	 * @param string $content
	 * @return \Zbase\Ui\Content
	 */
	public function setContent($content)
	{
		$this->_content = $content;
		return $this;
	}

	/**
	 * Return the Wrapper Attributes
	 * @return array
	 */
	public function wrapperAttributes()
	{
		$attr = parent::wrapperAttributes();
		$attr['class'][] = 'zbase-ui-' . $this->_type;
		return $attr;
	}

}
