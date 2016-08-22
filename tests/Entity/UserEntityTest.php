<?php

/**
 * Test class for {@see \Zbase\Entity\Laravel\Entity\User\User}.
 * @covers \Zbase\Entity\Laravel\Entity\User\User
 */
class UserEntityTest extends TestCase
{

	/**
	 * @group entity
	 * @group Userentity
	 */
	public function testHasAccess()
	{
		$user = zbase_entity('user')->repository()->by('username', 'adminx')->first();
		$this->assertTrue($user->hasAccess('admin'));
		$this->assertTrue($user->hasAccess('user'));
		$this->assertFalse($user->hasAccess('sudo'));
	}

	/**
	 * @group entity
	 * @group Userentity
	 */
	public function testUpdateEmailAddress()
	{
		$user = zbase_entity('user')->repository()->by('username', 'adminx')->first();
		$user->email = 'admin@zbase.com';
		$user->unsetAllOptions();
		$user->save();
		zbase_alerts_reset();
		$newEmailAddress = 'new-email-address@test.com';
		$user->updateRequestEmailAddress($newEmailAddress);
		$this->assertSame($user->getDataOption('email_new', null), $newEmailAddress);
		$this->assertTrue(zbase_alerts_has('info'));
		$this->assertFalse($user->checkEmailRequestUpdate('someFalseCodes'));
		$requestCode = $user->getDataOption('email_updaterequest_code');
		$this->assertTrue($user->checkEmailRequestUpdate($requestCode));
		$this->assertTrue(is_null($user->getDataOption('email_updaterequest_code')));
		$verificationCode = $user->getDataOption('email_verification_code', null);
		$this->assertFalse(is_null($verificationCode));
		$this->assertTrue($user->verifyEmailAddress($verificationCode));
		$user->email = 'admin@zbase.com';
		$user->unsetAllOptions();
		$user->save();
	}

	/**
	 * @group entity
	 * @group Userentity
	 */
	public function testUpdatePassword()
	{
		$user = zbase_entity('user')->repository()->by('username', 'adminx')->first();
		$user->password = zbase_bcrypt('password');
		$user->unsetAllOptions();
		$user->save();
		zbase_alerts_reset();
		$newPassword = 'abc12345678';
		$user->updateRequestPassword($newPassword);
		$this->assertTrue(zbase_alerts_has('info'));
		$this->assertFalse(empty($user->getDataOption('password_update_code', [])));
		$user->updatePassword($newPassword);
		$this->assertTrue(zbase_bcrypt_check($newPassword, $user->password));
		$user->password = zbase_bcrypt('password');
		$user->unsetAllOptions();
		$user->save();
	}

}
