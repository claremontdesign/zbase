<?php

namespace Zbase\Entity\Laravel\Message;

/**
 * Zbase-Node Entity
 *
 * Node Entity Base Model
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Node.php
 * @project Zbase
 * @package Zbase/Entity/Node
 */
class File extends \Zbase\Entity\Laravel\Node\File
{

	/**
	 * The Entity Name
	 * @var string
	 */
	protected $entityName = 'messages_files';

	/**
	 * If URL was given, what to do?
	 * @var boolean
	 */
	protected $urlToFile = false;

	/**
	 * The Node Name Prefix
	 * @var string
	 */
	public static $nodeNamePrefix = 'messages';

	/**
	 * Return table minimum columns requirement
	 * @param array $columns Some columns
	 * @param array $entity Entity Configuration
	 * @return array
	 */
	public static function tableColumns($columns = [], $entity = [])
	{
		$columns['node_id']['foreign']['column'] = 'message_id';
		return $columns;
	}

}
