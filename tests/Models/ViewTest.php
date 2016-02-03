<?php

/**
 * Test class for {@see \Zbase\Models\View}.
 * @covers \Zbase\Models\View
 */
class ViewTest extends TestCase
{

	/**
	 * @return void
	 * @test
	 * @coversDefaultClass setPageTitle
	 */
	public function testPageTitle()
	{
		$view = new Zbase\Models\View;
		zbase_config_set('view.default.title.prefix', 'Zbase');
		zbase_config_set('view.default.title.suffix', null);
		zbase_config_set('view.default.title.separator', ' | ');
		$view->setPageTitle('I am the page title.');
		$this->assertEquals('Zbase | I am the page title.', $view->pageTitle());
	}

	/**
	 * @return void
	 * @test
	 * @coversDefaultClass add
	 */
	public function testAddReturnsRightObject()
	{
		$view = new Zbase\Models\View;
		$keyName = 'headMeta';
		$className = 'Zbase\Models\View\\' . ucfirst($keyName);
		$config = [
			'id' => 'viewport',
			'content' => 'width=1020',
			'name' => 'viewport',
			'html' => [
				'condition' => null,
				'attributes' => null
			]
		];
		$this->assertInstanceOf($className, $view->add($keyName, $config));
	}

	/**
	 * @return void
	 * @test
	 * @coversDefaultClass get
	 */
	public function testGetReturnsRightObject()
	{
		$view = new Zbase\Models\View;
		$keyName = 'headMeta';
		$className = 'Zbase\Models\View\\' . ucfirst($keyName);
		$config = [
			'id' => 'viewport',
			'content' => 'width=1020',
			'name' => 'viewport',
			'html' => [
				'condition' => null,
				'attributes' => null
			]
		];
		$this->assertInstanceOf($className, $view->add($keyName, $config));
	}

	/**
	 * @return void
	 * @test
	 * @coversDefaultClass has
	 */
	public function testHasReturnsBoolean()
	{
		$view = new Zbase\Models\View;
		$keyName = 'headMeta';
		$config = [
			'id' => 'viewport',
			'content' => 'width=1020',
			'name' => 'viewport',
			'html' => [
				'condition' => null,
				'attributes' => null
			]
		];
		$view->add($keyName, $config);
		$this->assertTrue($view->has($keyName, $config['id']));
		$this->assertFalse($view->has($keyName, 'ThisIsWrongId'));
	}

	/**
	 * @return void
	 * @test
	 * @coversDefaultClass render
	 */
	public function testRenderReturnsString()
	{
		$view = new Zbase\Models\View;
		$keyName = 'headMeta';
		$config = [
			'id' => 'viewport',
			'content' => 'width=1020',
			'name' => 'viewport',
			'html' => [
				'condition' => null,
				'attributes' => null
			]
		];
		$view->add($keyName, $config);
		$this->assertEquals(EOF . '<meta name="viewport" content="width=1020" />', $view->render($keyName));
	}

	/**
	 * @return void
	 * @test
	 * @coversDefaultClass all
	 */
	public function testAllReturnsArray()
	{
		$view = new Zbase\Models\View;
		$keyName = 'headMeta';
		$config = [
			'id' => 'viewport',
			'content' => 'width=1020',
			'name' => 'viewport',
			'html' => [
				'condition' => null,
				'attributes' => null
			]
		];
		$view->add($keyName, $config);
		$this->assertEquals(1, count($view->all($keyName)));
	}

	/**
	 * @return void
	 * @test
	 * @coversDefaultClass all sorted ascending by position
	 */
	public function testAllReturnsArrayInPosition()
	{
		$view = new Zbase\Models\View;
		$keyName = 'stylesheet';
		$styleOne = [
			'id' => 'styleone',
			'href' => 'styleone.css',
			'position' => 1
		];
		$view->add($keyName, $styleOne);
		$styleTwo = [
			'id' => 'styletwo',
			'href' => 'styletwo.css',
			'position' => 0
		];
		$view->add($keyName, $styleTwo);
		$styleThree = [
			'id' => 'stylethree',
			'href' => 'stylethree.css',
			'position' => 2
		];
		$view->add($keyName, $styleThree);
		$sortedStyle = [
			$styleThree,
			$styleOne,
			$styleTwo
		];
		$all = $view->all($keyName);
		$allSortedStyle = [];
		foreach ($all as $v)
		{
			$allSortedStyle[] = $v->getAttributes();
		}
		$this->assertSame($sortedStyle, $allSortedStyle);
	}

	// <editor-fold defaultstate="collapsed" desc="Placeholders">
	/**
	 * @return void
	 * @test
	 * @coversDefaultClass addToPlaceholder
	 * @coversDefaultClass inPlaceholder
	 * @coversDefaultClass getFromPlaceholder
	 */
	public function testAddToPlaceholder()
	{
		$view = new Zbase\Models\View;
		$config = [
			'id' => 'script',
			'src' => 'script.js',
			'placeholder' => 'placeholder-append',
			'html' => [
				'condition' => null,
				'attributes' => null
			]
		];
		$html = new Zbase\Models\View\Javascript($config);
		$view->addToPlaceholder($html->getPlaceholder(), $html->id(), $html);
		$this->assertTrue($view->inPlaceholder('placeholder-append', 'script'));
		$this->assertInstanceOf(\Zbase\Interfaces\HtmlInterface::class, $view->getFromPlaceholder('placeholder-append', 'script'));
		$view->removeFromPlaceholder('placeholder-append', 'script');
		$this->assertFalse($view->inPlaceholder('placeholder-append', 'script'));
	}

	// </editor-fold>
}
