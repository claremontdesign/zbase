<?php

namespace Zbase\Models\View;

/**
 * Stylesheet
 * http://www.w3schools.com/tags/tag_link.asp
 * The <link> element defines the page relationship to an external resource.
 * output: <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css" />
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Stylesheet.php
 * @project Zbase
 * @package Zbase\Models\View
 */
use Zbase\Interfaces;
use Zbase\Traits;
use Zbase\Models\View;

class Stylesheet extends View\HeadLink implements Interfaces\PositionInterface, Interfaces\AttributeInterface
{

	use Traits\Position;

	/**
	 * The HTML Prefix
	 * @see Traits\Html::getHtmlId
	 * @var string
	 */
	protected $htmlPrefix = 'stylesheet-';

	/**
	 * Constructor
	 * @param array $attributes
	 */
	public function __construct($attributes)
	{
		parent::__construct($attributes);
		$this->setRel('stylesheet');
	}
}
