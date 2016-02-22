<?php

/**
 * Zbase-Laravel Helpers-Session
 *
 * Functions and Helpers for Accessing Session and its content
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file sessions.php
 * @project Zbase
 * @package Zbase/Laravel/Helpers
 */

/**
 * Session namespacing
 *
 * @param string $key
 * @return string
 */
function zbase_session_name($key)
{
	return zbase_tag() . '_' . $key;
}

/**
 * Set a key / value pair into the session
 *
 * @param string $key The index key
 * @param mixed $value The value of the key
 */
function zbase_session_set($key, $value)
{
	\Session::set(zbase_session_name($key), $value);
}

/**
 * Remove one or many items from the session.
 *
 * @param  string|array $key
 * @return void
 */
function zbase_session_forget($key)
{
	\Session::forget(zbase_session_name($key));
}

/**
 * Flash a key / value pair to the session
 *
 * @param string $key The index key
 * @param mixed $value The value of the key
 */
function zbase_session_flash($key, $value)
{
	\Session::flash(zbase_session_name($key), $value);
}

/**
 * Check if a $key exists from the Session
 *
 * @param type $key
 * @return type
 */
function zbase_session_has($key)
{
	return \Session::has(zbase_session_name($key));
}

/**
 * Push a value onto a session array.
 *
 * @param  string  $key
 * @param  mixed   $value
 * @return void
 */
function zbase_session_push($key, $value)
{
	\Session::push(zbase_session_name($key), $value);
}

/**
 * Get the value of a given key and then forget it.
 *
 * @param  string  $key
 * @param  string  $default
 * @return mixed
 */
function zbase_session_pull($key, $default = null)
{
	return \Session::pull(zbase_session_name($key), $default);
}

/**
 * Get the value of a given key
 *
 * @param  string  $key
 * @param  string  $default
 * @return mixed
 */
function zbase_session_get($key, $default = null)
{
	return \Session::get(zbase_session_name($key), $default);
}

/**
 * Return the Session Object
 *
 * @return \Illuminate\Session\SessionManager
 */
function zbase_session()
{
	return app('session');
}

/**
 * Return all Sessions contents
 *
 * @return array
 */
function zbase_sessions()
{
	return \Session::all();
}

/**
 * Remove all of the items from the session.
 *
 * @return void
 */
function zbase_sessions_flush()
{
	\Session::flush();
}

/**
 * Generate a new session identifier.
 *
 * @param  bool  $destroy
 * @return bool
 */
function zbase_sessions_regenerate()
{
	\Session::regenerate();
}

/**
 * Reflash all of the session flash data
 * @return void
 */
function zbase_sessions_reflash()
{
	\Session::reflash();
}

/**
 * Return the Current Sessions ID
 * @return string
 */
function zbase_session_id()
{
	return \Session::getId();
}

/**
 * Retrieve a cookie value
 *
 * @param type $key
 * @param type $default
 * @return string
 */
function zbase_cookie($key, $default = null)
{
	return \Cookie::get($key, $default);
}

/**
 * Create a cookie that lasts N minutes
 *
 * @param type $key
 * @param type $value
 * @param type $minutes
 */
function zbase_cookie_make($key, $value, $minutes = 60)
{
	\Cookie::queue($key, $value, $minutes);
	//\Cookie::make($key, $value, $minutes);
}

/**
 * Create a cookie that lasts for ever
 *
 * @param type $key
 * @param type $value
 */
function zbase_cookie_forever($key, $value)
{
	\Cookie::forever($key, $value);
}

/**
 * Forget a cookie
 *
 * @param type $key
 */
function zbase_cookie_forget($key)
{
	\Cookie::forget($key);
}
