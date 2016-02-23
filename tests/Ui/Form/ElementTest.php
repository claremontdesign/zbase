<?php

/**
 * Dx
 *
 * @link http://dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2015 ClaremontDesign/MadLabs-Dx
 * @version 0.0.0.1
 * @since Feb 23, 2016 7:12:54 PM
 * @file Element.php
 * @project Expression project.name is undefined on line 13, column 15 in Templates/Scripting/EmptyPHP.php.
 * @package Expression package is undefined on line 14, column 15 in Templates/Scripting/EmptyPHP.php.
 */
class ElementTest extends TestCase
{

	/**
	 * @return void
	 * @test
	 * @group Ui
	 */
	public function testElementFactoryText()
	{
		$elementName = 'username';
		$element = [
			'type' => 'text',
			'label' => 'Element Label',
			'id' => 'elementId'
		];
		$ele = zbase_ui_form_element($elementName, $element);
		$this->assertTrue($ele instanceof \Zbase\Ui\Form\Element);

		$elementRoute = [
			'view' => [
				'name' => zbase_tag() . 'test::contents.test.content',
				'enable' => true,
				'content' => function() use($elementName, $element){
					return zbase_ui_form_element($elementName, $element);
				},
			],
			'url' => '/test/ui-form-element-text',
			'enable' => true
		];
		zbase_route_init('uiFormElementTest', $elementRoute);
		$this->visit('/test/ui-form-element-text')->see('Element Label');
	}

	/**
	 * @return void
	 * @test
	 * @group Ui
	 */
	public function testElementFactoryRadio()
	{
		$elementName = 'username';
		$element = [
			'type' => 'radio',
			'label' => 'Element Label',
			'id' => 'elementId',
			'multiOptions' => [
				'value' => 'Value Label',
				'valueTwo' => 'ValueTwo Label',
				'valueThree' => 'ValueThree Label',
				'valueFour' => 'ValueFour Label',
			],
		];
		$elementRoute = [
			'view' => [
				'name' => zbase_tag() . 'test::contents.test.content',
				'enable' => true,
				'content' => function() use($elementName, $element){
					return zbase_ui_form_element($elementName, $element);
				},
			],
			'url' => '/test/ui-form-element-radio',
			'enable' => true
		];
		zbase_route_init('uiFormElementRadio', $elementRoute);
		$this->visit('/test/ui-form-element-radio')->see('ValueThree Label');
	}

	/**
	 * @return void
	 * @test
	 * @group Ui
	 */
	public function testElementFactoryCheckbox()
	{
		$elementName = 'username';
		$element = [
			'type' => 'checkbox',
			'label' => 'Element Label',
			'id' => 'elementId',
			'multiOptions' => [
				'valueThree' => 'ValueCheckobx Label',
			],
		];
		$elementRoute = [
			'view' => [
				'name' => zbase_tag() . 'test::contents.test.content',
				'enable' => true,
				'content' => function() use($elementName, $element){
					return zbase_ui_form_element($elementName, $element);
				},
			],
			'url' => '/test/ui-form-element-checkbox',
			'enable' => true
		];
		zbase_route_init('uiFormElementChecbox', $elementRoute);
		$this->visit('/test/ui-form-element-checkbox')->see('ValueCheckobx');
	}

	/**
	 * @return void
	 * @test
	 * @group Ui
	 */
	public function testElementFactorySelect()
	{
		$elementName = 'username';
		$element = [
			'type' => 'select',
			'label' => 'Select Element Label',
			'id' => 'elementId',
			'multiOptions' => [
				'valueThree' => 'ValueCheckobx Label',
			],
		];
		$elementRoute = [
			'view' => [
				'name' => zbase_tag() . 'test::contents.test.content',
				'enable' => true,
				'content' => function() use($elementName, $element){
					return zbase_ui_form_element($elementName, $element);
				},
			],
			'url' => '/test/ui-form-element-select',
			'enable' => true
		];
		zbase_route_init('uiFormElementSelect', $elementRoute);
		$this->visit('/test/ui-form-element-select')->see('Select Element Label');
	}

}
