<?php

namespace Zbase\Entity\Laravel\User;

/**
 * Zbase-UserProfile Entity
 *
 * UserProfile Entity Model
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file UserProfile.php
 * @project Zbase
 * @package Zbase/Entity/User
 */
use Zbase\Entity\Laravel\Entity as BaseEntity;
use Zbase\Interfaces;

class Role extends BaseEntity implements Interfaces\IdInterface
{

	/**
	 * Entity name as described in the config
	 * @var string
	 */
	protected $entityName = 'user_roles';

	/**
	 * Role PrimaryKey Id
	 * @return integer
	 */
	public function id()
	{
		return $this->role_id;
	}

	/**
	 * The Role Name
	 * @return string
	 */
	public function name()
	{
		return $this->role_name;
	}

	/**
	 * Title string
	 * @return string
	 */
	public function title()
	{
		return ucfirst($this->name());
	}

	/**
	 * Description
	 * @return string
	 */
	public function description()
	{
		return $this->id() . ': ' . $this->name();
	}

	/**
	 * Return all parent roles
	 *
	 * @return Collection
	 */
	public function parents()
	{
		return $this->where('parent_id', '<', $this->parent()->first()->id())->orderBy('parent_id')->get();
	}

	/**
	 * Return all child roles
	 *
	 * @return Collection
	 */
	public function children()
	{
		return $this->where('parent_id', '>', $this->parent()->first()->id())->orderBy('parent_id')->get();
	}

}
