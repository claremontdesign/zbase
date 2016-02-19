<?php

/**
 * Test class for {@see \Zbase\Entity\Laravel\Entity\User\User}.
 * @covers \Zbase\Entity\Laravel\Entity\User\User
 */
class UserEntityTest extends TestCase
{

	public function testHasAccess()
	{
		$user = zbase_entity('user')->repository()->by('username', 'admin')->first();
		$this->assertTrue($user->hasAccess('admin'));
		$this->assertTrue($user->hasAccess('user'));
		$this->assertFalse($user->hasAccess('sudo'));
	}

}
