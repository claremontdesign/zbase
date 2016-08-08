<?php

/**
 * Test class for {@see \Zbase\Models\View\Style}.
 * @covers \Zbase\Models\View\Style
 */
class StyleTest extends TestCase
{

	/**
	 * @return void
	 * @test
	 * @coversDefaultClass properties
	 */
	public function testGetProperties()
	{
		$config = [
			'id' => 'style',
			'style' => '#selector{display:block;}'
		];
		$view = new Zbase\Models\View\Style($config);
		$this->assertEquals('style', $view->id());
		$this->assertEquals('styleStyle', $view->getHtmlId());
	}

	/**
	 * @return void
	 * @test
	 * @coversDefaultClass __toString
	 */
	public function testToString()
	{
		$config = [
			'id' => 'style',
			'style' => '#selector{display:block;}'
		];
		$view = new Zbase\Models\View\Style($config);
		$this->assertEquals($view->__toString(), EOF . '<style type="text/css" id="styleStyle">' . EOF . '#selector{display:block;}' . EOF . '</style>' . EOF);
	}

	/**
	 * Test function associated with Script
	 *
	 * @return void
	 * @test
	 */
	public function testScriptViewFunctions()
	{
		$this->assertInstanceOf(Zbase\Models\View\Style::class, zbase_view_style_add('style', '#selector{display:block;}', null, []));
		$links = [
			'styleOne' => [
				'id' => 'style',
				'style' => '#selector{display:block;}'
			],
			'styleTwo' => [
				'id' => 'style',
				'style' => '#selector{display:block;}'
			],
			'styleThree' => [
				'id' => 'style',
				'style' => '#selector{display:block;}'
			],
			'styleFour' => [
				'id' => 'style',
				'style' => '#selector{display:block;}'
			],
		];
		$this->assertEquals(5, count(zbase_view_styles_set($links)));
		$this->assertInstanceOf(Zbase\Models\View\Style::class, zbase_view_style('styleThree'));
		$this->assertNotInstanceOf(Zbase\Models\View\Style::class, zbase_view_style('styleFive'));
		$this->assertTrue(zbase_view_style_has('styleThree'));
		$this->assertFalse(zbase_view_style_has('styleFive'));
		$this->assertEquals(5, count(zbase_view_styles($links)));
	}

}
