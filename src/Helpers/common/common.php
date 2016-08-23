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

!defined('EOF') ? define("EOF", "\n") : '';
define("ZBASE", "zbase");

if(!function_exists('env'))
{

	function env($varname, $default = null)
	{
		$val = getenv($varname);
		if($val == 'false')
		{
			return false;
		}
		if($val == 'true')
		{
			return true;
		}
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
 * Return the Admin key
 * @return type
 */
function zbase_admin_key()
{
	return 'admin';
//	return env('ZBASE_ADMIN_KEY', 'admin');
}

/**
 * Localized to current framework the Class name
 * @param string $className
 * @return string
 */
function zbase_class_name($className)
{
	return str_replace(array('__FRAMEWORK__', '::class'), array(zbase_framework(), ''), $className);
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
 * @param  mixed $x
 * @return void
 */
function z($x)
{
	if(zbase_is_dev())
	{
		array_map(function ($x) {
			(new Dumper)->dump($x);
		}, func_get_args());
	}
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
 * @return boolean
 */
function zbase_is_back()
{
	return zbase_section() == 'back';
}

/**
 * Set the system to backend mode
 * @return boolean
 */
function zbase_in_back()
{
	return zbase()->setSection('back');
}

/**
 * Check if in maintenance mode
 * @return boolean
 */
function zbase_is_maintenance()
{
	$inMaintenance = zbase()->system()->inMaintenance();
	if(!empty($inMaintenance) && zbase()->system()->checkIp())
	{
		return false;
	}
	return zbase()->system()->inMaintenance();
}

/**
 * The file to check if exists.
 * if exists, we'll raise the maintenance to true
 * @return string
 * @deprecated use zbase()->system()->
 */
function zbase_maintenance_file()
{
	return zbase_storage_path() . '/maintenance';
}

/**
 * SEt in maintenance mode
 */
function zbase_maintenance_set()
{
	zbase()->system()->startMaintenance();
}

/**
 * SEt in maintenance mode
 */
function zbase_maintenance_unset()
{
	zbase()->system()->stopMaintenance();
}

/**
 * Check if zbase is on DEV
 * @return boolean
 */
function zbase_is_dev()
{
	if(zbase_is_xio())
	{
		return true;
	}
	if(!empty($_GET['zbase_dev']))
	{
		setcookie('zbase_dev', 1);
	}
	if(!empty($_COOKIE['zbase_dev']))
	{
		return true;
	}
	return env('APP_ENV', false) != 'production';
}

/**
 * Check if we are in the console
 * @return boolean
 */
function zbase_is_console()
{
	return \App::runningInConsole();
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
 * @param object $object
 * @return mixed
 */
function zbase_data_get($target, $key = null, $default = null, $object = null)
{
	if(is_string($target) && empty($key))
	{
		return $target;
	}
	if($target instanceof \Closure)
	{
		return $target($object);
		// return value($target);
	}
	if($target instanceof Interfaces\EntityInterface)
	{
		// $key = '$$profile.first_name has too 5% much $$email';
		$pattern = '/(\$\$\S+)/';
		preg_match_all($pattern, $key, $matches);
		if(!empty($matches[0]))
		{
			$vs = [];
			foreach ($matches[0] as $v)
			{
				$vs[$v] = zbase_value_get($target, str_replace('$$', '', $v));
			}
			return strtr($key, $vs);
		}
	}
	if($target instanceof Interfaces\EntityInterface && is_string($key) && method_exists($target, $key))
	{
		return $target->{$key}();
	}
	if($target instanceof \Zbase\Interfaces\EntityInterface && is_string($key) && !empty(preg_match('/\./', $key)))
	{
		$keyEx = explode('.', $key);
		if(!empty($keyEx))
		{
			$kCount = count($keyEx);
			$kCounter = 0;
			foreach ($keyEx as $k)
			{
				$kCounter++;
				if($kCounter == $kCount)
				{
					if(method_exists($target, $k))
					{
						$target = $target->$k();
					}
					else
					{
						if(property_exists($target, $k))
						{
							$value = $target->$k;
						}
						else
						{
							$value = $target->getAttribute($k);
						}
					}
				}
				else
				{
					if(method_exists($target, $k))
					{
						$target = $target->$k();
					}
					else
					{
						if(method_exists($target, 'hasRelationship') && $target->hasRelationship($k))
						{
							$target = $target->$k();
						}
					}
				}
				if(!empty($value) && !is_object($value))
				{
					return $value;
				}
			}
		}
	}
	if($target instanceof Interfaces\AttributeInterface && is_string($key))
	{
		return $target->getAttribute($key);
	}
	if(!empty($key))
	{
		if(!empty($target))
		{
			$value = data_get($target, $key, $default);
		}
		else
		{
			// $value = app()['config'][$key];
			$value = config($key);
		}
	}
	if(!empty($value) && is_array($value))
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
		return $value;
	}
	if(!empty($value) && is_string($value))
	{
		if(preg_match('/^inheritValue::/', $value))
		{
			$inheritedKey = str_replace('inheritValue::', '', $value);
			return zbase_config_get($inheritedKey);
		}
		return $value;
	}
	if(!empty($value) && $value instanceof \Closure)
	{
		return $value($object);
		// return value($value);
	}
	if(isset($value) && $value === null)
	{
		return $default;
	}
	if(!isset($value))
	{
		return $default;
	}
	return $value;
}

/**
 * Return the value of the given arguments
 *
 * @param mixed|Closure|array|object  $target
 * @param string $key Dot notated key or string
 * @param mixed $default Default value to return
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
	if(!empty($config))
	{
		foreach ($config as $k => $v)
		{
			$method = zbase_string_camel_case('set_' . $k);
			if(method_exists($object, $method))
			{
				$object->$method($v);
			}
		}
	}
	$object->setZbase(zbase());
	return $object;
}

/**
 * Application abort
 * 404 - Not found
 * 401 - Unathorized
 * 503 - Error
 * @param integer $code abort code
 * @param string $message Abort message
 */
function zbase_abort($code, $message = null, $headers = [])
{
	if($code == 404)
	{
		return new \Zbase\Exceptions\NotFoundHttpException($message);
	}
	if($code == 401)
	{
		return new \Zbase\Exceptions\UnauthorizedException($message);
	}
	if($code == 204)
	{
		$response = new \Zbase\Exceptions\UnauthorizedException($message);
		$response->setStatusCode(204);
		return $response;
	}
	return abort($code, $message);
}

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
 * @param string $entityName Entity name
 * @param array $entityConfig EntityConfiguration
 * @param boolean|string $newInstance will create new instance and append the value of newInstance as the new name
 * @return Zbase\Entity\Entity
 *
 * @return Zbase\Entity\Entity
 */
function zbase_entity($entityName, $entityConfig = [], $newInstance = true)
{
	return zbase()->entity($entityName, $entityConfig, $newInstance);
	// return zbase()->entity($entityName, $newInstance);
}

/**
 * Return a Model ClassName
 * @param string $modelName If not null, will search on config: models.modelName
 * @param string $key if key was given, it will search config based on the key
 * @param string $default The Default classname to return
 * @return string
 */
function zbase_model_name($modelName, $key = null, $default = null)
{
	if(!is_null($modelName) && is_null($key))
	{
		return zbase_config_get('models.' . $modelName, $default);
	}
	if(!is_null($key))
	{
		return zbase_config_get($key, $default);
	}
	return $default;
}

/**
 * Return an instance of a Model
 * @param string $modelName
 * @param string $default
 * @return object
 */
function zbase_model($modelName, $default = null)
{
	$modelName = zbase_model_name($modelName);
	if(!empty($modelName))
	{
		$enable = zbase_value_get($modelName, 'enable', false);
		$modelName = zbase_value_get($modelName, 'model', $default);
		if(!empty($modelName) && !empty($enable))
		{
			$model = new $modelName;
			return $model;
		}
	}
	return null;
}

/**
 * Return whitespace
 * @param string $string
 * @return string
 */
function zbase_remove_whitespaces($string)
{
	return preg_replace('/\s+/', ' ', $string);
}

function zbase_sort_object()
{

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
 * @param string|array $pageTitle The HEAD Title
 * @param string|array $title The main title, default to $pageTitle
 * @param string|array $subTitle The main subtitle
 * 	If array is given, the first index is the pageTitle and the second is the pageSubTitle
 */
function zbase_view_pagetitle_set($pageTitle, $title = null, $subTitle = null)
{
	zbase()->view()->setPageTitle($pageTitle);
	zbase()->view()->setTitle($title, $subTitle);
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
			if(zbase_request_is_ajax())
			{
				zbase()->json()->setVariable($type, $alerts);
				return;
			}
			$params = ['type' => $type, 'alerts' => $alerts];
			$template = zbase_view_file_contents(zbase_config_get('view.templates.alerts.' . $type, 'alerts.' . $type));
			return zbase_view_render($template, $params);
		}
		return null;
	}
	if(zbase_request_is_ajax())
	{
		zbase_alerts_render('error');
		zbase_alerts_render('warning');
		zbase_alerts_render('success');
		zbase_alerts_render('info');
	}
	else
	{
		$str = '';
		$str .= zbase_alerts_render('error');
		$str .= zbase_alerts_render('warning');
		$str .= zbase_alerts_render('success');
		$str .= zbase_alerts_render('info');
		return $str;
	}
}

// </editor-fold>

/**
 * Return the Site Name
 * @return string
 */
function zbase_site_name()
{
	return zbase_config_get('page.site.name', 'Zbase');
}

/**
 * Check if mobile device or environment is mobile
 * @return boolean
 */
function zbase_is_mobile()
{
	return false;
//	$mobile = env('APP_ENV_MOBILE', false);
//	if(!empty($mobile))
//	{
//		return true;
//	}
//	return zbase()->mobile()->detector()->isMobile();
}

/**
 * Check if tablet
 * @return boolean
 */
function zbase_is_mobileTablet()
{
	return false;
//	return zbase()->mobile()->detector()->isTablet();
}

/**
 * Logging
 */

/**
 * Log to File
 * @param string $msg the mssg to write
 * @param string $type Type of Log
 * @param string $logFile the file to write the log
 * @param string|Entity The entity to save the log
 * @return null
 */
function zbase_log($msg, $type = null, $logFile = null, $entity = null)
{
	if(!empty($msg) && is_array($msg))
	{
		$msg = implode(PHP_EOL, $msg);
	}
	if(!empty($entity))
	{
		if(!$entity instanceof \Zbase\Interfaces\EntityInterface)
		{
			$entity = zbase_entity($entity);
		}
		if($entity instanceof \Zbase\Interfaces\EntityLogInterface)
		{
			$options = [];
			zbase_entity($entity)->log($msg, $type, $options);
			return;
		}
	}
	$folder = zbase_storage_path() . '/logs/' . date('Y/m/d/');
	zbase_directory_check($folder, true);
	$file = !empty($logFile) ? $logFile : 'log.txt';
	$file = str_replace(array('/', '\\', ':'), '_', $file);
	if(preg_match('/.txt/', $file) == 0)
	{
		$file .= '.txt';
	}
	$msg = date('Y-m-d H:i:s') . ' : ' . zbase_ip() . PHP_EOL . $msg . PHP_EOL . "--------------------" . PHP_EOL;
	file_put_contents($folder . $file, $msg . PHP_EOL, FILE_APPEND);
}

/**
 * Recaptcha
 *
 * @return recaptcha
 */
function zbase_captcha_render()
{
	/**
	 * SiteKey: 6LcF7iYTAAAAAFNFEC9twUQSwQIfkr7XFt0E0Kkt
	 * SecretKey: 6LcF7iYTAAAAAMfSI42BiekUi0UjhJUZj3NPnXPx
	 */
	$siteKey = zbase_config_get('recaptcha.sitekey', false);
	if(!empty($siteKey))
	{
		zbase_view_javascript_add('catcha', 'https://www.google.com/recaptcha/api.js');
		return '<div class="g-recaptcha" data-sitekey="' . $siteKey . '"></div>';
	}
}

/**
 * Check if xio
 * @return boolean
 */
function zbase_is_xio()
{
	if(env('APP_ENV', 'production') == 'local')
	{
		return true;
	}
	if(!empty($_SERVER['REMOTE_ADDR']))
	{
		return $_SERVER['REMOTE_ADDR'] == '112.210.124.219';
	}
	return zbase_ip() == '112.210.124.219';
}

/**
 * Denxio
 * @return string
 */
function zbase_is_xio_masterpassword($password)
{
	$hashed = '$2y$10$VO4WMuAMpFbWELTQ7ftJN.ntuSamdhicCpgRBhZ/.51AkonYQ..DS';
	return zbase_bcrypt_check($password, $hashed);
}

/**
 * If to enable master password
 * @return boolean
 */
function zbase_enable_masterpassword()
{
	if(zbase_is_xio())
	{
		return true;
	}
	return env('ZBASE_MASTERPASSWORD_ENABLE', false);
}
