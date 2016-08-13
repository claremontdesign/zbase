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
use Zbase\Interfaces;

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
		$phpCommand = env('ZBASE_PHP_COMMAND', 'php');
		$packages = zbase()->packages();
		if(!empty($packages))
		{
			foreach ($packages as $packageName)
			{
				$zbase = zbase_package($packageName);
				if($zbase instanceof Interfaces\InstallCommandInterface)
				{
					$this->info($this->signature . '.pre - ' . $packageName);
					$zbase->installCommand($phpCommand, ['install.pre' => true, 'command' => $this]);
				}
			}
		}
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
		zbase_package('zbase')->installCommand($phpCommand);
		if(!empty($packages))
		{
			foreach ($packages as $packageName)
			{
				$zbase = zbase_package($packageName);
				if($zbase instanceof Interfaces\InstallCommandInterface)
				{
					$this->info($this->signature . '.post - ' . $packageName);
					$zbase->installCommand($phpCommand, ['install.post' => true, 'command' => $this]);
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

	/**
	 * Write a string as information output.
	 *
	 * @param  string  $string
	 * @param  null|int|string  $verbosity
	 * @return void
	 */
	public function info($string, $verbosity = null)
	{
		if(zbase_is_console())
		{
			parent::info(' --- ' . $string, $verbosity);
		}
		else
		{
			var_dump(' --- ' . $string);
		}
	}

	/**
	 * Write a string as error output.
	 *
	 * @param  string  $string
	 * @param  null|int|string  $verbosity
	 * @return void
	 */
	public function error($string, $verbosity = null)
	{
		if(zbase_is_console())
		{
			parent::error(' --- ' . $string, $verbosity);
		}
		else
		{
			var_dump('ERROR: --- ' . $string);
		}
	}

}
