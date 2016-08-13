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
	 * Return all users of the same role
	 * @return Collection
	 */
	public function users()
	{
		return $this->belongsToMany('\Zbase\Entity\Laravel\User\User', 'users_roles')->get();
	}

	/**
	 * Return all roles above the Current Role
	 *
	 * @return Collection
	 */
	public function above()
	{
		return zbase_cache(zbase_cache_key(zbase_entity($this->entityName), 'above_' . $this->parent_id . '_' . $this->id()), function(){
			return $this->where('parent_id', '>', $this->parent_id)->orderBy('parent_id')->get();
		}, [$this->entityName], (60 * 24), ['forceCache' => true, 'driver' => 'file']);
	}

	/**
	 * Return all roles below the current role
	 *
	 * @return Collection
	 */
	public function below()
	{
		return zbase_cache(zbase_cache_key(zbase_entity($this->entityName), 'below_' . $this->parent_id), function(){
			return $this->where('parent_id', '<', $this->parent_id)->where($this->getKeyName(), '!=', $this->id())->orderBy('parent_id')->get();
		}, [$this->entityName], (60 * 24), ['forceCache' => true, 'driver' => 'file']);
	}

	/**
	 * Return all same level Roles
	 *
	 * @return Collection
	 */
	public function same()
	{
		return zbase_cache(zbase_cache_key(zbase_entity($this->entityName), 'same_' . $this->parent_id), function(){
			return $this->where('parent_id', '=', $this->parent_id)->where($this->getKeyName(), '!=', $this->id())->orderBy('parent_id')->get();
		}, [$this->entityName], (60 * 24), ['forceCache' => true, 'driver' => 'file']);
	}

	/**
	 * Return a Role by Rolename
	 * @param type $name
	 * @return type
	 */
	public function getRoleByName($name)
	{
		return zbase_cache(zbase_cache_key(zbase_entity($this->entityName), 'getRoleByName_' . $name), function() use ($name){
			return $this->repo()->by('role_name', $name)->first();
		}, [$this->entityName], (60 * 24), ['forceCache' => true, 'driver' => 'file']);
	}

	// <editor-fold defaultstate="collapsed" desc="ListAllRoles">
	/**
	 * Return all Roles
	 *
	 * @return array|null
	 */
	public static function listAllRoles()
	{
		return zbase_cache(zbase_cache_key(zbase_entity('user_roles'), 'listAllRoles'), function(){
			$roles = zbase_entity('user_roles')->all();
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
		}, [zbase_entity('user_roles')->getTable()], (60 * 24), ['forceCache' => true, 'driver' => 'file']);
	}

	// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc="Table Definitions">
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
				'parent_id' => 1,
				'role_name' => 'manager'
			],
			[
				'parent_id' => 1,
				'role_name' => 'editor'
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

	// </editor-fold>
}
