<?php

namespace Packagename;

/**
 * Packagename ServiceProvider
 *
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file LaravelServiceProvider.php
 * @project Packagename
 * @package Packagename
 */
class LaravelServiceProvider extends \Illuminate\Support\ServiceProvider
{

	public function register()
	{
		$this->app->singleton(packagename_tag(), function(){
			return new Packagename();
		});
		zbase()->addPackage(packagename_tag());
	}

	public function boot()
	{
		if(zbase_file_exists(__DIR__ . '/../resources/views'))
		{
			$this->loadViewsFrom(__DIR__ . '/../resources/views', packagename_tag());
		}
		if(zbase_file_exists(__DIR__ . '/../resources/assets'))
		{
			$this->publishes([
				__DIR__ . '/../resources/assets' => zbase_public_path(zbase_path_asset(packagename_tag())),
					], 'public');
		}
		if(zbase_file_exists(__DIR__ . '/Http/Controllers/Laravel/routes.php'))
		{
			require __DIR__ . '/Http/Controllers/Laravel/routes.php';
		}
	}

}
