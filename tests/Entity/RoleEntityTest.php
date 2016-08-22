<?php

/**
 * C:\WebDevelopment\wamp64\www\zbase\vendor\bin\phpunit.bat "--colors" "--log-junit" "C:\Users\Dennes\AppData\Local\Temp\nb-phpunit-log.xml" "--configuration" "C:\WebDevelopment\wamp64\www\zbase\packages\dennesabing\zbase\phpunit.xml" "--group" "role" "C:\Program Files\NetBeans 8.1\php\phpunit\NetBeansSuite.php" "--" "--run=C:\WebDevelopment\wamp64\www\zbase\packages\dennesabing\zbase\tests
 * Test class for {@see \Zbase\Entity\Laravel\Entity\User\Role}.
 * @covers \Zbase\Entity\Laravel\Entity\User\Role
 */
class RoleEntityTest extends TestCase
{

	/**
	 * @group entity
	 * @group role
	 */
	public function testRoleEntityIsRole()
	{
		$roleEntity = zbase_entity('user_roles');
		$this->assertTrue($roleEntity instanceof \Zbase\Entity\Laravel\User\Role);
	}

	/**
	 * @group entity
	 * @group role
	 */
	public function testListAllRolesIsCached()
	{
		$cacheKey = zbase_cache_key(zbase_entity('user_roles'), 'listAllRoles');
		$roles = zbase_entity('user_roles')->listAllRoles();
		$this->assertTrue(is_array($roles));
		$this->assertTrue(zbase_cache_has($cacheKey, [zbase_entity('user_roles')->getTable()], ['driver' => 'file']));
	}

	/**
	 * @group entity
	 * @group role
	 */
	public function testRoleChildren()
	{
		$entity = zbase_entity('user_roles');
		$role = $entity->repo()->by('role_name', 'user')->first();
		$childrens = $role->above();
		$hasChild = false;
		foreach($childrens as $children)
		{
			if($children->name() == 'admin')
			{
				$hasChild = true;
			}
		}
		$this->assertTrue($hasChild);
	}

	/**
	 * @group entity
	 * @group role
	 */
	public function testAccesses()
	{
		$user = zbase_entity('user')->repo()->by('username', 'adminx')->first();
		$this->assertTrue($user->hasAccess('user'));
		$this->assertTrue($user->hasAccess('only::admin'));
		$this->assertFalse($user->hasAccess('only::user'));
		$this->assertTrue($user->hasAccess('below::sudo'));
		$this->assertFalse($user->hasAccess('same::sudo'));
		$this->assertFalse($user->hasAccess('above::admin'));
		$this->assertTrue($user->hasAccess('only::admin,only::user'));
		$this->assertFalse($user->hasAccess('only::sudo,only::user'));
	}

}
