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
 *
 * @param type $senderIndex The Sender Index
 * @return array
 */
function zbase_messenger_sender($senderIndex)
{
	if(!preg_match('/@/', $senderIndex))
	{
		$toEmail = zbase_config_get('email.' . $senderIndex . '.email');
		$toName = zbase_config_get('email.' . $senderIndex . '.name');
		return [
			$toEmail,
			$toName
		];
	}
	return false;
}

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
function zbase_messenger_email($to, $from, $subject, $view, $data, $options = [], $sentDev = true)
{
	if(zbase_is_dev() || zbase_is_xio())
	{
		if(!empty($sentDev))
		{
			return zbase_messenger_email('dennes.b.abing@gmail.com', $from, $subject, $view, $data, $options, false);
		}
	}
	if(!zbase_config_get('email.enable', false))
	{
		return;
	}
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

//	if(!zbase_is_dev())
//	{
		$logMsg = [];
		$message = zbase_view_render($view, $data)->render();
		dd($message);
		$logMsg[] = $subject;
		$logMsg[] = 'From: ' . $fromName . ' ' . $fromEmail;
		$logMsg[] = 'To: ' . $to;
		$headers = "From: " . $fromName . " <$fromEmail>\r\n";
		$headers .= "Reply-To: " . $fromName . " <$fromEmail>\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		$sent = mail($to, $subject, $message, $headers);
		zbase_log($logMsg, 1, __FUNCTION__ . '.txt');
		if(zbase_is_xio())
		{
			zbase()->json()->setVariable(__FUNCTION__, $sent);
		}
		return $sent;
//	}

//
//	$to = 'dennes.b.abing@gmail.com';
//	return Mail::send($view, $data, function ($msg) use ($to, $from, $subject) {
//				if(!is_array($to))
//				{
//					$toEmail = $to;
//					$toName = $to;
//					if(!preg_match('/@/', $to))
//					{
//						$toEmail = zbase_config_get('email.' . $to . '.email');
//						$toName = zbase_config_get('email.' . $to . '.name');
//					}
//				}
//				if(!is_array($from))
//				{
//					$fromEmail = $from;
//					$fromName = $from;
//					if(!preg_match('/@/', $from))
//					{
//						$fromEmail = zbase_config_get('email.' . $from . '.email');
//						$fromName = zbase_config_get('email.' . $from . '.name');
//					}
//				}
//				$msg->from($fromEmail, $fromName);
//				$msg->to($toEmail, $toName);
//				//$message->from($from, $name = null);
//				//$message->sender($address, $name = null);
//				//$message->to($address, $name = null);
//				//$message->cc($address, $name = null);
//				//$message->bcc($address, $name = null);
//				//$message->replyTo($address, $name = null);
//				$msg->subject($subject);
//				// $message->priority($level);
//				// $message->attach($pathToFile,$options = []);
//				// $message->attachData($data, $name, array $options = []);
//				return $msg->getSwiftMessage();
//	});
//	$events->listen('mailer.sending', function ($message) {
//
//	});
}
