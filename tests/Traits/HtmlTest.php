<?php

/**
 * Test class for {@see \Zbase\Traits\Html}.
 * @covers \Zbase\Traits\Html
 */
class HtmlTest extends TestCase
{

	private $traitObject;

	/**
	 * @return void
	 */
	public function setUp()
	{
		$this->traitObject = $this->createObjectForTrait();
	}

	/**
	 * Create an object based on Trait
	 * @return object
	 */
	private function createObjectForTrait()
	{
		$traitName = \Zbase\Traits\Html::class;
		return $this->getObjectForTrait($traitName);
	}

	/**
	 * @return void
	 * @test
	 * @coversDefaultClass renderHtmlAttributes
	 */
	public function testRenderHtmlAttributes()
	{
		$htmlAttributes = [
			'data-test' => 'dataTestValue',
			'rel' => 'stylesheet'
		];
		$this->traitObject->setHtmlAttributes($htmlAttributes);
		$expected = 'data-test="dataTestValue" rel="stylesheet"';
		$actual = $this->traitObject->renderHtmlAttributes();
		$this->assertEquals($expected, $actual);
	}

	/**
	 * @return void
	 * @test
	 * @coversDefaultClass getHtmlAttributes
	 */
	public function testGetHtmlAttributes()
	{
		$this->assertEquals(0, count($this->traitObject->getHtmlAttributes()));
	}

	/**
	 * @return void
	 * @test
	 * @coversDefaultClass addHtmlAttribute
	 */
	public function testAddHtmlAttributes()
	{
		$this->traitObject->addHtmlAttribute('data-rel','dataRelValue');
		$expected = 'data-rel="dataRelValue"';
		$actual = $this->traitObject->renderHtmlAttributes();
		$this->assertEquals($expected, $actual);
	}

}
