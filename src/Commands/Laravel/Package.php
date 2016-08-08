<?php

namespace Zbase\Commands\Laravel;

/**
 * Zbase-Command Package
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Package.php
 * @project Zbase
 * @package Zbase/Traits
 *
 *
 * [packagename]
 *		config
 *			config.php
 *			[packagename.php]
 *		resources
 *			assets
 *				js
 *				css
 *				img
 *			raw
 *			views
 *				contents
 *				templates
 *					front
 *						default
 *							layout.blade.php
 *		database
 *		src
 *			Helpers
 *				Laravel
 *					helpers.php
 *				helpers.php
 *			LaravelServiceProvider.php
 *			[Packagename.php]
 *
 */
use Illuminate\Console\Command;

class Package extends Command
{

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $signature = 'zbase:package';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create a Zbase Package';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		//$commands = [];
		//$phpCommand = env('ZBASE_PHP_COMMAND', 'php');
		dd($this->getArgument('name'));
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		// [$name, $mode, $description, $defaultValue]
		return array();
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
//		[$name, $shortcut, $mode, $description, $defaultValue]
		return array();
	}



    /**
     * Write a string as information output.
     *
     * @param  string  $string
     * @param  null|int|string  $verbosity
     * @return void
     */
    public function info($string, $verbosity = null)
    {
        parent::info(' --- ' . $string, $verbosity);
    }
}
