<?php

namespace Zbase\Models\View;

/**
 * String
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Script.php
 * @project Zbase
 * @package Zbase\Models\View
 */
use Zbase\Interfaces;
use Zbase\Traits;

class String implements Interfaces\IdInterface, Interfaces\HtmlInterface, Interfaces\PositionInterface, Interfaces\PlaceholderInterface, Interfaces\AttributeInterface
{

	use Traits\Attribute,
	 Traits\Html,
	 Traits\Position,
	 Traits\Placeholder,
	 Traits\Id;

	/**
	 * The HTML Prefix
	 * @see Traits\Html::getHtmlId
	 * @var string
	 */
	protected $htmlPrefix = 'string-';

	/**
	 * The script content
	 * @var string
	 */
	protected $string = null;

	/**
	 * Constructor
	 * @param array $attributes
	 */
	public function __construct($attributes)
	{
		$this->setAttributes($attributes);
	}

	/**
	 * Render
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->getString() . ' ';
	}

	/**
	 * Set the String
	 * @return type
	 */
	function getString()
	{
		return $this->string;
	}

	/**
	 * return The String
	 * @param string $string
	 */
	function setString($string)
	{
		$this->string = $string;
	}

}
