<?php

/**
 */
class RoutesTest extends TestCase
{
	/**
	 * @return void
	 * @test
	 * @group route
	 */
	public function testRoutesFromConfig()
	{
		$this->visit('/tests/view-route')
				->see('View from Route')
				->dontSee('The Test Content');

	}

}
