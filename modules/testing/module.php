<?php

/**
 * Dx
 *
 * @link http://dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2015 ClaremontDesign/MadLabs-Dx
 * @version 0.0.0.1
 * @since Mar 5, 2016 11:51:42 PM
 * @file dsstore/module.php
 *
 */
return [
	'id' => 'testing',
	'enable' => true,
	'backend' => false,
	'frontend' => false,
	'routes' => [
		'generate_password' => [
			'usernameRouteCheck' => false,
			'url' => '/test/generate-password/{password?}',
			'view' => [
				'enable' => true,
				'layout' => 'blank',
				'name' => 'type.html',
				'content' => function(){
					$password = zbase_route_input('password');
					dd($password, zbase_bcrypt($password));
				}
			]
		],
		'testing_email_sending' => [
			'usernameRouteCheck' => false,
			'url' => '/test/email-sending/{action?}',
			'view' => [
				'enable' => true,
				'layout' => 'blank',
				'name' => 'type.html',
				'content' => function(){
					$user = zbase_entity('user')->repo()->by('username', 'dennesabing')->first();
					$params = [];
					$params['token'] = zbase_generate_code();
					$to = 'dennes.b.abing@gmail.com';
					$fromEmail = zbase_config_get('email.noreply.email');
					$fromName = zbase_config_get('email.noreply.name');
					$subject = 'Test Subject';
					$headers = "From: " . $fromName . " <$fromEmail>\r\n";
					$headers .= "Reply-To: ". $fromName . " <$fromEmail>\r\n";
					$headers .= "MIME-Version: 1.0\r\n";
					$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
					//$message = zbase_view_render(zbase_view_file_contents('auth.password.email.password'), $params);
					//$sent = mail($to, $subject, $message, $headers);
					//dd($sent, $to, $fromEmail, $message);
					dd(zbase_messenger_email($to, 'noreply', $subject, zbase_view_file_contents('auth.password.email.password'), $params));
				}
			]
		],
		'testing_email_template' => [
			'usernameRouteCheck' => false,
			'url' => '/test/templates/email/{type?}',
			'view' => [
				'enable' => true,
				'layout' => 'blank',
				'name' => 'type.html',
				'content' => function(){
					$type = zbase_route_input('type');
					/**
					 * test/templates/email/forgot-password
					 */
					if($type == 'forgot-password')
					{
						$user = zbase_entity('user')->repo()->by('username', 'dennesabing')->first();
						$params = [];
						$params['token'] = zbase_generate_code();
						return zbase_view_render(zbase_view_file_contents('auth.password.email.password'), $params);
					}
					/**
					 * test/templates/email/account-email-verification
					 */
					if($type == 'account-email-verification')
					{
						$user = zbase_entity('user')->repo()->by('username', 'dennesabing')->first();
						$params = [];
						$params['entity'] = $user;
						$params['code'] = zbase_generate_code();
						return zbase_view_render(zbase_view_file_contents('email.account.new'), $params);
					}
					/**
					 * test/templates/email/account-email-update-request
					 */
					if($type == 'account-email-update-request')
					{
						$user = zbase_entity('user')->repo()->by('username', 'dennesabing')->first();
						$params = [];
						$params['entity'] = $user;
						$params['code'] = zbase_generate_code();
						$params['newEmailAddress'] = 'new_email@dennesabing.com';
						return zbase_view_render(zbase_view_file_contents('email.account.newEmailAddressRequest'), $params);
					}
					/**
					 * test/templates/email/account-email-update-verify
					 */
					if($type == 'account-email-update-verify')
					{
						$user = zbase_entity('user')->repo()->by('username', 'dennesabing')->first();
						$params = [];
						$params['entity'] = $user;
						$params['code'] = zbase_generate_code();
						$params['newEmailAddress'] = 'new_email@dennesabing.com';
						return zbase_view_render(zbase_view_file_contents('email.account.newEmailAddressVerification'), $params);
					}
					/**
					 * test/templates/email/account-password-request
					 */
					if($type == 'account-password-request')
					{
						$user = zbase_entity('user')->repo()->by('username', 'dennesabing')->first();
						$params = [];
						$params['entity'] = $user;
						$params['code'] = zbase_generate_code();
						return zbase_view_render(zbase_view_file_contents('email.account.newEmailAddressVerification'), $params);
					}
					/**
					 * test/templates/email/account-password-update
					 */
					if($type == 'account-password-update')
					{
						$user = zbase_entity('user')->repo()->by('username', 'dennesabing')->first();
						$params = [];
						$params['entity'] = $user;
						$params['code'] = zbase_generate_code();
						return zbase_view_render(zbase_view_file_contents('email.account.newPasswordRequest'), $params);
					}
				}
			],
		],
	]
];
