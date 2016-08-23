<?php

$pathToLaravel = __DIR__ . '/../../zbase/biz';
$string = file_get_contents('php://input');
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
				$code = trim(str_replace('/start', null, $text));
				$file = $pathToLaravel . '/storage/tg/' . $code;
				if(!file_exists($file))
				{
					file_put_contents($pathToLaravel . '/storage/tg/' . $code, $chatId);
				}
			}
		}
	}
}