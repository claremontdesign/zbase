<?php

/**
 */
class AuthControllerTest extends TestCase
{

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
		$user = zbase_entity('user')->find(1);
		$this->actingAs($user)
				->visit('/login')
				->seePageIs(zbase_url_from_route('home'));
	}
//
//	function testRegisterPage()
//	{
//		dd($this->visit('/register'));
//		$this->visit('/register')
//				->type('Taylor', 'name')
//				->type('theUsername', 'username')
//				->type('taylor@zbase.com', 'email')
//				->type('password', 'password')
//				->type('password', 'password_confirmation')
//				->press('Register')
//				->seePageIs('/home');
//	}

}
