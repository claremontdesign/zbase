<?php

namespace Zbase;

/**
 * Zbase ServiceProvider
 *
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file ServiceProvider.php
 * @project Zbase
 * @package Zbase
 */
class LaravelServiceProvider extends \Illuminate\Support\ServiceProvider
{

	public function register()
	{
		if(zbase_is_testing())
		{
			zbase_file_copy(__DIR__ . '/../tests/config/database.php', zbase_base_path() . '/config/testing/database.php');
		}
		$this->app->singleton(zbase_tag(), function(){
			return new Zbase;
		});
	}

	public function boot()
	{
		$this->loadViewsFrom(__DIR__ . '/../resources/views', zbase_tag());
		if(!zbase_is_testing())
		{
			$this->mergeConfigFrom(
					__DIR__ . '/../config/config.php', zbase_tag()
			);
			$configs = [];
			$packages = zbase()->packages();
			if(!empty($packages))
			{
				foreach ($packages as $packageName)
				{
					$configFiles = zbase_package($packageName)->config();
					if(is_array($configFiles))
					{
						foreach ($configFiles as $configFile)
						{
							if(file_exists($configFile))
							{
								$configs = array_replace_recursive($configs, require $configFile);
							}
						}
					}
					else
					{
						$configs = array_replace_recursive($configs, require $configFiles);
					}
				}
			}
			$this->app['config'][zbase_tag()] = array_replace_recursive($this->app['config'][zbase_tag()], $configs);
		}
		else
		{
			$this->loadViewsFrom(__DIR__ . '/../tests/resources/views', zbase_tag() . 'test');
			copy(__DIR__ . '/../config/entities/user.php', __DIR__ . '/../tests/config/entities/user.php');
			copy(__DIR__ . '/../config/entities/user.php', __DIR__ . '/../tests/config/entities/user.php');
			$this->mergeConfigFrom(
					__DIR__ . '/../tests/config/config.php', zbase_tag()
			);
		}

		$this->publishes([
			__DIR__ . '/../resources/assets' => zbase_public_path(zbase_path_asset()),
				], 'public');

		$this->publishes([
			__DIR__ . '/../database/migrations' => base_path('database/migrations'),
			__DIR__ . '/../database/seeds' => base_path('database/seeds'),
			__DIR__ . '/../database/factories' => base_path('database/factories')
				], 'migrations');

		$this->app['config']['database.connections.mysql.prefix'] = zbase_db_prefix();
		$this->app['config']['auth.providers.users.model'] = get_class(zbase_entity('user'));
		$this->app['config']['auth.passwords.users.table'] = zbase_config_get('entity.user_tokens.table.name');
		require __DIR__ . '/Http/Controllers/Laravel/routes.php';
		if(!zbase_is_testing())
		{
		}
	}

}
