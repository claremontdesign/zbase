<?php

namespace Zbase\Models\View;

/**
 * HeadLink
 * http://www.w3schools.com/tags/tag_link.asp
 * The <link> element defines the page relationship to an external resource.
 * output: <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css" />
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file HeadMeta.php
 * @project Zbase
 * @package Zbase\Models\View
 */
use Zbase\Interfaces;
use Zbase\Traits;

class HeadLink implements Interfaces\IdInterface, Interfaces\HtmlInterface, Interfaces\AttributeInterface
{

	use Traits\Attribute,
	 Traits\Html,
	 Traits\Id;

	/**
	 * The HTML Prefix
	 * @see Traits\Html::getHtmlId
	 * @var string
	 */
	protected $htmlPrefix = 'headlink-';

	/**
	 * Specifies the location of the linked document
	 * @var string
	 */
	protected $href = null;

	/**
	 * Specifies the media type of the linked document
	 * @var string
	 */
	protected $type = null;

	/**
	 * The relationship between the current document and the linked document
	 * @var type
	 */
	protected $rel = null;

	/**
	 * Constructor
	 * @param array $attributes
	 */
	public function __construct($attributes)
	{
		$this->setAttributes($attributes);
		if($this->getRel() == 'stylesheet')
		{
			$this->setType('text/css');
		}
	}

	/**
	 * Render
	 *
	 * @return string
	 */
	public function __toString()
	{
		$id = $this->id();
		$href = $this->getHref();
		$rel = $this->getRel();
		if(!empty($id) && !empty($href) && !empty($rel))
		{
			return $this->wrapWithHtmlConditions('<link id="' . $this->getHtmlId() . '" href="' . $href . '" rel="' . $rel . '" type="' . $this->getType() . '" ' . $this->renderHtmlAttributes() . '/>');
		}
		return '';
	}

	/**
	 * @see class::$href
	 * @return string
	 */
	public function getHref()
	{
		return $this->href;
	}

	/**
	 * @see class::$type
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @see class::$rel
	 * @return string
	 */
	public function getRel()
	{
		return $this->rel;
	}

	/**
	 * @see class::$href
	 */
	public function setHref($href)
	{
		$this->href = $href;
	}

	/**
	 * @see class::$type
	 */
	public function setType($type)
	{
		$this->type = $type;
	}

	/**
	 * @see class::$rel
	 */
	public function setRel($rel)
	{
		$this->rel = $rel;
		if($rel == 'stylesheet')
		{
			$this->setType('text/css');
		}
	}

}
