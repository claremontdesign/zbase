<?php

namespace Zbase\Models\View;

/**
 * HeadMeta
 * http://www.w3schools.com/tags/tag_meta.asp
 * Metadata is data (information) about data.
 * 	The <meta> tag provides metadata about the HTML document. Metadata will not be displayed on the page, but will be machine parsable.
 * 	Meta elements are typically used to specify page description, keywords, author of the document, last modified, and other metadata.
 * 	The metadata can be used by browsers (how to display content or reload page), search engines (keywords), or other web services.
 *
 * <code>
 * 	zbase_view_head_meta_add('viewport', 'width=1020', null, null, ['http-equiv' => 'Content-Language']);
 * 	<meta name="viewport" content="width=1020" http-equiv="Content-Language"/>
 *
 * 	zbase_view_head_meta_add('viewport', 'width=1020', 'lte IE 8', null, ['http-equiv' => 'Content-Language']);
 * 	<!--[lte IE 8]><meta name="viewport" content="width=1020" http-equiv="Content-Language"/><![endif]-->
 * </code>
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

class HeadMeta implements Interfaces\IdInterface, Interfaces\HtmlInterface, Interfaces\AttributeInterface, Interfaces\PositionInterface
{

	use Traits\Attribute,
	 Traits\Html,
	 Traits\Position,
	 Traits\Id;

	/**
	 * Gives the value associated with the http-equiv or name attribute
	 * @var string
	 */
	protected $content = null;

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
		$name = !empty($this->name()) ? ' name="' . $this->name() . '"' : null;
		$content = !empty($this->getContent()) ? ' content="' . $this->getContent() . '"' : null;
		return $this->wrapWithHtmlConditions('<meta' . $name . $content . ' ' . $this->renderHtmlAttributes() . '/>');
	}

	/**
	 * @see class::$content
	 * @return string
	 */
	public function getContent()
	{
		return $this->content;
	}

	/**
	 * @see class::$content
	 * @param string $content
	 */
	public function setContent($content)
	{
		$this->content = $content;
	}

}
