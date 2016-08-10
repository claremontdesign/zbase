<?php

class TestCase extends \Orchestra\Testbench\TestCase
{

	/**
	 * return the Zbase App
	 * @param type $app
	 * @return type
	 */
	protected function getZbase($app)
	{
		return ['\Zbase\LaravelServiceProvider'];
	}

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
		Artisan::call('zbase:migrate');
		Mail::pretend(true);
	}

}
