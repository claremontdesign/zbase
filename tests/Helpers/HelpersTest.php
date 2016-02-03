<?php

/**
 */
class HelpersTest extends TestCase
{

	/**
	 * @return void
	 * @test
	 */
	public function testzbase_data_get()
	{
		$config = [
			'key' => [
				'keyTwo' => [
					'keyThree' => [
						'keyFour' => 'keyFourValue'
					]
				]
			]
		];
		$this->assertEquals('keyFourValue', zbase_data_get($config, 'key.keyTwo.keyThree.keyFour'));
		$this->assertSame(['keyThree' => ['keyFour' => 'keyFourValue']], zbase_data_get($config, 'key.keyTwo'));

		// Test configReplace
		$arrOne = [
			'template' => [
				'someTag' => [
					'configReplace' => 'view.template.otherTag',
					'front' => [
						'package' => 'someThemePackage',
						'theme' => 'someThemeName'
					],
				],
				'otherTag' => [
					'front' => [
						'package' => 'someOtherTagThemePackage',
						'theme' => 'someOtherTagThemeName'
					],
				],
			],
		];
		zbase_config_set('view', $arrOne);
		$expected = [
			'front' => [
				"package" => "someOtherTagThemePackage",
				"theme" => "someOtherTagThemeName"
			]
		];
		$this->assertSame($expected, zbase_config_get('view.template.someTag'));

		// Test configMerge
		$arrOne = [
			'template' => [
				'someTag' => [
					'configMerge' => 'view.template.otherTag',
					'front' => [
						'package' => 'someThemePackage',
						'theme' => 'someThemeName'
					],
				],
				'otherTag' => [
					'front' => [
						'package' => 'someOtherTagThemePackage',
						'theme' => 'someOtherTagThemeName'
					],
				],
			],
		];
		zbase_config_set('view', $arrOne);
		$expected = [
			'front' => [
				"package" => [
					"someThemePackage",
					"someOtherTagThemePackage"
				],
				"theme" => [
					"someThemeName",
					"someOtherTagThemeName"
				]
			]
		];
		$this->assertSame($expected, zbase_config_get('view.template.someTag'));

		// Test inheritedValue
		$arrOne = [
			'template' => [
				'someTag' => [
					'front' => [
						'package' => 'someThemePackage',
						'theme' => 'inheritValue::view.template.otherTag.front.theme'
					],
				],
				'otherTag' => [
					'front' => [
						'package' => 'someOtherTagThemePackage',
						'theme' => 'someOtherTagThemeName'
					],
				],
			],
		];
		zbase_config_set('view', $arrOne);
		$this->assertSame('someOtherTagThemeName', zbase_config_get('view.template.someTag.front.theme'));
	}

	/**
	 * @return void
	 * @test
	 */
	public function testValueGet()
	{
		/**
		 * Check can return \Closure value
		 */
		$closure = function(){
			return 8 * 4;
		};
		$this->assertEquals(8 * 4, zbase_value_get($closure));

		/**
		 * Check can return value of dot-notated key
		 */
		$config = [
			'key' => [
				'keyTwo' => [
					'keyThree' => [
						'keyFour' => 'keyFourValue',
						'keyFive' => $closure,
					]
				]
			]
		];
		$this->assertEquals('keyFourValue', zbase_value_get($config, 'key.keyTwo.keyThree.keyFour'));
		$this->assertEquals(8 * 4, zbase_value_get($config, 'key.keyTwo.keyThree.keyFive'));
		$this->assertSame(['keyThree' => ['keyFour' => 'keyFourValue', 'keyFive' => $closure]], zbase_value_get($config, 'key.keyTwo'));
	}

}
