<?php

/**
 * Test class for {@see \Zbase\Widgets\Widget}.
 * @covers \Zbase\Widgets\Widget
 */
class WidgetTest extends TestCase
{

	public function testWidget()
	{
		$config = [
			'enable' => true,
			'access' => 'guest',
			'type' => 'form'
		];
		$widget = new \Zbase\Widgets\Widget('widgetName', $config);
		$this->assertTrue($widget->enabled());
	}
}
