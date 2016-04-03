<?php

namespace Zbase\Entity\Laravel\Message;

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
use Zbase\Widgets\EntityInterface as WidgetEntityInterface;

class Message extends BaseEntity implements WidgetEntityInterface
{

	/**
	 * Entity name as described in the config
	 * @var string
	 */
	protected $entityName = 'messages';

	/**
	 * The Action Messages
	 * @var array
	 */
	protected $_actionMessages = [];

	protected static function boot()
	{
		parent::boot();
		static::saved(function($node) {
			$node->_updateAlphaId();
		});
	}

	public function id()
	{
		return $this->message_id;
	}

	public function subject()
	{
		return $this->subject;
	}

	public function message()
	{
		return $this->content;
	}

	public function excerpt()
	{
		return substr(0, 100) . '...';
	}

	public function getTimeSent()
	{
		return $this->created_at;
	}

	public function alphaId()
	{
		return $this->alpha_id;
	}

	public function sender()
	{
		return zbase_user_byid($this->sender_id);
	}

	/**
	 * Return the Sender
	 * @return String
	 */
	public function senderName()
	{
		if(property_exists($this, 'sender_first_name'))
		{
			return $this->sender_first_name . ' ' . $this->sender_last_name;
		}
		return $this->sender()->displayName();
	}

	/**
	 * Sender Avatar
	 * @return string
	 */
	public function senderAvatarUrl()
	{
		if(property_exists($this, 'sender_avatar'))
		{
			return $this->sender_avatar;
		}
		return $this->sender()->profile()->avatar;
	}

	public function readUrl()
	{
		return zbase_url_from_route('messages', ['action' => 'read', 'id' => $this->alphaId()]);
	}

	/**
	 * Read Status
	 * @return boolean
	 */
	public function readStatus()
	{
		return (bool) $this->read_status;
	}

	/**
	 * Reply Status
	 * @return boolean
	 */
	public function replyStatus()
	{
		return (bool) $this->reply_status;
	}

	/**
	 * Starred
	 * @return boolean
	 */
	public function isStarred()
	{
		return (bool) $this->is_starred;
	}

	/**
	 * Is Important
	 * @return boolean
	 */
	public function isImportant()
	{
		return (bool) $this->is_important;
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
		if($action == 'read')
		{
			if(empty($this->read_status))
			{
				$this->read_status = 1;
				$this->save();
			}
		}
		if(strtolower($method) == 'post')
		{
			if(!empty($data['msg']))
			{
				$oMessage = $this->repository()->byAlphaId($data['msg']);
			}
			if(!empty($oMessage))
			{
				/**
				 * Action is read, but posting, means that it is a reply
				 */
				if($action == 'read' || $action == 'reply')
				{
					$message = $data['content'];
					$subject = 'RE: ' . $oMessage->subject();
					$sender = zbase_auth_user()->id();
					$recipient = $oMessage->user_id;
					if(!empty($oMessage->node_id) && !empty($oMessage->node_prefix))
					{
						$options['node_id'] = $oMessage->node_id;
						$options['node_prefix'] = $oMessage->node_prefix;
						$options['parent_id'] = $oMessage->id();
					}
					$messageObject = zbase_entity($this->entityName, [], true);
					$newMessage = $messageObject->newMessage($message, $subject, $sender, $recipient, $options);
					$this->reply_status = 1;
					$this->save();
					$this->_actionMessages[$action]['success'][] = _zt('Message sent.', ['%title%' => $newMessage->subject()]);
					return true;
				}
			}
			if($action == 'trash')
			{
				$this->trash_status = 1;
				$this->save();
				$this->_actionMessages[$action]['success'][] = _zt('Message trashed.', ['%title%' => $this->subject()]);
				return true;
			}
			$this->_actionMessages[$action]['error'][] = _zt('Message reference not found. Kindly check.', ['%title%' => $this->title, '%id%' => $this->id()]);
			return false;
		}
		return true;
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

	// <editor-fold defaultstate="collapsed" desc="DataTable Widget Query Interface/Methods">
	/**
	 * Sorting Query
	 * @param array $sorting Array of Sorting
	 * @param array $filters Array of Filters
	 * @param array $options some options
	 * @return array
	 */
	public function querySorting($sorting, $filters = [], $options = [])
	{
		$sort = ['messages.message_id' => 'DESC'];
		return $sort;
	}

	/**
	 * Join Query
	 * @param array $filters Array of Filters
	 * @param array $sorting Array of Sorting
	 * @param array $options some options
	 * @return array
	 */
	public function queryJoins($filters, $sorting = [], $options = [])
	{
		$joins = [];
		$joins[] = [
			'type' => 'join',
			'model' => 'users as sender',
			'foreign_key' => 'messages.sender_id',
			'local_key' => 'sender.user_id',
		];
		$joins[] = [
			'type' => 'join',
			'model' => 'users_profile as sender_profile',
			'foreign_key' => 'messages.sender_id',
			'local_key' => 'sender_profile.user_id',
		];
		return $joins;
	}

	/**
	 * REturn selects
	 * @param array $filters
	 * @return array
	 */
	public function querySelects($filters)
	{
		$selects = ['messages.user_id'];
		$selects[] = 'messages.sender_id';
		$selects[] = 'messages.alpha_id';
		$selects[] = 'messages.subject';
		$selects[] = 'messages.read_status';
		$selects[] = 'messages.trash_status';
		$selects[] = 'messages.reply_status';
		$selects[] = 'messages.is_starred';
		$selects[] = 'messages.is_important';
		$selects[] = 'messages.is_draft';
		$selects[] = 'messages.created_at';
		$selects[] = 'messages.updated_at';
		$selects[] = 'sender_profile.first_name as sender_first_name';
		$selects[] = 'sender_profile.last_name as sender_last_name';
		$selects[] = 'sender_profile.avatar as sender_avatar';
		return $selects;
	}

	/**
	 * Filter Query
	 * @param array $filters Array of Filters
	 * @param array $sorting Array of Sorting
	 * @param array $options some options
	 * @return array
	 */
	public function queryFilters($filters, $sorting = [], $options = [])
	{
		$queryFilters = [];
		if(!empty($filters))
		{
			$isPublic = !empty($filters['public']) ? true : false;
			if(!empty($isPublic))
			{
				$queryFilters['status'] = [
					'eq' => [
						'field' => 'messages.status',
						'value' => 2
					]
				];
			}
			$currentUser = !empty($filters['currentUser']) ? true : false;
			if(!empty($currentUser))
			{
				$queryFilters['user'] = [
					'eq' => [
						'field' => 'messages.user_id',
						'value' => zbase_auth_user()->id()
					]
				];
			}
		}
		return $queryFilters;
	}

	// </editor-fold>

	/**
	 * Create new Message
	 * @param string $message The MEssage
	 * @param string $subject Subject
	 * @param string $sender UserId or User objcet
	 * @param string $recipient UserId or User objeect
	 * @param array $options
	 * @return \Zbase\Entity\Laravel\Message\Message
	 */
	public function newMessage($message, $subject, $sender, $recipient, $options)
	{
		try
		{
			$this->subject = $subject;
			$this->content = $message;
			$this->read_status = 0;
			$this->trash_status = 0;
			$this->reply_status = 0;
			if(!empty($options['parent_id']))
			{
				$this->parent_id = $options['parent_id'];
			}
			if(!empty($options['node_id']))
			{
				$this->node_id = $options['node_id'];
			}
			if(!empty($options['node_prefix']))
			{
				$this->node_prefix = $options['node_prefix'];
			}
			if(!$sender instanceof \Zbase\Entity\Laravel\User\User && is_numeric($sender))
			{
				$sender = zbase_user_byid($sender);
			}
			if($sender instanceof \Zbase\Entity\Laravel\User\User)
			{
				$this->sender_id = $sender->id();
			}
			if(!$recipient instanceof \Zbase\Entity\Laravel\User\User && is_numeric($recipient))
			{
				$recipient = zbase_user_byid($recipient);
			}
			if($recipient instanceof \Zbase\Entity\Laravel\User\User)
			{
				$this->user_id = $recipient->id();
			}
			$this->status = 2;
			$this->save();
			return $this;
		} catch (\Zbase\Exceptions\RuntimeException $e)
		{
			if(zbase_is_dev())
			{
				dd($e);
			}
			zbase_abort(503);
		}
	}

	/**
	 * Generate and Update Row Alpha ID
	 * @return void
	 */
	protected function _updateAlphaId()
	{
		if(empty($this->alpha_id) && !empty($this->message_id) && !empty($this->alphable))
		{
			$alphaId = zbase_generate_hash([$this->message_id, time()], $this->entityName);
			$i = 1;
			while (!empty($this->fetchByAlphaId($alphaId)))
			{
				$alphaId = zbase_generate_hash([time(), $i++, $this->message_id], $this->entityName);
			}
			$this->alpha_id = $alphaId;
			$this->save();
		}
	}

	/**
	 * Fetch a Row By AlphaId
	 * @param string $alphaId
	 * @return Collection[]
	 */
	public function fetchByAlphaId($alphaId)
	{
		return $this->repository()->byAlphaId($alphaId);
	}

	// <editor-fold defaultstate="collapsed" desc="Entity Configuration">

	/**
	 * Table Relations
	 * @param array $relations Configuration default data
	 * @return array
	 */
	public static function tableRelations($relations = [])
	{
		$relations = [
			'user' => [
				'entity' => 'user',
				'type' => 'onetomany',
				'class' => [
					'method' => 'user'
				],
				'keys' => [
					'local' => 'user_id',
					'foreign' => 'user_id'
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
			'user_id' => [
				'length' => 16,
				'hidden' => false,
				'fillable' => true,
				'type' => 'integer',
				'index' => true,
				'unsigned' => true,
				'foreign' => [
					'table' => 'users',
					'column' => 'user_id',
					'onDelete' => 'cascade'
				],
				'comment' => 'Owner ID'
			],
			'sender_id' => [
				'length' => 16,
				'hidden' => false,
				'fillable' => true,
				'type' => 'integer',
				'index' => true,
				'unsigned' => true,
				'comment' => 'Sender ID'
			],
			'node_id' => [
				'length' => 16,
				'hidden' => false,
				'fillable' => true,
				'type' => 'integer',
				'nullable' => true,
				'unsigned' => true,
				'comment' => 'Node ID'
			],
			'node_prefix' => [
				'length' => 255,
				'hidden' => false,
				'fillable' => true,
				'type' => 'string',
				'nullable' => true,
				'index' => true,
				'comment' => 'Node Prefix'
			],
			'subject' => [
				'filterable' => [
					'name' => 'subject',
					'enable' => true
				],
				'sortable' => [
					'name' => 'subject',
					'enable' => true
				],
				'length' => 255,
				'nullable' => true,
				'hidden' => false,
				'fillable' => true,
				'type' => 'string',
				'comment' => 'Subject'
			],
			'content' => [
				'nullable' => true,
				'hidden' => false,
				'fillable' => true,
				'type' => 'text',
				'comment' => 'Content'
			],
			'status' => [
				'filterable' => [
					'name' => 'status',
					'enable' => true
				],
				'sortable' => [
					'name' => 'status',
					'enable' => true
				],
				'hidden' => false,
				'default' => 2,
				'fillable' => false,
				'nullable' => true,
				'unsigned' => true,
				'type' => 'boolean',
				'index' => true,
				'comment' => 'Is message viewable to the user?'
			],
			'read_status' => [
				'filterable' => [
					'name' => 'read',
					'enable' => true
				],
				'sortable' => [
					'name' => 'read',
					'enable' => true
				],
				'hidden' => false,
				'fillable' => false,
				'nullable' => true,
				'unsigned' => true,
				'default' => 0,
				'type' => 'boolean',
				'index' => true,
				'comment' => 'Read Status'
			],
			'trash_status' => [
				'filterable' => [
					'name' => 'trash',
					'enable' => true
				],
				'sortable' => [
					'name' => 'trash',
					'enable' => true
				],
				'hidden' => false,
				'fillable' => false,
				'nullable' => true,
				'unsigned' => true,
				'type' => 'boolean',
				'default' => 0,
				'index' => true,
				'comment' => 'Trash Status'
			],
			'reply_status' => [
				'filterable' => [
					'name' => 'reply',
					'enable' => true
				],
				'sortable' => [
					'name' => 'reply',
					'enable' => true
				],
				'hidden' => false,
				'fillable' => false,
				'default' => 0,
				'nullable' => true,
				'unsigned' => true,
				'type' => 'boolean',
				'index' => true,
				'comment' => 'Reply Status'
			],
			'is_starred' => [
				'filterable' => [
					'name' => 'star',
					'enable' => true
				],
				'sortable' => [
					'name' => 'star',
					'enable' => true
				],
				'hidden' => false,
				'fillable' => false,
				'nullable' => true,
				'unsigned' => true,
				'type' => 'boolean',
				'default' => 0,
				'index' => true,
				'comment' => 'Starred'
			],
			'is_important' => [
				'filterable' => [
					'name' => 'important',
					'enable' => true
				],
				'sortable' => [
					'name' => 'important',
					'enable' => true
				],
				'hidden' => false,
				'fillable' => false,
				'nullable' => true,
				'unsigned' => true,
				'type' => 'boolean',
				'default' => 0,
				'index' => true,
				'comment' => 'Important'
			],
			'is_draft' => [
				'filterable' => [
					'name' => 'draft',
					'enable' => true
				],
				'sortable' => [
					'name' => 'draft',
					'enable' => true
				],
				'hidden' => false,
				'fillable' => false,
				'nullable' => true,
				'default' => 0,
				'unsigned' => true,
				'type' => 'boolean',
				'index' => true,
				'comment' => 'Draft'
			],
			'parent_id' => [
				'filterable' => [
					'name' => 'parentid',
					'enable' => true
				],
				'hidden' => false,
				'fillable' => false,
				'nullable' => true,
				'unsigned' => true,
				'type' => 'integer',
				'index' => true,
				'comment' => 'Parent ID'
			],
		];
		return $columns;
	}

	/**
	 * Table Entity Configuration
	 * @param array $entity Configuration default data
	 * @return array
	 */
	public static function entityConfiguration($entity = [])
	{
		$entity['table'] = [
			'name' => 'messages',
			'primaryKey' => 'message_id',
			'timestamp' => true,
			'alphaId' => true,
			'optionable' => true,
			'description' => 'Messaging',
		];
		return $entity;
	}

	// </editor-fold>
}
