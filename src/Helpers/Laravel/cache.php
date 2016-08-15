<?php

/**
 * Zbase-Laravel Helpers-Cache
 *
 * Functions and Helpers for Caching
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file cache.php
 * @project Zbase
 * @package Zbase/Laravel/Helpers
 *
 * https://laravel.com/docs/5.1/cache
 * $value = Cache::get('key','default');
 * $expiresAt = Carbon::now()->addMinutes(10);
 * Cache::put('key', 'value', $expiresAt);
 * Cache::forever('key', 'value');
 * Cache::forget('key');
 * Cache::tags(['people', 'authors'])->put('Anne', $anne, $minutes);
 * $anne = Cache::tags(['people', 'authors'])->get('Anne');
 * Cache::tags('authors')->flush();
 *
  zbase_cache(zbase_cache_key($this, __FUNCTION__, func_get_args(), $this->getModel()->getTable()),
  function() {return 'value';},
  [$this->getModel()->getTable()]
  );
 */

/**
 * Check if caching is enabled
 * @return boolean
 */
function zbase_cache_enable()
{
	return env('CACHE_ENABLE', null);
}

/**
 * Check if current cache supports tagging
 *
 * @return boolean
 */
function zbase_cache_support_tags()
{
	if(method_exists(zbase_cache_driver()->getStore(), 'tags'))
	{
		return true;
	}
	return false;
}

/**
 * Return the current cache driver
 * @return string
 */
function zbase_cache_driver($store = null)
{
	if(is_null($store))
	{
		return \Cache::driver();
	}
	return \Cache::store($store);
}

/**
 * Return a value from cache else save new
 *
 * @param string $key
 * @param \Closure $callback
 * @param integer $minutes number of minutes to store items. Default: 60m
 * @return mixed
 */
function zbase_cache($key, \Closure $callback, array $tags = null, $minutes = 60, $options = array())
{
	if($minutes === null)
	{
		$minutes = 60;
	}
	$logFile = !empty($options['logFile']) ? $options['logFile'] : __FUNCTION__;
	$logMsg = !empty($options['logMsg']) ? $options['logMsg'] : __FUNCTION__;
	if(zbase_cache_has($key, $tags, $options))
	{
		zbase_log($key . ' -- CACHE HIT' . PHP_EOL . $logMsg, null, $logFile);
		return zbase_cache_get($key, $tags, $options);
	}
	zbase_log($key . ' -- CACHE MISS' . PHP_EOL . $logMsg, null, $logFile);

	/**
	 * Force Cache Entity Level
	 */
	$forceCaching = zbase_config_get('db.cache.force', true);
	if(!empty($options['forceCache']) && !empty($forceCaching))
	{
		$value = $callback();
		zbase_cache_save($key, $value, $minutes, $tags, $options);
		return $value;
	}
	if(!zbase_cache_enable())
	{
		return $callback();
	}
	$value = $callback();
	zbase_cache_save($key, $value, $minutes, $tags, $options);
	return $value;
	// return \Cache::remember($key, $minutes, $callback);
}

/**
 * Check if a cache key exists
 *
 * @param string $key
 * @param array $tags Tags
 * @return boolean
 */
function zbase_cache_has($key, array $tags = null, $options = [])
{
	if(!empty($options['driver']))
	{
		return zbase_cache_driver($options['driver'])->has($key);
	}
	if(!zbase_cache_enable())
	{
		return false;
	}
	if(zbase_cache_support_tags() && !empty($tags))
	{
		return \Cache::tags($tags)->has($key);
	}
	return \Cache::has($key);
}

/**
 * Save value to cache key
 *
 * @param string $key
 * @param mixed $value
 * @param integer $minutes number of minutes to store items. Default: 60m
 * @param array $tags Tags
 * @return mixed
 */
function zbase_cache_save($key, $value, $minutes = 60, array $tags = null, $options = [])
{
	if(!empty($options['driver']))
	{
		return zbase_cache_driver($options['driver'])->put($key, $value, $minutes);
	}
	if(zbase_cache_support_tags() && !empty($tags))
	{
		return \Cache::tags($tags)->put($key, $value, $minutes);
	}
	return \Cache::put($key, $value, $minutes);
}

/**
 * Return the cache value of the $key
 * @param string $key
 * @param array $tags Tags
 * @return mixed
 */
function zbase_cache_get($key, array $tags = null, $options = [])
{
	if(!empty($options['driver']))
	{
		return zbase_cache_driver($options['driver'])->get($key);
	}
	if(!zbase_cache_enable())
	{
		return false;
	}
	if(zbase_cache_support_tags() && !empty($tags))
	{
		return \Cache::tags($tags)->get($key);
	}
	return \Cache::get($key);
}

/**
 * Delete a cache item by  key
 *
 * @param string $key
 * @param array $tags Tags
 * @return boolean
 */
function zbase_cache_remove($key, array $tags = null, $options = [])
{
	if(!empty($options['driver']))
	{
		zbase_cache_driver($options['driver'])->forget($key);
	}
	if(!zbase_cache_enable())
	{
		return false;
	}
	if(zbase_cache_support_tags() && !empty($tags))
	{
		return \Cache::tags($tags)->forget($key);
	}
	return \Cache::forget($key);
}

/**
 * Clear the entire cache
 *
 * @param array $tags
 * @return boolean
 */
function zbase_cache_flush(array $tags = null, $options = [])
{
	if(!empty($options['driver']))
	{
		zbase_cache_driver($options['driver'])->flush();
	}
	if(zbase_cache_support_tags() && !empty($tags))
	{
		\Cache::tags($tags)->flush();
	}
	\Cache::flush();
	zbase_cache_driver('file')->flush();
}

/**
 * Generate a Cache Key
 * @param object $object
 * @param string $method
 * @param array $arguments
 * @param string $prefix
 * @param string $suffix
 * @return string|md5
 */
function zbase_cache_key($object = null, $method = null, array $arguments = null, $prefix = null, $suffix = null)
{
	$strings = [];
	if(!empty($prefix))
	{
		$strings[] = md5($prefix);
	}
	if(is_object($object))
	{
		$strings[] = str_replace('\\', '_', get_class($object));
	}
	if(!empty($method))
	{
		$strings[] = $method;
	}
	if(is_array($arguments))
	{
		$strings[] = zbase_string_from_array($arguments);
	}
	if(!empty($suffix))
	{
		$strings[] = $suffix;
	}
	if(!empty($strings))
	{
		return md5(str_slug(implode(' ', $strings)));
	}
	return null;
}
