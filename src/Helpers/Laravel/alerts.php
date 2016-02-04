<?php

/**
 * Zbase-Laravel Helpers-Messages
 *
 * Functions and Helpers for messages, alerts and notifications
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file alerts.php
 * @project Zbase
 * @package Zbase/Laravel/Helpers
 *
 * alert types: error|info|success|warning
 */

/**
 * Add an alert
 *
 * @param string $type
 * @param string $msg
 * @param string $title
 * @param array $options
 * @return void
 */
function zbase_alert($type, $msg, $options = [])
{
	$tag = zbase_tag() . '_alert_' . $type;
	$session = zbase_session();

	if($msg instanceof \Illuminate\Support\MessageBag)
	{
		$messages = $msg->getMessages();
		$msg = [];
		if(!empty($messages))
		{
			foreach ($messages as $key => $ms)
			{
				foreach($ms as $m)
				{
					$msg[] = $m;
					/**
					 * When validating through controller
					 */
					if(!empty($options['formvalidation']))
					{
						zbase_form_message_flash($key, $m, 'error');
					}
				}
			}
		}
	}
	if(!is_array($msg))
	{
		$msg = [$msg];
	}
	if($session->has($tag))
	{
		$errs = $session->get($tag);
		foreach ($msg as $m)
		{
			if(!in_array($m, $errs))
			{
				$session->push($tag, $m);
			}
		}
		$errormsgs = $session->get($tag);
	}
	else
	{
		$errormsgs = $msg;
		$session->put($tag, $errormsgs);
	}
	$session->forget($tag);
	$session->flash($tag . '_pool', $errormsgs);
}

/**
 * Return all alerts
 *
 * @param string $type
 * @return array
 */
function zbase_alerts($type)
{
	$tag = zbase_tag() . '_alert_' . $type . '_pool';
	$session = zbase_session();
	if($session->has($tag))
	{
		$msgs = $session->get($tag);
		$session->forget($tag);
		return $msgs;
	}
	return [];
}

/**
 * Check if there is an alert for $type
 *
 * @param type $type
 * @return boolean
 */
function zbase_alerts_has($type = null)
{
	$tag = zbase_tag() . '_alert_' . $type . '_pool';
	$session = zbase_session();
	return $session->has($tag);
}
