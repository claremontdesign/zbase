<?php

/**
 * Test class for {@see \Zbase\Ui\Tabs}.
 * @covers \Zbase\Ui\Tabs
 */
class TabTest extends TestCase
{

	/**
	 * @group Ui
	 */
	public function testTabs()
	{
		$tabOneConfiguration = [
			'type' => 'tab',
			'label' => 'Tab One',
			'id' => 'tabOne',
			'group' => 'ExampleTab',
			'position' => 100,
			'contents' => [
				[
					'id' => 'contentId',
					'enable' => true,
					'content' => 'The Content of Tab One',
				],
				[
					'id' => 'contentTwoId',
					'enable' => true,
					'content' => 'The Second Content of Tab One',
				],
				[
					'id' => 'contentThreeId',
					'enable' => true,
					'content' => 'The third Content of Tab One',
				],
			],
		];
		$tabTwoConfiguration = [
			'type' => 'tab',
			'label' => 'Tab Two',
			'id' => 'tabTwo',
			'group' => 'ExampleTab',
			'contents' => [
				[
					'id' => 'contentId',
					'enable' => true,
					'content' => 'The Content of Tab Two',
				],
				[
					'id' => 'contentTwoId',
					'enable' => true,
					'content' => 'The Second Content of Tab Two',
				],
				[
					'id' => 'contentThreeId',
					'enable' => true,
					'content' => 'The third Content of Tab Two',
				],
			],
		];
		$tabThreeConfiguration = [
			'type' => 'tab',
			'label' => 'Tab Three',
			'id' => 'tabThree',
			'group' => 'ExampleTab',
			'contents' => [
				[
					'id' => 'contentId',
					'enable' => true,
					'content' => 'The Content of Tab Three',
				],
				[
					'id' => 'contentTwoId',
					'enable' => true,
					'content' => 'The Second Content of Tab Three',
				],
				[
					'id' => 'contentThreeId',
					'enable' => true,
					'content' => 'The third Content of Tab Three',
				],
			],
		];

		$elementRoute = [
			'view' => [
				'name' => zbase_tag() . 'test::contents.test.content',
				'enable' => true,
				'content' => function() use($tabOneConfiguration, $tabTwoConfiguration, $tabThreeConfiguration){
					return zbase_ui_tabs([$tabOneConfiguration, $tabTwoConfiguration, $tabThreeConfiguration]);
				},
			],
			'url' => '/test/ui-tab',
			'enable' => true
		];
		zbase_route_init('uiTest', $elementRoute);
		$this->visit('/test/ui-tab')->see('<a data-toggle="tab" href="#ExampleTabtabOne">Tab One</a>');

	}

}
