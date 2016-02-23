@extends(zbase_view_template_layout())
@section('content')
<?php
		$elementName = 'username';
		$element = [
			'type' => 'checkbox',
			'label' => 'Element Label',
			'id' => 'elementId',
			'inline' => true,
			'multiOptions' => [
				'value' => 'Value Label',
				'valueTwo' => 'ValueTwo Label',
				'valueThree' => 'ValueThree Label',
				'valueFour' => 'ValueFour Label',
			],
		];
		echo zbase_ui_form_element($elementName, $element);
		$elementName = 'usernamex';
		$element = [
			'type' => 'radio',
			'label' => 'Element Label',
			'id' => 'elementIdx',
			'inline' => true,
			'multiOptions' => [
				'value' => 'Value Label',
				'valueTwo' => 'ValueTwo Label',
				'valueThree' => 'ValueThree Label',
				'valueFour' => 'ValueFour Label',
			],
		];
		echo zbase_ui_form_element($elementName, $element);
?>
 The Content
@stop