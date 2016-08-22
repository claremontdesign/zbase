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
 */
return [
	'type' => 'form',
	'enable' => false,
	'config' => [
		'nested' => true,
		'elements' => [
			'header' => [
				'ui' => [
					'type' => 'component.pageHeader',
					'id' => 'header',
					'text' => 'Request to change password, enter your current password.'
				],
			],
			'password' => [
				'type' => 'password',
				'id' => 'account_password',
				'label' => 'Account Password',
				'angular' => [
					'ngModel' => 'currentUser.accountPassword',
				],
				'validations' => [
					'required' => [
						'enable' => function(){
							if(zbase_request_is_post())
							{
								$tab = zbase_request_input('tab');
								if($tab == 'email' || $tab == 'password')
								{
									return true;
								}
							}
							return false;
						},
						'message' => 'Please enter your account password.'
					],
					'accountPassword' => [
						'enable' => function(){
							if(zbase_request_is_post())
							{
								$tab = zbase_request_input('tab');
								if($tab == 'email' || $tab == 'password')
								{
									return true;
								}
							}
							return false;
						},
						'message' => 'Account password don\'t match.'
					],
				],
			],
		],
	],
];
