<?php

namespace Zbase\Traits;

/**
 * Zbase-Id
 *
 * ReUsable Traits - Id
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Id.php
 * @project Zbase
 * @package Zbase/Traits
 */
trait Id
{

	/**
	 * Id
	 * @var string
	 */
	protected $id;

	/**
	 * Name
	 * @var string
	 */
	protected $name;

	/**
	 * Title
	 * @var string
	 */
	protected $title;

	/**
	 * Description
	 * @var string
	 */
	protected $description;

	/**
	 * Retrieve the attribute::id
	 * @return string
	 */
	public function id()
	{
		return $this->id;
	}

	/**
	 * @see $id
	 * @param string $id
	 */
	public function setId($id)
	{
		$this->id = $id;
	}

	/**
	 * Retrieve the object attribute::name
	 * @return string
	 */
	public function name()
	{
		return $this->name;
	}

	/**
	 * @see $name
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * Return the object attribute::title
	 * @return string
	 */
	public function title()
	{
		return $this->title;
	}

	/**
	 * @see $title
	 * @param string $title
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	}

	/**
	 * Return the object attribute::description
	 * @return string
	 */
	public function description()
	{
		return $this->description;
	}

	/**
	 * @see $description
	 * @param string $description
	 * @return type
	 */
	public function setDescription($description)
	{
		$this->description = $description;
	}

}
