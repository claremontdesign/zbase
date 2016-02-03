<?php

/**
 * Test class for {@see \Zbase\Traits\Id}.
 * @covers \Zbase\Traits\Id
 */
class IdTest extends TestCase
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
		$traitName = \Zbase\Traits\Id::class;
		return $this->getObjectForTrait($traitName);
	}

	/**
	 * @return void
	 * @test
	 * @coversDefaultClass id
	 */
	public function testIdReturnsTheSame()
	{
		$this->traitObject->setId('id');
		$this->assertEquals('id', $this->traitObject->id());
	}

	/**
	 * @return void
	 * @test
	 * @coversDefaultClass title
	 */
	public function testTitleReturnsTheSame()
	{
		$this->traitObject->setTitle('title');
		$this->assertEquals('title', $this->traitObject->title());
	}

	/**
	 * @return void
	 * @test
	 * @coversDefaultClass name
	 */
	public function testNameReturnsTheSame()
	{
		$this->traitObject->setName('name');
		$this->assertEquals('name', $this->traitObject->name());
	}

	/**
	 * @return void
	 * @test
	 * @coversDefaultClass description
	 */
	public function testDescriptionReturnsTheSame()
	{
		$this->traitObject->setDescription('description');
		$this->assertEquals('description', $this->traitObject->description());
	}

}
