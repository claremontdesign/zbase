<?php

class TestCase extends \Orchestra\Testbench\TestCase
{

	public function setUp()
	{
		parent::setUp();

		$this->prepareDbForTests();
	}

	private function prepareDbForTests()
	{
		Artisan::call('migrate');
		Mail::pretend(true);
	}

}
