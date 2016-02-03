<?php

/**
 */
class HelpersViewTest extends TestCase
{

	/**
	 * @return void
	 * @test
	 */
	public function testzbase_view_plugin_load()
	{
		$config = [
			'plugins' => [
				'testMeta' => [
					'type' => \Zbase\Models\View::HEADMETA,
					'enable' => true,
					'name' => 'viewport',
					'content' => 'width=1020'
				],
			]
		];
		zbase_config_set('view', $config);
		$this->assertInstanceOf(Zbase\Interfaces\HtmlInterface::class, zbase_view_plugin_load('testMeta'));
	}

	/**
	 * @return void
	 * @test
	 */
	public function testzbase_view_template_layout()
	{
		// Returns default
		$this->assertEquals(zbase_view_template_layout(), 'zbase::templates.front.default.layout');

		// Returns a layout from other packages with a given theme name
		$plugin = [
			'template' => [
				'front' => [
					'package' => 'zbase',
					'theme' => 'default'
				],
			]
		];
		zbase_config_set('view', $plugin);
		// $this->assertEquals(zbase_view_template_layout(), 'someThemePackage::templates.front.someThemeName.layout');
		$this->assertEquals(zbase_view_template_layout(), 'zbase::templates.front.default.layout');

		// Return a different layout based on a given tag
		$plugin = [
			'template' => [
				'someTag' => [
					'front' => [
						'package' => 'someThemePackage',
						'theme' => 'someThemeName'
					],
				]
			]
		];
		zbase_config_set('view', $plugin);
		$this->assertEquals(zbase_view_template_layout('someTag'), 'zbase::templates.front.default.layout');
	}

	public function testzbase_view_file()
	{
		// Return the name
		$this->assertEquals('package::view.file', zbase_view_file('package::view.file'));
	}

}
