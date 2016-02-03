<?php

namespace Zbase\Models\View;

/**
 * Style
 * output: <style type="text/css">#selector{display:block;}</style>
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Style.php
 * @project Zbase
 * @package Zbase\Models\View
 */
use Zbase\Interfaces;
use Zbase\Traits;

class Style implements Interfaces\IdInterface, Interfaces\HtmlInterface, Interfaces\PositionInterface, Interfaces\AttributeInterface
{

	use Traits\Attribute,
	 Traits\Html,
	 Traits\Position,
	 Traits\Id;

	/**
	 * The HTML Prefix
	 * @see Traits\Html::getHtmlId
	 * @var string
	 */
	protected $htmlPrefix = 'style-';

	/**
	 * The style content
	 * @var string
	 */
	protected $style = null;

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
		$id = $this->id();
		$style = $this->getStyle();
		if(!empty($id) && !empty($style))
		{
			return EOF . '<style type="text/css" id="' . $this->getHtmlId() . '">' . EOF . $style . EOF . '</style>' . EOF;
		}
		return '';
	}

	/**
	 * Return the style
	 * @return string
	 */
	function getStyle()
	{
		return $this->style;
	}

	/**
	 * Set the style
	 * @param string $style
	 */
	function setStyle($style)
	{
		$this->style = $style;
	}

}
