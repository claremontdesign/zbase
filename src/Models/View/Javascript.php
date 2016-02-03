<?php

namespace Zbase\Models\View;

/**
 * Javascript
 * http://www.w3schools.com/tags/tag_link.asp
 * The <link> element defines the page relationship to an external resource.
 * output: <script type='text/javascript' src='script.js'></script>
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Javascript.php
 * @project Zbase
 * @package Zbase\Models\View
 */
use Zbase\Interfaces;
use Zbase\Traits;

class Javascript implements Interfaces\IdInterface, Interfaces\HtmlInterface, Interfaces\PositionInterface, Interfaces\AttributeInterface, Interfaces\PlaceholderInterface
{

	use Traits\Attribute,
	 Traits\Html,
	 Traits\Placeholder,
	 Traits\Position,
	 Traits\Id;

	/**
	 * The HTML Prefix
	 * @see Traits\Html::getHtmlId
	 * @var string
	 */
	protected $htmlPrefix = 'javascript-';

	/**
	 * Specifies the URL of an external script file
	 * @var string
	 */
	protected $src = null;

	/**
	 * Constructor
	 * @param array $attributes
	 */
	public function __construct($attributes)
	{
		$this->setPlaceholder('body_javascripts');
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
		$src = $this->getSrc();
		if(!empty($id) && !empty($src))
		{
			return $this->wrapWithHtmlConditions('<script id="' . $this->getHtmlId() . '" type="text/javascript" src="' . $src . '"' . $this->renderHtmlAttributes() . '></script>');
		}
		return '';
	}

	/**
	 * Retrieve the URL of an external script file
	 * @return string
	 */
	function getSrc()
	{
		return $this->src;
	}

	/**
	 * Specifies the URL of an external script file
	 * @param string $src
	 */
	function setSrc($src)
	{
		$this->src = $src;
	}

}
