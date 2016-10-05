<?php

namespace Zbase\Commands\Laravel;

/**
 * Zbase-Command Clear
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Clear.php
 * @project Zbase
 * @package Zbase/Traits
 */
use Illuminate\Console\Command;
use Zbase\Interfaces;

class Clear extends Command
{

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $signature = 'zbase:clear {options}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Clear all cache and views';

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
		zbase()->setConsoleCommand($this);
		$phpCommand = env('ZBASE_PHP_COMMAND', 'php');
		$packages = zbase()->packages();
		if(!empty($packages))
		{
			foreach ($packages as $packageName)
			{
				$zbase = zbase_package($packageName);
				if($zbase instanceof Interfaces\ClearCommandInterface)
				{
					$this->info($this->signature . '.pre - ' . $packageName);
					$zbase->clearCommand($phpCommand, ['clear.pre' => true, 'command' => $this]);
				}
			}
		}
		echo shell_exec($phpCommand . ' artisan clear-compiled');
		echo shell_exec($phpCommand . ' artisan cache:clear');
		echo shell_exec($phpCommand . ' artisan view:clear');
		echo shell_exec($phpCommand . ' artisan config:clear');
		echo shell_exec($phpCommand . ' artisan route:clear');
		\File::cleanDirectory(zbase_storage_path('tmp/images'));
		\File::cleanDirectory(zbase_storage_path(zbase_tag() . '_tmp/images'));
		$commands = []; // zbase()->commands('clear');
		if(!empty($commands))
		{
			foreach ($commands as $command)
			{
				if($command instanceof \Closure)
				{
					$command();
				}
				else
				{
					echo shell_exec($phpCommand . ' artisan ' . $command);
				}
			}
		}
		if(!empty($packages))
		{
			foreach ($packages as $packageName)
			{
				$zbase = zbase_package($packageName);
				if($zbase instanceof Interfaces\ClearCommandInterface)
				{
					$this->info($this->signature . '.post - ' . $packageName);
					$zbase->clearCommand($phpCommand, ['clear.post' => true, 'command' => $this]);
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
