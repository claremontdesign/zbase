<?php

/**
 * Test class for {@see \Zbase\Models\View\HeadMeta}.
 * @covers \Zbase\Models\View\HeadMeta
 */
class HeadMetaTest extends TestCase
{

	/**
	 * @return void
	 * @test
	 * @coversDefaultClass getContent
	 */
	public function testGetContent()
	{
		$config = [
			'id' => 'viewport',
			'content' => 'width=1020',
			'name' => 'viewport',
			'html' => [
				'condition' => null,
				'attributes' => null
			]
		];
		$view = new Zbase\Models\View\HeadMeta($config);
		$this->assertEquals($view->getContent(), 'width=1020');
		$this->assertEquals($view, '<meta name="viewport" content="width=1020" />');
	}

	/**
	 * @return void
	 * @test
	 * @coversDefaultClass setContent
	 */
	public function testSetContent()
	{
		$config = [
			'id' => 'viewport',
			'content' => 'width=1020',
			'name' => 'viewport'
		];
		$view = new Zbase\Models\View\HeadMeta($config);
		$view->setContent('width=1050');
		$this->assertEquals($view->__toString(), '<meta name="viewport" content="width=1050" />');
	}

	/**
	 * @return void
	 * @test
	 * @coversDefaultClass __toString
	 */
	public function testToString()
	{
		$config = [
			'id' => 'viewport',
			'content' => 'width=1020',
			'name' => 'viewport',
			'html' => [
				'conditions' => '',
				'attributes' => []
			]
		];
		$view = new Zbase\Models\View\HeadMeta($config);
		$this->assertEquals($view->__toString(), '<meta name="viewport" content="width=1020" />');
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
			'id' => 'viewport',
			'content' => 'width=1020',
			'name' => 'viewport',
			'html' => [
				'conditions' => 'lte IE 8',
				'attributes' => ['http-equiv' => 'Content-Language']
			]
		];
		$view = new Zbase\Models\View\HeadMeta($config);
		$this->assertEquals($view->__toString(), '<!--[lte IE 8]><meta name="viewport" content="width=1020" http-equiv="Content-Language"/><![endif]-->');
	}

	/**
	 * Test function associated with HeadMeta
	 *
	 * @return void
	 * @test
	 */
	public function testHeadMetaViewFunctions()
	{
		zbase_config_set('view.autoload.plugins',[]);
		$this->assertInstanceOf(Zbase\Models\View\HeadMeta::class, zbase_view_head_meta_add('viewport', 'width=1020', null, null, ['http-equiv' => 'Content-Language']));
		$links = [
			'linkOne' => [
				'content' => 'width=1020',
				'name' => 'viewport'
			],
			'linkTwo' => [
				'content' => 'width=1020',
				'name' => 'viewport'
			],
			'linkThree' => [
				'content' => 'width=1020',
				'name' => 'viewport'
			],
			'linkFour' => [
				'content' => 'width=1020',
				'name' => 'viewport'
			],
		];
		$this->assertEquals(7, count(zbase_view_head_metas_set($links)));
		$this->assertInstanceOf(Zbase\Models\View\HeadMeta::class, zbase_view_head_meta('linkThree'));
		$this->assertNotInstanceOf(Zbase\Models\View\HeadMeta::class, zbase_view_head_meta('linkFive'));
		$this->assertTrue(zbase_view_head_meta_has('linkThree'));
		$this->assertFalse(zbase_view_head_meta_has('linkFive'));
		$this->assertEquals(7, count(zbase_view_head_metas($links)));
	}

}
