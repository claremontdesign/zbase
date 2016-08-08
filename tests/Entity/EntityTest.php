<?php

/**
 * Test class for {@see \Zbase\Entity\Laravel\Entity}.
 * @covers \Zbase\Entity\Laravel\Entity
 */
class EntityTest extends TestCase
{

	/**
	 * @group entity
	 */
	public function testEntityInstance()
	{
		$user = \DB::table('users')->where('user_id', 2)->first();
		$userProfile = \DB::table('users_profile')->where('user_id', 2)->first();
		$roles = \DB::table('users_roles')->where('user_id', 2)->get();

		$model = zbase_entity('user');
		$this->assertTrue($model->find(2) instanceof \Zbase\Entity\Laravel\User\User);
		$this->assertTrue($user->email == $model->find(2)->email);

		/**
		 * Test Dynamic Call to relationship
		 */
		/**
		 * OneToOne
		 */
		$this->assertTrue($model->find(2)->profile() instanceof \Zbase\Entity\Laravel\User\UserProfile);
		$this->assertTrue($model->find(2)->profile()->first_name == $userProfile->first_name);
		$this->assertTrue($model->find(2)->profile()->user()->password == $user->password);
		/**
		 * ManyToMany
		 */
		$this->assertTrue(count($model->find(2)->roles()) == count($roles));
		$this->assertTrue($model->find(2)->roles()->first()->id() == $roles[0]->role_id);
	}

	/**
	 * @group entity
	 */
	public function testRepositoryById()
	{
		$user = zbase_entity('user')->repository()->by('username', 'admin')->first();
		$user->email = 'admin@zbase.com';
		$user->unsetAllOptions();
		$user->save();
		$model = zbase_entity('user');
		$this->assertTrue($model->repository() instanceof \Zbase\Interfaces\EntityRepositoryInterface);

		$user = \DB::table('users')->where('user_id', 2)->first();
		/**
		 * By Id
		 */
		$this->assertTrue($model->repository()->byId(2)->password == $user->password);
		$this->assertTrue($model->repository()->byId(2, ['user_id'])->password == null);
		$this->assertTrue($model->repository()->byId(2, ['user_id'])->user_id == 2);
	}

	/**
	 * @group entity
	 */
	public function testRepositoryAll()
	{
		$model = zbase_entity('user');
		$user = \DB::table('users');
		$this->assertTrue(count($model->repository()->withTrashed()->all()) == count($user->get()));

		/**
		 * Filters
		 */
		$userFilter = \DB::table('users')->where('user_id', 5)->first();
		$filter = [
			'user_id' => 5
		];
		$this->assertTrue($model->repository()->all(['*'], $filter)->first()->password == $userFilter->password);

		$userFilter = \DB::table('users')->where('user_id', 6)->first();
		$filter = [
			'user_id' => [
				'eq' => [
					'field' => 'user_id',
					'value' => 6
				],
			],
			'email' => [
				'eq' => [
					'field' => 'email',
					'value' => $userFilter->email
				],
			],
		];
		$this->assertTrue($model->repository()->all(['*'], $filter)->first()->password == $userFilter->password);

		/**
		 * Sorting
		 */
		$userFilter = \DB::table('users')->where('email_verified', 1)->orderBy('user_id', 'desc')->get();
		$filter = [
			'email_verified' => [
				'eq' => [
					'field' => 'email_verified',
					'value' => 1
				],
			],
		];
		$sort = ['user_id' => 'desc'];
		$this->assertTrue($model->repository()->all(['*'], $filter, $sort)->first()->password == $userFilter[0]->password);

		/**
		 * Joins
		 */
		$users = DB::table('users')
				->join('users_profile', 'users.user_id', '=', 'users_profile.user_id')
				->select('users.password')
				->orderBy('users.user_id', 'desc')
				->get();
		$filter = [];
		$sort = ['users.user_id' => 'desc'];
		$joins = [
			[
				'type' => 'join',
				'model' => 'users_profile as users_profile',
				'foreign_key' => 'users_profile.user_id',
				'local_key' => 'users.user_id'
			]
		];
		$this->assertTrue($model->repository()->all(['users.password'], $filter, $sort, $joins)->first()->password == $users[0]->password);
	}

}
