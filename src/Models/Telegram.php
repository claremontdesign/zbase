<?php

namespace Zbase\Models;

/**
 * Zbase-Model-Request
 *
 * Request Model
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Request.php
 * @project Zbase
 * @package Zbase/Model
 *
 * ALTER TABLE `zbase_users`
  ADD COLUMN `telegram_chat_id` INT(11) NULL DEFAULT NULL COMMENT 'Telegram Chat Id' AFTER `mlm_dl_left_count`;
 */
use Zbase\Models;

class Telegram
{

	protected $enabled = null;

	/**
	 *
	 */
	public function __construct()
	{
		$this->file = zbase_storage_path() . '/telegram';
	}

	/**
	 * Telegram settings
	 *
	 * @return array
	 */
	public function settings()
	{
		if(file_exists($this->file))
		{
			return json_decode(file_get_contents($this->file), true);
		}
		return [
			'status' => 0,
			'botusername' => '',
			'bottoken' => '',
		];
	}

	/**
	 * Save Settings
	 * @param array $details
	 */
	public function saveSettings($details)
	{
		file_put_contents($this->file, json_encode($details, JSON_HEX_QUOT));
		if(!empty($details['status']))
		{
			$this->webhook();
			// $this->sendZivxioMessage('81803240');
		}
	}

	/**
	 * Disable Telegram support
	 *
	 * @return void
	 */
	public function disable()
	{
		$details = $this->settings();
		$details['status'] = 0;
		file_put_contents($this->file, json_encode($details, JSON_HEX_QUOT));
	}

	/**
	 * check if Enabled
	 * @return boolean
	 */
	public function isEnabled()
	{
		if(is_null($this->enabled))
		{
			$details = $this->settings();
			$this->enabled = false;
			if(!empty($details['status']))
			{
				$this->enabled = true;
			}
		}
		return $this->enabled;
	}

	/**
	 * Send Message to UYser
	 * @param User $user
	 * @param string $message
	 */
	public function send($user, $message)
	{
		if($this->isEnabled())
		{
			if($user instanceof \Zbase\Entity\Laravel\User\User)
			{
				$chatId = $user->telegram_chat_id;
				$url = 'https://api.telegram.org/bot' . $this->token() . '/sendMessage?chat=' . $chatId . '&text=' . $message;
				$this->tg($url);
			}
		}
	}

	/**
	 * Call WebHook
	 */
	public function webhook()
	{
		$details = $this->settings();
		if(empty($details['webhook']))
		{
			$webHookUrl = zbase_url_from_route('telegramhook', ['token' => $this->token()]);
			$details['webhook'] = $webHookUrl;
			file_put_contents($this->file, json_encode($details, JSON_HEX_QUOT));
		}
		else
		{
			$webHookUrl = $details['webhook'];
		}
		$url = 'https://api.telegram.org/bot' . $this->token() . '/setWebhook?url=' . $webHookUrl;
		$this->tg($url);
	}

	/**
	 * Return the Bot Token
	 *
	 * @return string
	 */
	public function token()
	{
		$details = $this->settings();
		if(!empty($details['bottoken']))
		{
			return $details['bottoken'];
		}
		return false;
	}

	/**
	 * Return the Bot Token
	 *
	 * @return string
	 */
	public function botusername()
	{
		$details = $this->settings();
		if(!empty($details['botusername']))
		{
			return $details['botusername'];
		}
		return false;
	}

	/**
	 * Send
	 * @param string $url
	 * @return type
	 */
	public function tg($url)
	{
		if(zbase_is_dev())
		{
			zbase_alert('info', 'TG CALL: ' . $url);
		}
//		$opts = array(
//			'http' => array(
//				'method' => "GET",
//				'header' => "Content-Type: application/x-www-form-urlencoded; charset: UTF-8"
//			)
//		);
//		$context = stream_context_create($opts);
//		return file_get_contents($url, false, $context);
	}

}
