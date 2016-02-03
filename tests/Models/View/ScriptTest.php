<?php

/**
 * Test class for {@see \Zbase\Models\View\Script}.
 * @covers \Zbase\Models\View\Script
 */
class ScriptTest extends TestCase
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
			'script' => 'function(){ console.log(var); }',
			'onLoad' => true
		];
		$view = new Zbase\Models\View\Script($config);
		$this->assertEquals('script', $view->id());
		$this->assertTrue($view->getOnLoad());
		$this->assertEquals('script-script', $view->getHtmlId());
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
			'script' => 'function(){ console.log(var); }',
			'onLoad' => true
		];
		$view = new Zbase\Models\View\Script($config);
		$this->assertEquals($view->__toString(), EOF . '<script type="text/javascript" id="script-script">' . EOF . 'function(){ console.log(var); }' . EOF . '</script>' . EOF);
	}

	/**
	 * Test function associated with Script
	 *
	 * @return void
	 * @test
	 */
	public function testScriptViewFunctions()
	{
		$this->assertInstanceOf(Zbase\Models\View\Script::class, zbase_view_script_add('script', 'function(){ console.log(var); }', true, null, []));
		$links = [
			'scriptOne' => [
				'id' => 'script',
				'script' => 'function(){ console.log(var); }',
				'onLoad' => true
			],
			'scriptTwo' => [
				'id' => 'script',
				'script' => 'function(){ console.log(var); }',
				'onLoad' => true
			],
			'scriptThree' => [
				'id' => 'script',
				'script' => 'function(){ console.log(var); }',
				'onLoad' => true
			],
			'scriptFour' => [
				'id' => 'script',
				'script' => 'function(){ console.log(var); }',
				'onLoad' => true
			],
		];
		$this->assertEquals(5, count(zbase_view_scripts_set($links)));
		$this->assertInstanceOf(Zbase\Models\View\Script::class, zbase_view_script('scriptThree'));
		$this->assertNotInstanceOf(Zbase\Models\View\Script::class, zbase_view_script('scriptFive'));
		$this->assertTrue(zbase_view_script_has('scriptThree'));
		$this->assertFalse(zbase_view_script_has('scriptFive'));
		$this->assertEquals(5, count(zbase_view_scripts($links)));
	}

}
