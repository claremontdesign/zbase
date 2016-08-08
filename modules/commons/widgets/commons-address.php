<?php

/**
 * Dx
 *
 * @link http://dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2015 ClaremontDesign/MadLabs-Dx
 * @version 0.0.0.1
 * @since Mar 8, 2016 10:37:59 AM
 * @file widget.php
 * @project Expression project.name is undefined on line 13, column 15 in Templates/Scripting/EmptyPHP.php.
 * @package Expression package is undefined on line 14, column 15 in Templates/Scripting/EmptyPHP.php.
 *
 */
return [
	'type' => 'form',
	'enable' => true,
	'config' => [
		'nested' => true,
		'elements' => [
			'first_name' => [
				'type' => 'text',
				'id' => 'first_name',
				'label' => 'First Name',
				'html' => [
					'attributes' => [
						'wrapper' => [
							'class' => ['col-md-6', 'col-sm-12']
						],
					],
				],
				'validations' => [
					'required' => [
						'enable' => true,
						'message' => 'First Name is required.'
					],
				],
			],
			'last_name' => [
				'type' => 'text',
				'id' => 'last_name',
				'label' => 'Last Name',
				'html' => [
					'attributes' => [
						'wrapper' => [
							'class' => ['col-md-6', 'col-sm-12']
						],
					],
				],
				'validations' => [
					'required' => [
						'enable' => true,
						'message' => 'Last Name is required.'
					],
				],
			],
			'address' => [
				'type' => 'text',
				'id' => 'address',
				'label' => 'Address',
				'html' => [
					'attributes' => [
						'input' => [
							'placeholder' => 'Room No, Street Name'
						],
						'wrapper' => [
							'class' => ['col-md-6', 'col-sm-12']
						],
					],
				],
				'validations' => [
					'required' => [
						'enable' => true,
						'message' => 'Address is required.'
					],
				],
			],
			'address_two' => [
				'type' => 'text',
				'id' => 'address_two',
				'label' => '&nbsp;',
				'html' => [
					'attributes' => [
						'input' => [
							'placeholder' => 'Barangay/District'
						],
						'wrapper' => [
							'class' => ['col-md-6', 'col-sm-12']
						],
					],
				],
			],
			'city' => [
				'type' => 'text',
				'id' => 'city',
				'label' => 'City/Municipality',
				'html' => [
					'attributes' => [
						'wrapper' => [
							'class' => ['col-md-6', 'col-sm-12']
						],
					],
				],
				'validations' => [
					'required' => [
						'enable' => true,
						'message' => 'City is required.'
					],
				],
			],
			'zip' => [
				'type' => 'text',
				'id' => 'zip',
				'label' => 'Zipcode',
				'html' => [
					'attributes' => [
						'wrapper' => [
							'class' => ['col-md-6', 'col-sm-12']
						],
					],
				],
				'validations' => [
					'required' => [
						'enable' => false,
						'message' => 'Zipcode is required.'
					],
				],
			],
			'state' => [
				'type' => 'text',
				'id' => 'state',
				'label' => 'State/Province',
				'html' => [
					'attributes' => [
						'wrapper' => [
							'class' => ['col-md-6', 'col-sm-12']
						],
					],
				],
				'validations' => [
					'required' => [
						'enable' => true,
						'message' => 'State/Province is required.'
					],
				],
			],
			'country' => [
				'type' => 'text',
				'id' => 'country',
				'label' => 'Country',
				'value' => 'PH',
				'html' => [
					'attributes' => [
						'input' => [
							'readonly' => 'true',
						],
						'wrapper' => [
							'class' => ['col-md-6', 'col-sm-12']
						],
					],
				],
				'validations' => [
					'required' => [
						'enable' => true,
						'message' => 'Country is required.'
					],
				],
			],
			'comment' => [
				'type' => 'textarea',
				'id' => 'comment',
				'label' => 'Comment',
				'html' => [
					'attributes' => [
						'input' => [
							'placeholder' => 'Notes and comments on shipping',
						],
						'wrapper' => [
							'class' => ['col-md-12', 'col-sm-12']
						],
					],
				],
			],
		]
	],
];
