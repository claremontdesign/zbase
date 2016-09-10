<?php

/**
 * Zbase Helpers - Data, Columns, Datatables
 *
 * Functions and Helpers Data, Columns, Datatables
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file data.php
 * @project Zbase
 * @package Zbase\Helpers
 */
function zbase_data_column($columnName, $columnConfig)
{
	$columnConfig['id'] = $columnName;
	$columnConfig['name'] = $columnName;
	if(!empty($columnConfig['label']))
	{
		$columnConfig['title'] = $columnConfig['label'];
	}
	if(!empty($columnConfig['comment']))
	{
		$columnConfig['description'] = $columnConfig['comment'];
		if(empty($columnConfig['title']))
		{
			$columnConfig['title'] = $columnConfig['comment'];
		}
	}
	return new \Zbase\Models\Data\Column($columnConfig);
}