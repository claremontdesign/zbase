<?php

namespace Zbase\Http\Controllers\Laravel;

/**
 * ApiController
 *
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file PageModuleController.php
 * @project Zbase
 * @package Zbase\Http\Controllers
 */
use Zbase\Http\Controllers\Laravel\Controller;
use Zbase\Interfaces;
use Zbase\Traits;

class ApiController extends Controller implements Interfaces\AttributeInterface
{

	use Traits\Attribute, Traits\Api;

	/**
	 * Telegram Hook
	 * http://dermasecrets-local.biz/telegram/237861793:AAFW6v2ZRg5oPHiQWuedtXuqs2GAguCSZU8
	 */
	public function telegramHook()
	{
		$code = zbase_request_query_input('start', false);
		$string = file_get_contents('php://input');
		$data = $code . "\n" . $string;
		file_put_contents(zbase_storage_path() . '_tg', $data);
//		if(!empty($code))
//		{
//			$code = \DB::table('user_tokens')->where(['token' => $code, 'taggable_type' => 'telegram'])->first();
//			if(!empty($code))
//			{
//				//$user = zbase_user_byid($code->user_id);
//				//$user->telegram_chat_id = '';
//			}
//		}
	}

	public function index()
	{
		return $this->apiIndex();
	}
}
