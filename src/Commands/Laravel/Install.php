<?php

namespace Zbase\Commands\Laravel;

/**
 * Zbase-Command Install
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Install.php
 * @project Zbase
 * @package Zbase/Traits
 */
use Illuminate\Console\Command;

class Install extends Command
{

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $signature = 'zbase:install';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Install Zbase';

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
		$commands = [];
		if(!empty($commands))
		{
			foreach ($commands as $command)
			{
				if($command instanceof \Closure)
				{
					$command();
				}
			}
		}
		$phpCommand = env('ZBASE_PHP_COMMAND', 'php');
		zbase_package('zbase')->installCommand($phpCommand);
		$packages = zbase()->packages();
		if(!empty($packages))
		{
			foreach ($packages as $packageName)
			{
				$zbase = zbase_package($packageName);
				if($zbase instanceof Interfaces\InstallCommandInterface)
				{
					$zbase->installCommand($phpCommand);
				}
			}
		}
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array();
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array();
	}

}
