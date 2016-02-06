<?php

namespace Zbase\Traits;

/**
 * Zbase-Faker
 * https://github.com/fzaninotto/Faker
 *
 * Reusable Methods Faker
 *
 * @link http://zbase.dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2016 ClaremontDesign/MadLabs-Dx
 * @file Faker.php
 * @project Zbase
 * @package Zbase/Traits
 */
trait Faker
{

	/**
	 * Return a Faker data
	 * @return mixed
	 */
	public function faker()
	{
		$valueMap = $this->getValueMap();
		if(!empty($valueMap))
		{
			$valueIndex = rand(0, count($valueMap) - 1);
			$valueCounter = 0;
			foreach ($valueMap as $value => $label)
			{
				if($valueIndex == $valueCounter)
				{
					return $value;
				}
				$valueCounter++;
			}
		}
		return self::f($this->getType(), $this->getSubType(), $this->getLength());
	}

	/**
	 *
	 * @param type $type
	 * @param type $subType
	 * @return type
	 */
	public static function f($type, $subType = 'string', $length = 64)
	{
		$faker = \Faker\Factory::create();
		switch (strtolower($type))
		{
			case 'string':
				switch (strtolower($subType))
				{
					case 'username':
						return $faker->userName();
					case 'personfullname':
						return $faker->name();
					case 'persondisplayname':
						return $faker->name();
					case 'personfirstname':
						return $faker->firstName();
					case 'personlastname':
						return $faker->lastName();
					case 'personmiddlename':
						return $faker->lastName();
					case 'email':
						return $faker->email();
					case 'alphaid':
						return alphaID(time());
					case 'password':
						return bcrypt('password');
					case 'avatarurl':
						return 'http://api.adorable.io/avatars/285/' . $faker->email() . '.png';
					default;
						return $faker->text($length);
				}
				break;
			case 'tinyint':
				return rand(1, 16);
			case 'boolean':
				return rand(0, 1);
			case 'timestamp':
				return $faker->dateTime('now');
			default;
		}
	}

}
