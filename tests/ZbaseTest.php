<?php

/**
 * Test class for {@see \Zbase\Zbase}.
 * @covers \Zbase\Zbase
 */
class ZbaseTest extends TestCase
{

	/**
	 * @return void
	 * @test
	 * @coversDefaultClass view
	 */
	public function testViewReturnsViewModel()
	{
		$zbase = new Zbase\Zbase;
		$this->assertInstanceOf('\Zbase\Models\View', $zbase->view());
	}

	/**
	 * Test DB Connection established
	 */
	public function testDbConnection()
	{
		$this->assertTrue(DB::connection() instanceof Illuminate\Database\MySqlConnection);
	}

	/**
	 * Test table was created based from config
	 * @group entity
	 */
	public function testTableWasCreatedFromConfig()
	{
		$this->assertTrue(Schema::hasTable('users'));
		$this->assertTrue(Schema::hasTable('users_profile'));
		$this->assertTrue(Schema::hasColumn('users', 'email'));
		$this->assertTrue(Schema::hasColumn('users_profile', 'first_name'));
	}

	/**
	 * Test factory was generated automatically
	 */
//	public function testDbFactoryFromConfig()
//	{
//		$model = zbase_entity('user');
//		$this->assertSame(zbase_config_get('entity.user.data.factory.rows') + count(zbase_config_get('entity.user.data.defaults')), count($model->all()));
//	}

	/**
	 * Test that entity model can be created
	 *
	 * @coversDefaultClass entity
	 */
	public function testEntityModelCanBeCreated()
	{
		$this->assertTrue(zbase_entity('user') instanceof \Zbase\Entity\Laravel\Entity);
		$this->assertTrue(zbase_entity('user') instanceof \Zbase\Entity\Laravel\User\User);
		/**
		 * Dynamic creation of entity, default to \Zbase\Entity\Laravel\Entity
		 */
		$this->assertTrue(zbase_entity('user_profile') instanceof \Zbase\Entity\Laravel\Entity);
	}

	/**
	 * Test if we can set a configuration
	 * at runtime
	 */
	public function testConfigSetGet()
	{
		$commandTest = [
			'url' => '/tests/commandtest',
			'enable' => true
		];
		zbase_config_set('routes.commandtest', $commandTest);
		$this->assertTrue(zbase_config_get('routes.commandtest.enable'));
	}

}
