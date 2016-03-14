<?php

namespace Zbase\Ui\Component;

/**
 * Zbase-Ui-Component-PageHeader
 *
 * PageHeader
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file PageHeader.php
 * @project Zbase
 * @package Zbase/Ui/Components
 */
use Zbase\Traits;
use Zbase\Interfaces;
use Zbase\Ui as UIs;

class PageHeader extends UIs\Ui implements UIs\UiInterface, Interfaces\IdInterface
{

	use Traits\Attribute,
	 Traits\Id,
	 Traits\Position,
	 Traits\Html;

	/**
	 * Widget Type
	 * @var string
	 */
	protected $_type = 'pageHeader';

	/**
	 * The view File to use
	 * @var string
	 */
	protected $_viewFile = 'ui.components.pageheader';

	/**
	 * Tag to use
	 * @var string
	 */
	protected $_tag = 'h3';

	/**
	 * The Text to display
	 * @var string
	 */
	protected $_text = null;

	/**
	 * Tag to use
	 * @return string
	 */
	public function getTag()
	{
		return $this->_tag;
	}

	public function setTag($tag)
	{
		$this->_tag = $tag;
		return $this;
	}

	/**
	 * Set Text
	 * @param type $text
	 * @return \Zbase\Ui\PageHeader
	 */
	public function setText($text)
	{
		$this->_text = $text;
		return $this;
	}

	public function getText()
	{
		return $this->_text;
	}

	/**
	 * Return the Wrapper Attributes
	 * @return array
	 */
	public function wrapperAttributes()
	{
		$attr = $this->_v('html.attributes.wrapper', []);
		$attr['class'][] = 'zbase-ui-wrapper';
		$attr['class'][] = 'zbase-ui-wrapper-' . $this->_type;
		return $attr;
	}

}
