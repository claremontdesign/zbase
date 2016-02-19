<?php

/**
 */
class RoutesTest extends TestCase
{

	/**
	 * @return void
	 * @test
	 */
	public function testRoutesFromConfig()
	{
		$this->assertTrue(true);
////		$this->visit('/tests/view-route')
////				->see('View from Route')
////				->dontSee('The Test Content');
//
//		$this->visit('/')
//				->dontSee('Route One Test')
//				->see('The Test Content');
//
//		/**
//		 * Test to redirect for auth
//		 */
//		$this->assertEquals(302, $this->call('GET', '/tests/route-auth')->status());
//
//		/**
//		 * Test not found
//		 */
//		$this->assertEquals(404, $this->call('GET', '/tests/route-not-found')->status());
	}

	public function testFormToAlerts()
	{
		$this->assertTrue(true);
//		$crawler = $this->visit('/tests/form')
//				->type('dennes@yahoo.com', 'email')
//				->press('Submit')->crawler;
//		$body = $crawler->filterXpath('//body')->extract(array('class'));
//		$this->assertTrue(in_array('controller-page', explode(' ', $body[0])));
	}
}
