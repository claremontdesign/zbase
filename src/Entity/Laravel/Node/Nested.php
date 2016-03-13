<?php

namespace Zbase\Entity\Laravel\Node;

/**
 * Zbase-Node Nested
 *
 * Node Entity Base Model
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Nested.php
 * @project Nested
 * @package Zbase/Entity/Node
 */
use Zbase\Entity\Laravel\Entity as BaseEntity;
use Zbase\Widgets\EntityInterface as WidgetEntityInterface;

class Nested extends BaseEntity implements WidgetEntityInterface
{

	/**
	 * Widget entity interface.
	 * 	Data should be validated first before passing it here
	 * @param string $method post|get
	 * @param string $action the controller action
	 * @param array $data validated; assoc array
	 * @param Zbase\Widgets\Widget $widget
	 */
	public function widgetController($method, $action, $data, \Zbase\Widgets\Widget $widget)
	{
		if(strtolower($method) == 'post')
		{
			if($action == 'index')
			{

			}
		}
	}

	// <editor-fold defaultstate="collapsed" desc="COLUMNS">
	/**
	 * Return table minimum columns requirement
	 * @return array
	 */
	public static function columns()
	{
		$columns = [];
		$columns['parent_id'] = [
			'hidden' => false,
			'fillable' => false,
			'type' => 'integer',
			'length' => 16,
			'nullable' => true,
			'unsigned' => true,
			'index' => true,
			'comment' => 'Nested Parent Id'
		];
		$columns['lft'] = [
			'hidden' => false,
			'fillable' => false,
			'type' => 'integer',
			'unsigned' => true,
			'nullable' => true,
			'length' => 16,
			'index' => true,
			'comment' => 'Nested Left'
		];
		$columns['rgt'] = [
			'hidden' => false,
			'fillable' => false,
			'type' => 'integer',
			'nullable' => true,
			'length' => 16,
			'index' => true,
			'comment' => 'Nested Right'
		];
		$columns['depth'] = [
			'hidden' => false,
			'fillable' => false,
			'type' => 'integer',
			'nullable' => true,
			'length' => 16,
			'index' => true,
			'comment' => 'Nested Depth'
		];
		return $columns;
	}

	// </editor-fold>
}
