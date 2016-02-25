<?php

/**
 * Test class for {@see \Zbase\Ui\Ui}.
 * @covers \Zbase\Ui\Ui
 */
class UiTest extends TestCase
{

	/**
	 * @group Ui
	 */
	public function testEnabled()
	{
		$configuration = [
			'id' => 'testId',
			'enable' => false
		];
		$testClass = new \Zbase\Ui\Tab($configuration);
		$this->assertFalse($testClass->enabled());
	}

}
