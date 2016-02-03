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
		\File::cleanDirectory(database_path() . '/migrations');
		\File::cleanDirectory(database_path() . '/seeds');
		\File::cleanDirectory(database_path() . '/factories');
		// echo shell_exec('php artisan cdbase:clear');
		echo shell_exec('php artisan vendor:publish --tag=migrations --force');
		echo shell_exec('php artisan optimize');
		echo shell_exec('php artisan migrate:refresh --seed --force');
		$commands = [];// zbase()->commands('migrate');
		if(!empty($commands))
		{
			foreach ($commands as $command)
			{
				echo shell_exec('php artisan ' . $command);
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
