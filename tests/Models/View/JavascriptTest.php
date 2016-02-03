<?php

/**
 * Test class for {@see \Zbase\Models\View\Javascript}.
 * @covers \Zbase\Models\View\Javascript
 */
class JavascriptTest extends TestCase
{

	/**
	 * @return void
	 * @test
	 * @coversDefaultClass properties
	 */
	public function testGetProperties()
	{
		$config = [
			'id' => 'script',
			'src' => 'script.js',
			'html' => [
				'condition' => null,
				'attributes' => null
			]
		];
		$view = new Zbase\Models\View\Javascript($config);
		$this->assertEquals('script', $view->id());
		$this->assertEquals('script.js', $view->getSrc());
		$this->assertEquals('javascript-script', $view->getHtmlId());
	}

	/**
	 * @return void
	 * @test
	 * @coversDefaultClass __toString
	 */
	public function testToString()
	{
		$config = [
			'id' => 'script',
			'src' => 'script.js',
			'html' => [
				'condition' => null,
				'attributes' => null
			]
		];
		$view = new Zbase\Models\View\Javascript($config);
		$this->assertEquals($view->__toString(), '<script id="javascript-script" type="text/javascript" src="script.js"></script>');
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
			'id' => 'script',
			'src' => 'script.js',
			'html' => [
				'conditions' => 'lte IE 8',
				'attributes' => null
			]
		];
		$view = new Zbase\Models\View\Javascript($config);
		$this->assertEquals($view->__toString(), '<!--[lte IE 8]><script id="javascript-script" type="text/javascript" src="script.js"></script><![endif]-->');
	}

	/**
	 * Test function associated with Stylesheet
	 *
	 * @return void
	 * @test
	 */
	public function testStylesheetViewFunctions()
	{
		$this->assertInstanceOf(Zbase\Models\View\Javascript::class, zbase_view_javascript_add('script', 'script.js', 'lte IE 8', []));
		$links = [
			'scriptOne' => [
				'src' => 'scriptOne.js',
			],
			'scriptTwo' => [
				'src' => 'scriptTwo.js',
			],
			'scriptThree' => [
				'src' => 'scriptThree.js',
			],
			'scriptFour' => [
				'src' => 'scriptFour.js',
			],
		];
		$this->assertEquals(5, count(zbase_view_javascripts_set($links)));
		$this->assertInstanceOf(Zbase\Models\View\Javascript::class, zbase_view_javascript('scriptThree'));
		$this->assertNotInstanceOf(Zbase\Models\View\Javascript::class, zbase_view_javascript('scriptFive'));
		$this->assertTrue(zbase_view_javascript_has('scriptThree'));
		$this->assertFalse(zbase_view_javascript_has('scriptFive'));
		$this->assertEquals(5, count(zbase_view_javascripts($links)));
	}

}
