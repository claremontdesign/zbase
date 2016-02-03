<?php

/**
 * Test class for {@see \Zbase\Traits\Attribute}.
 * @covers \Zbase\Traits\Attribute
 */
class AttributeTest extends TestCase
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
		$traitName = \Zbase\Traits\Attribute::class;
		return $this->getObjectForTrait($traitName);
	}

	/**
	 * @return void
	 * @test
	 * @coversDefaultClass setAttribute
	 * @coversDefaultClass getAttributes
	 */
	public function testSetAttributeReturnsSame()
	{
		$name = 'attributeName';
		$value = 'attributeValue';
		$this->traitObject->setAttribute($name, $value);
		$attributes = $this->traitObject->getAttributes();
		$this->assertEquals($value, $attributes[$name]);
	}

	/**
	 * @return void
	 * @test
	 * @coversDefaultClass setAttributes
	 * @coversDefaultClass getAttributes
	 * @coversDefaultClass __get
	 * @coversDefaultClass __set
	 * @coversDefaultClass __call
	 * @coversDefaultClass getAttribute
	 * @coversDefaultClass setAttribute
	 */
	public function testSetAttributes()
	{
		$attr = [
			'name' => 'value',
			'attName' => 'attValue',
			'attArray' => [
				'arrayOne' => [], 'arrayTwo' => []
			],
			'attArrayTwo' => ['arrOne', 'arrTwo']
		];
		$this->traitObject->setAttributes($attr);
		$attributes = $this->traitObject->getAttributes();
		$this->assertEquals('attValue', $attributes['attName']);
		$this->assertArrayHasKey('name', $attributes);
		$this->assertArrayHasKey('arrayOne', $attributes['attArray']);
		$this->assertEquals('arrTwo', $attributes['attArrayTwo'][1]);
		$this->assertEquals('attValue', $this->traitObject->__get('attName'));
		$this->assertEquals('attValue', $this->traitObject->getAttribute('attName'));
		$this->traitObject->__set('anotherAttribute', 'anotherValue');
		$this->assertEquals('anotherValue', $this->traitObject->__get('anotherAttribute'));
		$this->traitObject->setAttribute('second', 'secondValue');
		$this->assertEquals('secondValue', $this->traitObject->getAttribute('second'));
		$this->traitObject->__call('setCallAttribute', 'callAttributeValue');
		$this->assertEquals('callAttributeValue', $this->traitObject->__call('getCallAttribute'));
	}

}
