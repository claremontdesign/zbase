<?php

/**
 * Zbase Helpers - Common
 *
 * Functions and Helpers Common helpers
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file common.php
 * @project Zbase
 * @package Zbase\Helpers
 */
use Illuminate\Support\Debug\Dumper;
use Zbase\Interfaces;

define("EOF", "\n");
define("ZBASE", "zbase");

if(!function_exists('env'))
{

	function env($varname, $default = null)
	{
		$val = getenv($varname);
		if($val === false)
		{
			return $default;
		}
		return $val;
	}

}

/**
 * Return the current zbase framework
 *
 * @return string
 */
function zbase_framework()
{
	return ucfirst(strtolower(env('ZBASE_FRAMEWORK', 'laravel')));
}

/**
 * Localized to current framework the Class name
 * @param string $className
 * @return string
 */
function zbase_class_name($className)
{
	return str_replace('__FRAMEWORK__', zbase_framework(), $className);
}

/**
 * The Zbase Tag/Prefix
 *
 * @return string
 */
function zbase_tag()
{
	return strtolower(env('ZBASE', ZBASE));
}

/**
 * Dump the passed variables
 *
 * @param  mixed
 * @return void
 */
function z()
{
	array_map(function ($x) {
		(new Dumper)->dump($x);
		}, func_get_args());
}

/**
 * Return the current section
 * @return string
 */
function zbase_section()
{
	return zbase()->section();
}

/**
 * Check if we are in front
 * @return boolean
 */
function zbase_is_front()
{
	return zbase_section() == 'front';
}

/**
 * Check if we are in the backend
 *
 * @return boolean
 */
function zbase_is_back()
{
	return zbase_section() == 'back';
}

/**
 * Get an item from an array or object using "dot" notation.
 * 	If the value retrieved is an array, it will check for "merge" index
 * 		and will get the value of the merge and array_replace_recursive it with the
 * 		value extracted
 *
 * If $target is empty, will use zbase_config_get to retrieve the value
 * <code>
 * <?php

 * ?>
 * </code>
 *
 * @param  array  $target
 * @param  string  $key
 * @param  mixed   $default
 * @return mixed
 */
function zbase_data_get($target, $key, $default = null)
{
	if($target instanceof \Closure)
	{
		return value($target);
	}
	if($target instanceof Interfaces\AttributeInterface && is_string($key))
	{
		return $target->getAttribute($key);
	}
	if(!empty($target))
	{
		$value = data_get($target, $key, $default);
	}
	else
	{
		$value = config($key, $default);
	}
	if(is_array($value))
	{
		if(!empty($value['configInherit']))
		{
			$mergeValue = $value['configInherit'];
			unset($value['configInherit']);
			return array_replace_recursive($value, zbase_config_get($mergeValue, []));
		}
		if(!empty($value['configReplace']))
		{
			$mergeValue = $value['configReplace'];
			unset($value['configReplace']);
			return array_replace_recursive($value, zbase_config_get($mergeValue, []));
		}
		if(!empty($value['configMerge']))
		{
			$mergeValue = $value['configMerge'];
			unset($value['configMerge']);
			return array_merge_recursive($value, zbase_config_get($mergeValue, []));
		}
	}
	if(is_string($value))
	{
		if(preg_match('/^inheritValue::/', $value))
		{
			$inheritedKey = str_replace('inheritValue::', '', $value);
			return zbase_config_get($inheritedKey);
		}
	}
	if($value instanceof \Closure)
	{
		return value($value);
	}
	return $value;
}

/**
 * Return the value of the given arguments
 *
 * @param mixed|Closure|array|object  $target
 * @param string $key
 * @return mixed
 */
function zbase_value_get($target, $key = null, $default = null)
{
	return zbase_data_get($target, $key, $default);
}

/**
 * Create an object based from the given $modelName and $params
 *
 * @param string $className The ClassName
 * @param array $config Some configuration
 */
function zbase_object_factory($className, $config = [])
{
	$object = new $className();
	$object->setZbase(zbase());
	return $object;
}

/**
 * Application abort
 *
 * @param integer $code abort code
 * @param string $message Abort message
 */
function zbase_abort($code, $message = null)
{
	return abort($code, $message);
}

// <editor-fold defaultstate="collapsed" desc="View">
/**
 * Return an HTML Attribute of the given selector
 *
 * <div <?php echo zbase_view_ui_tag_attributes('alert', 'class="alert alert-danger fade in" role="alert"'); ?>></div>
 *
 * @param string $selector
 * @param string $defaultAttributes
 * @return string
 */
function zbase_view_ui_tag_attributes($selector, $defaultAttributes = null)
{
	$config = zbase_config_get('ui.' . $selector . '.html.attributes', []);
	if(!empty($config))
	{
		if(!empty($defaultAttributes))
		{
			$att = [];
			$atts = new \SimpleXMLElement("<element $defaultAttributes />");
			$a = (array) $atts->attributes();
			$a = array_merge_recursive($config, $a['@attributes']);
			foreach ($a as $k => $v)
			{
				if(is_array($v))
				{
					$att[] = $k . '="' . implode(' ', $v) . '"';
				}
				else
				{
					$att[] = $k . '="' . $v . '"';
				}
			}
			return implode(' ', $att);
		}
		else
		{
			$att = [];
			foreach ($config as $k => $v)
			{
				$att[] = $k . '="' . $v . '"';
			}
			return implode(' ', $att);
		}
	}
	else
	{
		return $defaultAttributes;
	}
	return null;
}

// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="Others">
/**
 * Set the page title
 * @param string|array $pageTitle
 * 	If array is given, the first index is the pageTitle and the second is the pageSubTitle
 */
function zbase_view_pagetitle_set($pageTitle)
{
	zbase()->view()->setPageTitle($pageTitle);
}

/**
 * Set the Meta-Description
 *
 * @param string $description
 */
function zbase_view_meta_description($description)
{
	zbase_view_head_meta_add('description', $description);
}

/**
 * SEt the Meta Keywords
 *
 * @param string $keywords
 */
function zbase_view_meta_keywords($keywords)
{
	zbase_view_head_meta_add('keywords', $keywords);
}

// </editor-fold>
// <editor-fold defaultstate="collapsed" desc="Alerts">

/**
 * Render alerts
 *
 * @param string $type
 * @return html
 */
function zbase_alerts_render($type = null)
{
	if(!empty($type))
	{
		$alerts = zbase_alerts($type);
		if(!empty($alerts))
		{
			$params = ['type' => $type, 'alerts' => $alerts];
			$template = zbase_config_get('view.templates.alerts.' . $type, 'alerts.' . $type);
			return zbase_view_render($template, $params);
		}
		return null;
	}
	$str = '';
	$str .= zbase_alerts_render('error');
	$str .= zbase_alerts_render('warning');
	$str .= zbase_alerts_render('success');
	$str .= zbase_alerts_render('info');
	return $str;
}

// </editor-fold>

/**
 * Return the DB Prefix
 * @return stroiing
 */
function zbase_db_prefix()
{
	return zbase_config_get('db.prefix');
}

/**
 * Return the Entity Model of a given entityName
 *
 * @param string $entityName
 * @param array $entityConfig
 *
 * @return Zbase\Entity\Entity
 */
function zbase_entity($entityName)
{
	return zbase()->entity($entityName);
}
