<?php

/**
 * Zbase Helpers - Translate
 *
 * Functions and Helpers Translate
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file translate.php
 * @project Zbase
 * @package Zbase\Helpers
 */

/**
 * Translate a text
 * @param string $text Text to translate
 * @return string
 */
function _zt($text, $replace = [])
{
	return strtr($text, $replace);
}
