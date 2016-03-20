<?php

/**
 * Zbase-Laravel Helpers-Messenger
 *
 * Functions and Helpers for sending emails/messages
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file messenger.php
 * @project Zbase
 * @package Zbase/Laravel/Helpers
 *
 */

/**
 * Send an email
 * @param string|array $to The recipient email address or if array, [email => name]
 * @param string|array $from The sender email address or if array [email => name] | account-noreply|robot-noreply|admin|admin-noreply
 * @param string $subject The email subject string
 * @param string $view The view file to use
 * @param string $data The data to pass to the view file
 * @param array $options some options
 * @return boolean|string|SwiftMailer message instance
 */
function zbase_messenger_email($to, $from, $subject, $view, $data, $options = [])
{
	Mail::send($view, $data, function ($message) use ($to, $from, $subject) {
		if(!is_array($to))
		{
			$toEmail = $to;
			$toName = $to;
			if(!preg_match('/@/', $to))
			{
				$toEmail = zbase_config_get('email.' . $to . '.email');
				$toName = zbase_config_get('email.' . $to . '.name');
			}
		}
		if(!is_array($from))
		{
			$fromEmail = $from;
			$fromName = $from;
			if(!preg_match('/@/', $from))
			{
				$fromEmail = zbase_config_get('email.' . $from . '.email');
				$fromName = zbase_config_get('email.' . $from . '.name');
			}
		}
		$message->from($fromEmail, $fromName);
		$message->to($toEmail, $toName);
		//$message->from($from, $name = null);
		//$message->sender($address, $name = null);
		//$message->to($address, $name = null);
		//$message->cc($address, $name = null);
		//$message->bcc($address, $name = null);
		//$message->replyTo($address, $name = null);
		$message->subject($subject);
		// $message->priority($level);
		// $message->attach($pathToFile,$options = []);
		// $message->attachData($data, $name, array $options = []);
		return $message->getSwiftMessage();
	});

//	$events->listen('mailer.sending', function ($message) {
//
//	});
}
