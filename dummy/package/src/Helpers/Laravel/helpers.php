<?php

/**
 * Packagename Helpers
 *
 * Functions and Helpers
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file ConstructNow.php
 * @project Packagename
 * @package Packagename/Helpers
 */

/**
 * Return Packagename Main model
 * @return Zbase\Interfaces\ZbaseInterface
 */
function packagename()
{
	return app('packagename');
}
