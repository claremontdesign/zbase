<?php

/**
 * Test class for {@see \Zbase\Models\View\Stylesheet}.
 * @covers \Zbase\Models\View\Stylesheet
 */
class StylesheetTest extends TestCase
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
			'href' => 'style.css',
			'html' => [
				'condition' => null,
				'attributes' => null
			]
		];
		$view = new Zbase\Models\View\Stylesheet($config);
		$this->assertEquals('style', $view->id());
		$this->assertEquals('stylesheet', $view->getRel());
		$this->assertEquals('style.css', $view->getHref());
		$this->assertEquals('stylesheetStyle', $view->getHtmlId());
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
			'href' => 'style.css',
			'html' => [
				'conditions' => null,
				'attributes' => null
			]
		];
		$view = new Zbase\Models\View\Stylesheet($config);
		$this->assertEquals($view->__toString(), '<link id="stylesheetStyle" href="style.css" rel="stylesheet" type="text/css" />');
	}

	/**
	 * Test __toString with conditions
	 *
	 * @return void
	 * @test
	 * @coversDefaultClass __toString
	 */
	public function testToStringWithConditions()
	{
		$config = [
			'id' => 'style',
			'href' => 'style.css',
			'html' => [
				'conditions' => 'lte IE 8',
				'attributes' => null
			]
		];
		$view = new Zbase\Models\View\Stylesheet($config);
		$this->assertEquals($view->__toString(), '<!--[lte IE 8]><link id="stylesheetStyle" href="style.css" rel="stylesheet" type="text/css" /><![endif]-->');
	}

	/**
	 * Test function associated with Stylesheet
	 *
	 * @return void
	 * @test
	 */
	public function testStylesheetViewFunctions()
	{
		$this->assertInstanceOf(Zbase\Models\View\Stylesheet::class, zbase_view_stylesheet_add('style', 'style.css', 'lte IE 8', []));
		$links = [
			'linkOne' => [
				'rel' => 'stylesheet',
				'href' => 'styleOne.css'
			],
			'linkTwo' => [
				'rel' => 'stylesheet',
				'href' => 'styleTwo.css'
			],
			'linkThree' => [
				'rel' => 'stylesheet',
				'href' => 'styleThree.css'
			],
			'linkFour' => [
				'rel' => 'stylesheet',
				'href' => 'styleFour.css'
			],
		];
		$this->assertEquals(5, count(zbase_view_stylesheets_set($links)));
		$this->assertInstanceOf(Zbase\Models\View\Stylesheet::class, zbase_view_stylesheet('linkThree'));
		$this->assertNotInstanceOf(Zbase\Models\View\Stylesheet::class, zbase_view_stylesheet('linkFive'));
		$this->assertTrue(zbase_view_stylesheet_has('linkThree'));
		$this->assertFalse(zbase_view_stylesheet_has('linkFive'));
		$this->assertEquals(5, count(zbase_view_stylesheets($links)));
	}

}
