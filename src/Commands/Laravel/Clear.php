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

class Clear extends Command
{

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $signature = 'zbase:clear';

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
		echo shell_exec('php artisan clear-compiled');
		echo shell_exec('php artisan cache:clear');
		echo shell_exec('php artisan view:clear');
		echo shell_exec('php artisan config:clear');
		echo shell_exec('php artisan route:clear');
		$commands = [];// zbase()->commands('clear');
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
