<?php

date_default_timezone_set(zbase_date_default_timezone());
/**
 * Dx
 *
 * @link http://dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2015 ClaremontDesign/MadLabs-Dx
 * @version 0.0.0.1
 * @since Feb 14, 2016 12:22:24 PM
 * @file date.php
 *
 * http://carbon.nesbot.com/docs/
 */
define('DATE_FORMAT_DB', 'Y-m-d H:i:s');

/**
 * Return the default timezone
 * @return string
 */
function zbase_date_default_timezone()
{
	return env('ZBASE_TIMEZONE', 'UTC');
}

/**
 * Return a Caron Date
 * @return \Carbon
 */
function zbase_date_now()
{
	return \Carbon\Carbon::now(new \DateTimeZone(zbase_date_default_timezone()));
}

/**
 * Convert instance of \Datetime to \Carbon\Carbon
 * @param \Datetime $date
 * @return \Carbon\Carbon
 */
function zbase_date_instance(\Datetime $date)
{
	return \Carbon\Carbon::instance($date);
}

/**
 * $second date should be before the $first date
 *
 * @param \Datetime|\Carbon\Carbon $first
 * @param \Datetime|\Carbon\Carbon $second
 * @return boolean
 */
function zbase_date_before($first, $second)
{
	if($first instanceof \DateTime)
	{
		$first = zbase_date_instance($first);
	}
	if($second instanceof \DateTime)
	{
		$second = zbase_date_instance($second);
	}
	if($first instanceof \Carbon\Carbon && $second instanceof \Carbon\Carbon)
	{
		return $first->gt($second);
	}
	return false;
}

/**
 * Create a Date from Format
 * @param type $format
 * @param type $time
 * @param type $tz
 * @return type
 */
function zbase_date_from_format($format, $time, $tz = null)
{
	return \Carbon\Carbon::createFromFormat($format, $time, $tz);
}

/**
 * Format Date from dB
 * @param string $time
 * @return \Datetime
 */
function zbase_date_from_db($time)
{
	return zbase_date_from_format(DATE_FORMAT_DB, $time);
}

/**
 * Human Readable DAte
 * Difference for Humans
 * @return string
 */
function zbase_date_human($time, $format = DATE_FORMAT_DB)
{
	return zbase_date_from_format($format, $time)->diffForHumans();
}