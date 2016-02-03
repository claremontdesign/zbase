<?php

namespace Zbase\Traits;

/**
 * Zbase-Id
 *
 * ReUsable Traits - Placeholder
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Placeholder.php
 * @project Zbase
 * @package Zbase/Traits
 */
trait Placeholder
{

	/**
	 * Placeholder
	 *
	 * head_prepend
	 * head_append
	 * body_prepend
	 * body_append
	 * body_class
	 * main_content_prepend
	 * main_content_append
	 * widget_$id_append
	 * widget_$id_prepend
	 * toolbar_top_left
	 * toolbar_top_right
	 * toolbar_bottom_left
	 * toolbar_bottom_right
	 *
	 * @var string
	 */
	protected $placeholder = null;

	/**
	 * Return placeholder
	 * @return string
	 */
	public function getPlaceholder()
	{
		return $this->placeholder;
	}

	/**
	 * Set the placeholder
	 *
	 * @param string $placeholder
	 */
	public function setPlaceholder($placeholder)
	{
		$this->placeholder = $placeholder;
	}
}
