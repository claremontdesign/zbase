<?php

class TestCase extends \Orchestra\Testbench\TestCase
{

	public function setUp()
	{
		parent::setUp();
		$this->prepareDbForTests();
	}

	public function tearDown()
	{
		parent::tearDown();
	}

	private function prepareDbForTests()
	{
		Artisan::call('migrate');
		Mail::pretend(true);
	}

}
