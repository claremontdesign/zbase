<?php

namespace Zbase\Interfaces;

/**
 * Zbase-Interface-HTML Interface
 *
 * HtmlInterface
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file HtmlInterface.php
 * @project Zbase
 * @package Zbase/Interfaces
 */
interface HtmlInterface
{

	/**
	 * Render the HTML Attributes
	 *
	 * @return array
	 */
	public function renderHtmlAttributes();

	/**
	 * Render HTML
	 * @return string
	 */
	public function __toString();
}
