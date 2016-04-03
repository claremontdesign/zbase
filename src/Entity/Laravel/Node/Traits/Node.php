<?php

namespace Zbase\Entity\Laravel\Node\Traits;

/**
 * Zbase-Entity Zbase Entity Maker
 *
 * Filterable Entity
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Entity.php
 * @project Zbase
 * @package Zbase/Entity/Traits
 */
trait Node
{

	use \Illuminate\Database\Eloquent\SoftDeletes;

	/**
	 * The Action Messages
	 * @var array
	 */
	protected $_actionMessages = [];

	/**
	 * Return the Node ID
	 * @return string
	 */
	public function id()
	{
		return $this->node_id;
	}

	public function alphaId()
	{
		return $this->alpha_id;
	}

	/**
	 * Return a messages based on the Action made
	 * @param boolean $flag
	 * @param string $action create|update|delete|restore|ddelete
	 * @return array
	 */
	public function getActionMessages($action)
	{
		if(!empty($this->_actionMessages[$action]))
		{
			return $this->_actionMessages[$action];
		}
		return [];
	}


	/**
	 * Generate and Update Row Alpha ID
	 * @return void
	 */
	protected function _updateAlphaId()
	{
		if(!empty($this->node_id) && empty($this->alpha_id) && !empty($this->alphable))
		{
			$alphaId = zbase_generate_hash([$this->node_id, time()], $this->entityName);
			$rowByAlphaId = $this->repository()->byAlphaId($alphaId);
			if(!empty($rowByAlphaId))
			{
				$i = 1;
				while (!empty($this->repository()->byAlphaId($alphaId)))
				{
					$alphaId = zbase_generate_hash([$this->node_id, time(), $i++], $this->entityName);
				}
			}
			$this->alpha_id = $alphaId;
			$this->save();
		}
	}

	/**
	 * Set the Node Attributes
	 * @param array $data
	 */
	public function nodeAttributes($data)
	{
		if(isset($data['status']))
		{
			$this->status = $data['status'];
		}
		if(isset($data['content']))
		{
			$this->content = $data['content'];
		}
		if(isset($data['excerpt']))
		{
			$this->excerpt = $data['excerpt'];
		}
		if(!empty($data['title']))
		{
			$this->title = $data['title'];
		}
		if(!empty($data['slug']))
		{
			$this->slug = $data['slug'];
		}
	}

	/**
	 * Set the Title Attribute
	 * @param string $value
	 */
	public function setTitleAttribute($value)
	{
		$this->attributes['title'] = $value;
		$this->slug = $value;
	}

	// <editor-fold defaultstate="collapsed" desc="SLUG">
	/**
	 *
	 * @param string $value
	 */
	public function setSlugAttribute($value)
	{
		if(!empty($this->sluggable))
		{
			$slug = $this->createSlug($value);
			$rowsBySlug = $this->repository()->bySlug($slug);
			if(!empty($rowsBySlug))
			{
				$i = 1;
				while (!empty($this->repository()->bySlug($slug)))
				{
					$slug = $slug . '-' . $i++;
				}
			}
			$this->attributes['slug'] = $slug;
		}
		else
		{
			$this->attributes['slug'] = null;
		}
	}

	/**
	 * Create a Slug
	 * @param string $value
	 * @return string
	 */
	public function createSlug($value)
	{
		return zbase_string_slug($value);
	}

	// </editor-fold>

	/**
	 * Create a Log
	 * @param string $action
	 * @param array $options
	 * @return void
	 */
	public function log($action, $options = [])
	{

	}

	/**
	 * Return table minimum columns requirement
	 * @return array
	 */
	public static function nodeDefaultColumns()
	{
		$columns = [];
		$columns['user_id'] = [
			'filterable' => [
				'name' => 'userid',
				'enable' => true
			],
			'sortable' => [
				'name' => 'userid',
				'enable' => true
			],
			'hidden' => false,
			'length' => 16,
			'fillable' => true,
			'nullable' => true,
			'type' => 'integer',
			'index' => true,
			'comment' => 'Author UserId'
		];
		$columns['title'] = [
			'filterable' => [
				'name' => 'title',
				'enable' => true
			],
			'sortable' => [
				'name' => 'title',
				'enable' => true
			],
			'hidden' => false,
			'length' => 255,
			'fillable' => true,
			'nullable' => true,
			'type' => 'string',
			'index' => false,
			'comment' => 'Title'
		];
		$columns['content'] = [
			'filterable' => [
				'name' => 'content',
				'enable' => true
			],
			'hidden' => false,
			'fillable' => true,
			'nullable' => true,
			'type' => 'text',
			'comment' => 'Content'
		];
		$columns['excerpt'] = [
			'filterable' => [
				'name' => 'excerpt',
				'enable' => true
			],
			'hidden' => false,
			'fillable' => true,
			'nullable' => true,
			'type' => 'text',
			'comment' => 'Excerpt'
		];
		$columns['status'] = [
			'filterable' => [
				'name' => 'status',
				'enable' => true
			],
			'sortable' => [
				'name' => 'status',
				'enable' => true
			],
			'hidden' => false,
			'fillable' => false,
			'nullable' => true,
			'unsigned' => true,
			'type' => 'boolean',
			'index' => true,
			'comment' => 'Status'
		];
		return $columns;
	}

	/**
	 * Return table minimum columns requirement
	 * @param array $columns Some columns
	 * @param array $entity Entity Configuration
	 * @return array
	 */
	public static function tableColumns($columns = [], $entity = [])
	{
		return $columns;
	}

}
