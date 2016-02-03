<?php

/**
 */
class HelpersConfigTest extends TestCase
{

	/**
	 * @return void
	 * @test
	 */
	public function testConfigSetGet()
	{
		zbase_config_set('test_config', 'test_value');
		$this->assertEquals('test_value', zbase_config_get('test_config'));
	}

}
