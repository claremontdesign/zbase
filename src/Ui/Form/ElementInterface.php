<?php

namespace Zbase\Ui\Form;

/**
 * Zbase-Form Element
 *
 * Element Base Model
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Element.php
 * @project Zbase
 * @package Zbase/Widgets
 */
interface ElementInterface
{
	public function __toString();

	public function getType();

	public function getValidationRules();

	public function getValidationMessages();
}
