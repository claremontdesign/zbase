<?php

/**
 * Test class for {@see \Zbase\Models\View\HeadLink}.
 * @covers \Zbase\Models\View\HeadLink
 */
class HeadLinkTest extends TestCase
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
			'rel' => 'stylesheet',
			'href' => 'style.css',
			'html' => [
				'condition' => null,
				'attributes' => null
			]
		];
		$view = new Zbase\Models\View\HeadLink($config);
		$this->assertEquals(zbase_string_camel_case('style'), $view->id());
		$this->assertEquals(zbase_string_camel_case('stylesheet'), $view->getRel());
		$this->assertEquals(zbase_string_camel_case('style.css'), $view->getHref());
		$this->assertEquals(zbase_string_camel_case('headlink-style'), $view->getHtmlId());
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
			'rel' => 'stylesheet',
			'href' => 'style.css',
			'html' => [
				'conditions' => null,
				'attributes' => null
			]
		];
		$view = new Zbase\Models\View\HeadLink($config);
		$this->assertEquals($view->__toString(), '<link id="' . zbase_string_camel_case('headlink-style') . '" href="style.css" rel="stylesheet" type="text/css" />');
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
			'rel' => 'stylesheet',
			'href' => 'style.css',
			'html' => [
				'conditions' => 'lte IE 8',
				'attributes' => null
			]
		];
		$view = new Zbase\Models\View\HeadLink($config);
		$this->assertEquals($view->__toString(), '<!--[lte IE 8]><link id="' . zbase_string_camel_case('headlink-style') . '" href="style.css" rel="stylesheet" type="text/css" /><![endif]-->');
	}

	/**
	 * Test function associated with HeadLink
	 *
	 * @return void
	 * @test
	 */
	public function testHeadLinkViewFunctions()
	{
		$this->assertInstanceOf(Zbase\Models\View\HeadLink::class, zbase_view_head_link_add('style', 'style.css', 'stylesheet', 'lte IE 8', []));
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
		$this->assertEquals(5, count(zbase_view_head_links_set($links)));
		$this->assertInstanceOf(Zbase\Models\View\HeadLink::class, zbase_view_head_link('linkThree'));
		$this->assertNotInstanceOf(Zbase\Models\View\HeadLink::class, zbase_view_head_link('linkFive'));
		$this->assertTrue(zbase_view_head_link_has('linkThree'));
		$this->assertFalse(zbase_view_head_link_has('linkFive'));
		$this->assertEquals(5, count(zbase_view_head_links($links)));
	}
}
