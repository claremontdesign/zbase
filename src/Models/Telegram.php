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
use Zbase\Entity\Laravel\User\User;

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
		if(!empty($details['testmessage']))
		{
			$this->testMessage();
			unset($details['testmessage']);
		}
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
	public function send(User $user, $message)
	{
		if($this->isEnabled())
		{
			if($user instanceof User)
			{
				$chatId = $this->userTelegramId($user);
				if(!empty($chatId))
				{
					$url = 'https://api.telegram.org/bot' . $this->token() . '/sendMessage?chat_id=' . $chatId . '&text=' . $message;
					$this->tg($url);
				}
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
	 * Test Message to Dennes
	 */
	public function testMessage()
	{
		$chatId = '81803240';
		$url = 'https://api.telegram.org/bot' . $this->token() . '/sendMessage?chat_id=' . $chatId . '&text=This is a test message from ' . zbase_site_name() . ' - ' . $_SERVER['HTTP_HOST'];
		$this->tg($url);
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
		try
		{
			if(zbase_is_dev())
			{
				zbase_alert('info', 'TG CALL: ' . $url);
			}
			$opts = array(
				'http' => array(
					'method' => "GET",
					'header' => "Content-Type: application/x-www-form-urlencoded; charset: UTF-8"
				)
			);
			$context = stream_context_create($opts);
			return file_get_contents($url, false, $context);
		} catch (\Zbase\Exceptions\RuntimeException $e)
		{
			zbase_exception_throw($e);
		}
	}

	/**
	 * Return a new user Code
	 *
	 * @return string
	 */
	public function userCode($user)
	{
		$code = zbase_generate_code();
		\DB::table('user_tokens')->where(['user_id' => $user->id(), 'taggable_type' => 'telegram'])->delete();
		$token = [
			'user_id' => $user->id(),
			'token' => $code,
			'taggable_type' => 'telegram'
		];
		\DB::table('user_tokens')->insert($token);
		return $code;
	}

	/**
	 * Return a new user Code
	 *
	 * @return string
	 */
	public function checkUserCode(User $user)
	{
		$userCode = \DB::table('user_tokens')->where(['taggable_type' => 'telegram', 'user_id' => $user->id()])->first();
		if(!empty($userCode))
		{
			$codeFile = zbase_storage_path() . '/tg/' . $userCode->token;
			if(file_exists($codeFile))
			{
				$chatId = trim(file_get_contents($codeFile));
				if(!empty($chatId))
				{
					$user->setDataOption('telegram_chat_id', $chatId);
					$user->save();
					$user->clearEntityCacheById();
					$this->send($user, 'Welcome, you have successfully enabled ' . zbase_site_name() . ' notifications.');
					unlink($codeFile);
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * Check if user is connected to telegram
	 *
	 * @return boolean
	 */
	public function checkUserTelegram(User $user)
	{
		$userOptions = json_decode($user->options);
		if(!empty($userOptions->telegram_chat_id))
		{
			return true;
		}
		return false;
	}

	/**
	 * Check if user is connected to telegram
	 *
	 * @return boolean
	 */
	public function userTelegramId(User $user)
	{
		$userOptions = json_decode($user->options);
		if(!empty($userOptions->telegram_chat_id))
		{
			return $userOptions->telegram_chat_id;
		}
		return false;
	}

	/**
	 * Disable Telegram Notifications
	 *
	 * @return
	 */
	public function disableUserTelegram(User $user)
	{
		$user->unsetDataOption('telegram_chat_id');
		$user->save();
		$user->clearEntityCacheById();
		zbase_alert('success', 'Telegram notifications disabled.');
		return true;
	}

	/**
	 * Receive Message from Hook
	 * @NOTUSED
	 */
	public function receiveMessage()
	{
		// $string = '{"update_id":798236645,"message":{"message_id":4,"from":{"id":81803240,"first_name":"DenxioAbing","last_name":"(zivxio)","username":"zivxio"},"chat":{"id":81803240,"first_name":"DenxioAbing","last_name":"(zivxio)","username":"zivxio","type":"private"},"date":1471951251,"text":"\/start MzfzuUGk5Wb4WNSkpeCQahA35J3GrZ5E","entities":[{"type":"bot_command","offset":0,"length":6}]}}';
		if($string !== '')
		{
			$tg = json_decode($string);
			if(isset($tg->message))
			{
				if(isset($tg->message->text) && isset($tg->message->from->id))
				{
					/**
					 * update_id
					 * message
					 * message->message_id
					 * message->from->id
					 * message->from->first_name
					 * message->from->last_name
					 * message->from->username
					 *
					 * message->chat->id
					 * message->chat->first_name
					 * message->chat->last_name
					 * message->chat->username
					 *
					 * message->date
					 * message->text
					 * message->entities
					 * message->entities->0->type = bot_command
					 * message->entities->0->offset
					 * message->entities->0->length
					 */
					$text = $tg->message->text;
					$chatId = $tg->message->chat->id;
					if(preg_match('/\/start/', $text) > 0)
					{
						$code = trim(str_replace('/start ', null, $text));
						$this->checkUserCode($code, $chatId);
					}
				}
			}
		}
	}

}
