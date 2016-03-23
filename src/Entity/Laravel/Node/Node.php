<?php

namespace Zbase\Entity\Laravel\Node;

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
use Zbase\Entity\Laravel\Entity as BaseEntity;
use Zbase\Widgets\EntityInterface as WidgetEntityInterface;

class Node extends BaseEntity implements WidgetEntityInterface
{

	use \Zbase\Entity\Laravel\Node\Traits\Node;

	/**
	 * The Entity Name
	 * @var string
	 */
	protected $entityName = 'node';

	/**
	 * The Node Name Prefix
	 * @var string
	 */
	public static $nodeNamePrefix = 'node';

	/**
	 * Route name
	 * @var type
	 */
	protected $routeName = 'node';

	protected static function boot()
	{
		parent::boot();
		static::saved(function($node) {
			$node->_updateAlphaId();
		});
	}

	/**
	 * Node Alpha URL
	 * @return string
	 */
	public function alphaUrl()
	{
		return zbase_url_from_route($this->routeName, ['action' => 'view', 'id' => $this->alphaId()]);
	}

	public function title()
	{
		if(!empty($this->title))
		{
			return $this->title;
		}
		return;
	}

	/**
	 * Widget entity interface.
	 * 	Data should be validated first before passing it here
	 * @param string $method post|get
	 * @param string $action the controller action
	 * @param array $data validated; assoc array
	 * @param Zbase\Widgets\Widget $widget
	 * @return boolean
	 */
	public function widgetController($method, $action, $data, \Zbase\Widgets\Widget $widget)
	{
		return $this->nodeWidgetController($method, $action, $data, $widget);
	}

	/**
	 * Set the Node Categories
	 * @param array $data array of data with index:category that is array of Category IDs
	 * @return void
	 */
	public function setNodeCategories($data)
	{
		if(!empty($data['category']))
		{
			if(is_array($data['category']))
			{
				$this->categories()->detach();
				foreach ($data['category'] as $categoryId)
				{
					$category = zbase_entity(static::$nodeNamePrefix . '_category')->repository()->byId($categoryId);
					if($category instanceof Nested)
					{
						$this->categories()->attach($category);
					}
				}
			}
		}
	}

	// <editor-fold defaultstate="collapsed" desc="Messages">
	/**
	 * Add a Message
	 * @param string $message
	 * @param int|User $sender
	 * @param int|User $recipient
	 * @param array $options
	 * @return Message
	 */
	public function addMessage($message, $subject, $sender, $recipient, $options)
	{
		try
		{
			$messageObject = zbase_entity(static::$nodeNamePrefix . '_messages');
			$messageObject->title = $subject;
			$messageObject->content = $message;
			$messageObject->read_status = 0;
			if(!$sender instanceof \Zbase\Entity\Laravel\User\User)
			{
				$sender = zbase_user_byid($sender);
			}
			if($sender instanceof \Zbase\Entity\Laravel\User\User)
			{
				$messageObject->sender_id = $sender->id();
			}
			if(!$recipient instanceof \Zbase\Entity\Laravel\User\User)
			{
				$recipient = zbase_user_byid($recipient);
			}
			if($recipient instanceof \Zbase\Entity\Laravel\User\User)
			{
				$messageObject->recipient_id = $recipient->id();
			}
			$messageObject->save();
			$messageObject->alpha_id = zbase_generate_hash($messageObject->message_id, static::$nodeNamePrefix . '_messages');
			$this->messages()->save($messageObject);
			return $messageObject;
		} catch (\Zbase\Exceptions\RuntimeException $e)
		{
			if(zbase_is_dev())
			{
				dd($e);
			}
			zbase_abort(500);
		}
	}

	// </editor-fold>

	/**
	 * Upload a file for this node
	 * @param string $index The Upload file name/index or the URL to file to download and save
	 * @return void
	 */
	public function uploadNodeFile($index = 'file')
	{
		try
		{
			$folder = zbase_storage_path() . '/' . zbase_tag() . '/' . static::$nodeNamePrefix . '/' . $this->id() . '/';
			zbase_directory_check($folder, true);
			$nodeFileObject = zbase_entity(static::$nodeNamePrefix . '_files', [], true);
			if(preg_match('/http\:/', $index))
			{
				// File given is a URL
				$filename = zbase_file_name_from_file(basename($index), time(), true);
				$uploadedFile = zbase_file_download_from_url($index, $folder . $filename);
			}
			if(!empty($_FILES[$index]['name']))
			{
				$filename = zbase_file_name_from_file($_FILES[$index]['name'], time(), true);
				$uploadedFile = zbase_file_upload_image($index, $folder, $filename, 'png');
			}
			if(!empty($uploadedFile) && zbase_file_exists($uploadedFile))
			{
				$nodeFiles = $this->files()->get();
				$nodeFileObject->is_primary = empty($nodeFiles) ? 1 : 0;
				$nodeFileObject->status = 2;
				$nodeFileObject->mimetype = zbase_file_mime_type($uploadedFile);
				$nodeFileObject->size = zbase_file_size($uploadedFile);
				$nodeFileObject->filename = basename($uploadedFile);
				$nodeFileObject->save();
				$nodeFileObject->alpha_id = zbase_generate_hash($nodeFileObject->file_id, static::$nodeNamePrefix . '_files');
				$this->files()->save($nodeFileObject);
			}
		} catch (\Zbase\Exceptions\RuntimeException $e)
		{
			if(zbase_is_dev())
			{
				dd($e);
			}
			zbase_abort(500);
		}
	}

	/**
	 * Save a new model and return the instance.
	 *
	 * @param  array  $attributes
	 * @return static
	 */
	public static function create(array $attributes = [])
	{
		$model = zbase_entity(static::$nodeNamePrefix);
		$model->createNode($attributes);
		$model->save();
		return $model;
	}

	/**
	 * Create Node from Array
	 * @param array $data
	 * @return \Zbase\Entity\Laravel\Node\Node
	 */
	public function createNode($data)
	{
		if(!empty($data['user']))
		{
			if($data['user'] instanceof \Zbase\Entity\Laravel\User\User)
			{
				$user = $data['user'];
			}
			if(is_int((int) $data['user']))
			{
				$user = zbase_user_byid($data['user']);
			}
			if($user instanceof \Zbase\Entity\Laravel\User\User)
			{
				$user->equipments()->save($this);
			}
			unset($data['user']);
		}
		else
		{
			if(zbase_auth_has())
			{
				zbase_auth_user()->equipments()->save($this);
			}
		}
		$this->nodeAttributes($data);
		$this->status = 2;
		$this->save();
		$this->setNodeCategories($data);
		if(!empty($data['file_url']))
		{
			$this->uploadNodeFile($data['file_url']);
		}
		elseif(!empty($data['files_url']))
		{
			foreach ($data['files_url'] as $fUrl)
			{
				$this->uploadNodeFile($fUrl);
			}
		}
		else
		{
			$this->uploadNodeFile();
		}
		return $this;
	}

	/**
	 * Update this object
	 * @param type $data
	 * @return \Zbase\Entity\Laravel\Node\Node
	 */
	public function updateNode($data)
	{
		$this->nodeAttributes($data);
		$this->save();
		$this->setNodeCategories($data);
		if(!empty($data['file_url']))
		{
			$this->uploadNodeFile($data['file_url']);
		}
		else if(!empty($data['files_url']))
		{
			foreach ($data['files_url'] as $fUrl)
			{
				$this->uploadNodeFile($fUrl);
			}
		}
		else
		{
			$this->uploadNodeFile();
		}
	}

	/**
	 * Widget entity interface.
	 * 	Data should be validated first before passing it here
	 * @param string $method post|get
	 * @param string $action the controller action
	 * @param array $data validated; assoc array
	 * @param Zbase\Widgets\Widget $widget
	 * @return boolean
	 */
	public function nodeWidgetController($method, $action, $data, \Zbase\Widgets\Widget $widget)
	{
		zbase_db_transaction_start();
		try
		{
			if($action == 'create' && strtolower($method) == 'post')
			{
				$this->createNode($data);
				$this->log($action);
				zbase_db_transaction_commit();
				zbase_cache_flush([$this->getTable()]);
				$this->_actionMessages[$action]['success'][] = _zt('Created "%title%"!', ['%title%' => $this->title, '%id%' => $this->id()]);
				return true;
			}
			if($action == 'update' && strtolower($method) == 'post')
			{
				$this->updateNode($data);
				$this->log($action);
				zbase_db_transaction_commit();
				zbase_cache_flush([$this->getTable()]);
				$this->_actionMessages[$action]['success'][] = _zt('Saved "%title%"!', ['%title%' => $this->title, '%id%' => $this->id()]);
				return true;
			}
		} catch (\Zbase\Exceptions\RuntimeException $e)
		{
			zbase_db_transaction_rollback();
		}

		if($action == 'index')
		{

			return;
		}
		if($action == 'update')
		{
			if($this->hasSoftDelete() && $this->trashed())
			{
				$undoText = '<a href="' . $widget->getModule()->url(zbase_section(), ['action' => 'restore', 'id' => $this->id()]) . '" title="Restore" class="undodelete">Restore</a>';
				$undoText .= ' | <a href="' . $widget->getModule()->url(zbase_section(), ['action' => 'ddelete', 'id' => $this->id()]) . '" title="Delete Forever " class="ddeleteforever">Delete Forever</a>';
				$this->_actionMessages[$action]['warning'][] = _zt('Row "%title%" was trashed! %undo%', ['%title%' => $this->title, '%id%' => $this->id(), '%undo%' => $undoText]);
				return false;
			}
		}
		if($action == 'delete')
		{
			if($this->hasSoftDelete() && $this->trashed())
			{
				$undoText = '<a href="' . $widget->getModule()->url(zbase_section(), ['action' => 'restore', 'id' => $this->id()]) . '" title="Restore" class="undodelete">Restore</a>';
				$undoText .= ' | <a href="' . $widget->getModule()->url(zbase_section(), ['action' => 'ddelete', 'id' => $this->id()]) . '" title="Delete Forever " class="ddeleteforever">Delete Forever</a>';
				$this->_actionMessages[$action]['warning'][] = _zt('Row "%title%" was trashed! %undo%', ['%title%' => $this->title, '%id%' => $this->id(), '%undo%' => $undoText]);
				return false;
			}
		}
		try
		{
			if($action == 'delete')
			{
				if($this->hasSoftDelete())
				{
					$this->deleted_at = zbase_date_now();
					$this->save();
				}
				else
				{
					$this->delete();
				}
				$this->log($action);
				zbase_db_transaction_commit();
				zbase_cache_flush([$this->getTable()]);
				$undoText = '';
				if(!empty($this->hasSoftDelete()))
				{
					$undoText = '<a href="' . $widget->getModule()->url(zbase_section(), ['action' => 'restore', 'id' => $this->id()]) . '" title="Undo Delete" class="undodelete">Undo</a>.';
					$undoText .= ' | <a href="' . $widget->getModule()->url(zbase_section(), ['action' => 'ddelete', 'id' => $this->id()]) . '" title="Delete Forever " class="ddeleteforever">Delete Forever</a>';
				}
				$this->_actionMessages[$action]['success'][] = _zt('Deleted "%title%"! %undo%', ['%title%' => $this->title, '%id%' => $this->id(), '%undo%' => $undoText]);
				return true;
			}
			if($action == 'restore')
			{
				if($this->trashed())
				{
					$this->restore();
					$this->log($action);
					zbase_db_transaction_commit();
					zbase_cache_flush([$this->getTable()]);
					$this->_actionMessages[$action]['success'][] = _zt('Row "%title%" was restored!', ['%title%' => $this->title, '%id%' => $this->id()]);
					return true;
				}
				$this->_actionMessages[$action]['error'][] = _zt('Error restoring "%title%". Row was not trashed.!', ['%title%' => $this->title, '%id%' => $this->id()]);
				return false;
			}
			if($action == 'ddelete')
			{
				if($this->trashed())
				{
					$this->forceDelete();
					$this->log($action);
					zbase_db_transaction_commit();
					zbase_cache_flush([$this->getTable()]);
					$this->_actionMessages[$action]['success'][] = _zt('Row "%title%" was removed from database!', ['%title%' => $this->title, '%id%' => $this->id()]);
					return true;
				}
				$this->_actionMessages[$action]['error'][] = _zt('Error restoring "%title%". Row was not trashed.!', ['%title%' => $this->title, '%id%' => $this->id()]);
				return false;
			}
		} catch (\Zbase\Exceptions\RuntimeException $e)
		{
			$this->_actionMessages[$action]['error'][] = _zt('There was a problem performing the request for "%title%".', ['%title%' => $this->title, '%id%' => $this->id()]);
			zbase_db_transaction_rollback();
		}
		return false;
	}

	// <editor-fold defaultstate="collapsed" desc="SEEDING / Table Configuration">
	/**
	 * Return fake values
	 */
	public static function fakeValue()
	{
		$faker = \Faker\Factory::create();
		return [
			'title' => ucfirst($faker->words(rand(3, 10), true)),
			'content' => $faker->text(1000, true),
			'excerpt' => $faker->text(200),
			'status' => rand(0, 2),
		];
	}

	/**
	 * POST-Seeding event
	 */
	public static function seedingEventPost($entity = [])
	{

	}

	/**
	 * Seed
	 */
	public static function seeder()
	{
		for ($x = 0; $x <= 15; $x++)
		{
			$data = static::fakeValue();
			echo "\n ----- " . static::$nodeNamePrefix . ' - ' . $data['title'] . ' - ' . $x;
			$model = zbase_entity(static::$nodeNamePrefix, [], $x);
			$model->createNode($data)->save();
		}
	}

	/**
	 * Table Relations
	 * @param array $relations Configuration default data
	 * @return array
	 */
	public static function tableRelations($relations = [])
	{
		$relations = [
			'owner' => [
				'entity' => 'user',
				'type' => 'onetomany',
				'class' => [
					'method' => 'owner'
				],
				'keys' => [
					'local' => 'user_id',
					'foreign' => 'user_id'
				],
			],
			static::$nodeNamePrefix . '_category' => [
				'entity' => static::$nodeNamePrefix . '_category',
				'type' => 'manytomany',
				'class' => [
					'method' => 'categories'
				],
				'pivot' => static::$nodeNamePrefix . '_category_pivot',
				'keys' => [
					'local' => 'category_id',
					'foreign' => 'node_id'
				],
			],
			static::$nodeNamePrefix . '_files' => [
				'entity' => static::$nodeNamePrefix . '_files',
				'type' => 'onetomany',
				'class' => [
					'method' => 'files'
				],
				'keys' => [
					'local' => 'node_id',
					'foreign' => 'node_id'
				],
			],
			static::$nodeNamePrefix . '_messages' => [
				'entity' => static::$nodeNamePrefix . '_messages',
				'type' => 'onetomany',
				'class' => [
					'method' => 'messages'
				],
				'keys' => [
					'local' => 'node_id',
					'foreign' => 'node_id'
				],
			],
		];
		return $relations;
	}

	/**
	 * Table Entity Configuration
	 * @param array $entity Configuration default data
	 * @return array
	 */
	public static function entityConfiguration($entity = [])
	{
		$entity['table'] = [
			'name' => static::$nodeNamePrefix,
			'primaryKey' => 'node_id',
			'timestamp' => true,
			'softDelete' => true,
			'alphaId' => true,
			'nodeable' => true,
			'optionable' => true,
			'sluggable' => true
		];
		return $entity;
	}

	// </editor-fold>
}
