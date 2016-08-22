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
		'access' => 'only::sudo',
		'values' => [
			'default' => [
				'enable' => true,
				'array' => function(){
					$details = zbase()->system()->scheduledDowntimeDetails();
					if(empty($details['maintenance-message']))
					{
						$details['maintenance-message'] = 'We will be having a temporary downtime on {START_TIME} until {END_TIME}. We will be doing updates and maintenance to serve you better.';
						$details['start-datetime'] = \Carbon\Carbon::createFromTime(10, 0, 0);
						$details['end-datetime'] = \Carbon\Carbon::createFromTime(11, 0, 0);
					}
					else
					{
						$details['start-datetime'] = zbase_date_from_format('Y-m-d H:i:s', $details['start-datetime']);
						$details['end-datetime'] = zbase_date_from_format('Y-m-d H:i:s', $details['end-datetime']);
					}
					return $details;
				}
			],
		],
		'entity' => null,
		'elements' => [
			'header' => [
				'ui' => [
					'type' => 'component.pageHeader',
					'id' => 'header',
					'text' => 'Maintenance Message Details'
				],
			],
			'status' => [
				'type' => 'select',
				'id' => 'status',
				'label' => 'Status',
				'multiOptions' => 'enableDisable',
				'help' => [
					'text' => 'Message will be displayed on the User Dashboard if Enabled.'
				],
			],
			'start-datetime' => [
				'type' => 'datetimelocal',
				'id' => 'start-datetime',
				'label' => 'Start Date/Time',
				'help' => [
					'text' => 'Enter start date and time of maintenance.'
				],
				'validations' => [
					'required' => [
						'enable' => true,
						'message' => 'Enter start date and time of maintenance.'
					],
				],
			],
			'end-datetime' => [
				'type' => 'datetimelocal',
				'id' => 'end-datetime',
				'label' => 'End Date/Time',
				'help' => [
					'text' => 'Enter end date and time of maintenance.'
				],
				'validations' => [
					'required' => [
						'enable' => true,
						'message' => 'Enter end date and time of maintenance.'
					],
				],
			],
			'maintenance-message' => [
				'type' => 'textarea',
				'id' => 'maintenance-message',
				'label' => 'Message prior to Maintenance',
				'help' => [
					'text' => 'Message to be displayed to the user before the maintenance.'
				],
				'html' => [
					'attributes' => [
						'input' => [
							'placeholder' => 'Enter Message',
						],
					],
				],
			],
			'maintenance-ips' => [
				'type' => 'textarea',
				'id' => 'maintenance-ips',
				'label' => 'IP Addresses to be excluded',
				'help' => [
					'text' => 'Enter IP Address that will be excluded for the Maintenance. 1 IP Address per Line.'
				],
				'html' => [
					'attributes' => [
						'input' => [
							'placeholder' => 'IP Addresses',
						],
					],
				],
			],
		]
	],
];
