<?php

namespace Zbase\Commands\Laravel;

/**
 * Zbase-Command Migrate
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Migrate.php
 * @project Zbase
 * @package Zbase/Traits
 */
use Illuminate\Console\Command;
use Zbase\Interfaces;

class Migrate extends Command
{

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $signature = 'zbase:migrate';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Database Migration and Seeding';

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
		file_put_contents(zbase_maintenance_file(), 1);
		$phpCommand = env('ZBASE_PHP_COMMAND', 'php');
		$packages = zbase()->packages();
		if(!empty($packages))
		{
			foreach ($packages as $packageName)
			{
				$zbase = zbase_package($packageName);
				if($zbase instanceof Interfaces\MigrateCommandInterface)
				{
					$this->info($this->signature . '.pre - ' . $packageName);
					$zbase->migrateCommand($phpCommand, ['migrate.pre' => true, 'command' => $this]);
				}
			}
		}
		\File::cleanDirectory(database_path() . '/migrations');
		\File::cleanDirectory(database_path() . '/seeds');
		\File::cleanDirectory(database_path() . '/factories');
		\File::cleanDirectory(storage_path() . '/zbase');
		\File::cleanDirectory(storage_path() . '/framework/cache');
		\File::cleanDirectory(storage_path() . '/framework/views');
		\File::cleanDirectory(storage_path() . '/logs');
		echo shell_exec($phpCommand . ' artisan vendor:publish --tag=migrations --force');
		echo shell_exec($phpCommand . ' artisan optimize');
		echo shell_exec($phpCommand . ' artisan migrate:refresh --seed --force');
		$commands = []; // zbase()->commands('migrate');
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
				if($zbase instanceof Interfaces\MigrateCommandInterface)
				{
					$this->info($this->signature . '.post - ' . $packageName);
					$zbase->migrateCommand($phpCommand, ['migrate.post' => true, 'command' => $this]);
				}
			}
		}
		if(file_exists(zbase_maintenance_file()))
		{
			unlink(zbase_maintenance_file());
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
        parent::info(' --- ' . $string, $verbosity);
    }
}
