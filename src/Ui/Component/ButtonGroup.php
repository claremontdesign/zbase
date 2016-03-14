<?php

namespace Zbase\Ui\Component;

/**
 * Zbase-Ui-Component-ButtonGroup
 *
 * ButtonGroup
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file ButtonGroup.php
 * @project Zbase
 * @package Zbase/Ui/Components
 */
use Zbase\Traits;
use Zbase\Interfaces;
use Zbase\Ui as UIs;

class ButtonGroup extends UIs\Ui implements UIs\UiInterface, Interfaces\IdInterface
{

	use Traits\Attribute,
	 Traits\Id,
	 Traits\Position,
	 Traits\Html;

	/**
	 * Widget Type
	 * @var string
	 */
	protected $_type = 'buttonGroup';

	/**
	 * The view File to use
	 * @var string
	 */
	protected $_viewFile = 'ui.components.buttonGroup';

	/**
	 * Return the Wrapper Attributes
	 * @return array
	 */
	public function wrapperAttributes()
	{
		$attr = $this->_v('html.attributes.wrapper', []);
		$attr['class'][] = 'zbase-ui-btngroup';
		$attr['class'][] = 'btn-group';
		$attr['role'][] = 'btn-group';
		$attr['aria-label'][] = $this->ariaLabel;
		return $attr;
	}

}
