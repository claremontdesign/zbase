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
	public function children()
	{
		return $this->where('parent_id', '<', $this->parent_id)->where($this->getKeyName(), '!=', $this->id())->orderBy('parent_id')->get();
	}

	/**
	 * Return all child roles
	 *
	 * @return Collection
	 */
	public function parents()
	{
		return $this->where('parent_id', '>', $this->parent_id)->orderBy('parent_id')->get();
	}

	/**
	 * Return all Roles
	 *
	 * @return array|null
	 */
	public static function listAllRoles()
	{
		$entity = zbase()->entity('user_roles', [], true);
		$roles = $entity->repo()->all();
		if(!empty($roles))
		{
			$lists = [];
			foreach ($roles as $role)
			{
				$lists[] = $role->name();
			}
			return $lists;
		}
		return null;
	}

	/**
	 * Default Data
	 * @param array $defaultData Configuration default data
	 * @return array
	 */
	public static function tableDefaultData($defaultData = [])
	{
		$columns = [
			[
				'parent_id' => 0,
				'role_name' => 'user'
			],
			[
				'parent_id' => 1,
				'role_name' => 'moderator'
			],
			[
				'parent_id' => 2,
				'role_name' => 'admin'
			],
			[
				'parent_id' => 3,
				'role_name' => 'sudo'
			]
		];
		return $columns;
	}

	/**
	 * Table Relations
	 * @param array $relations Configuration default data
	 * @return array
	 */
	public static function tableRelations($relations = [])
	{
		$relations = [
			'user_roles' => [
				'type' => 'manytomany',
				'pivot' => 'users_roles',
				'keys' => [
					'local' => 'user_id',
					'foreign' => 'role_id'
				],
			],
			'parent' => [
				'type' => 'onetoone',
				'entity' => 'user_roles',
				'keys' => [
					'local' => 'parent_id',
					'foreign' => 'role_id'
				],
			],
		];
		return $relations;
	}

	/**
	 * Return table minimum columns requirement
	 * @param array $columns Some columns
	 * @param array $entity Entity Configuration
	 * @return array
	 */
	public static function tableColumns($columns = [], $entity = [])
	{
		$columns = [
			'parent_id' => [
				'hidden' => false,
				'fillable' => false,
				'type' => 'integer',
				'unsigned' => true,
				'nullable' => true,
				'length' => 16,
				'comment' => 'Parent Id'
			],
			'role_name' => [
				'hidden' => false,
				'fillable' => true,
				'type' => 'string',
				'length' => 32,
				'unique' => true,
				'index' => true,
				'comment' => 'Role name'
			],
		];
		return $columns;
	}

}
