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

class Test extends Command
{

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $signature = 'zbase:test';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Test Zbase';

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
				if($zbase instanceof Interfaces\TestCommandInterface)
				{
					$this->info($this->signature . '.pre - ' . $packageName);
					$zbase->testCommand($phpCommand, ['test.pre' => true, 'command' => $this]);
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
		zbase_package('zbase')->testCommand($phpCommand, ['test.post' => true, 'command' => $this]);
		if(!empty($packages))
		{
			foreach ($packages as $packageName)
			{
				$zbase = zbase_package($packageName);
				if($zbase instanceof Interfaces\TestCommandInterface)
				{
					$this->info($this->signature . '.post - ' . $packageName);
					$zbase->testCommand($phpCommand, ['test.post' => true, 'command' => $this]);
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
        parent::info(' --- ' . $string, $verbosity);
    }
}
