<?php

/**
 */
class AuthControllerTest extends TestCase
{

	/**
	 * @group Urls
	 * @group Auth
	 */
	function testLoginPage()
	{
		/**
		 * Test that guest cannot access member area or authed areas
		 * Test redirected to login page
		 */
		$this->visit(zbase_url_from_route('home'))->seePageIs(zbase_url_from_route('login'));
		$this->assertEquals(302, $this->call('GET', zbase_url_from_route('home'))->status());

		/**
		 * Test if authed user redirected to home when accessing login page
		 */
//		$user = zbase_entity('user')->find(1);
//		$this->actingAs($user)
//				->visit('/login')
//				->seePageIs(zbase_url_from_route('home'));
	}

	/**
	 * @group Urls
	 * @group Auth
	 */
	function testBackendLoginPage()
	{
		/**
		 * Test that guest cannot access member area or authed areas
		 * Test redirected to login page
		 */
		$this->visit(zbase_url_from_route('admin'))->seePageIs(zbase_url_from_route('admin.login'));
		$this->assertEquals(302, $this->call('GET', zbase_url_from_route('admin'))->status());
	}

}
