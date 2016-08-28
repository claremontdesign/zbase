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
	'id' => 'notifications',
	'enable' => true,
	'access' => 'users',
	'class' => null,
	'backend' => true,
	'frontend' => false,
	'url' => [
		'backend' => 'notifications/{action?}/{id?}/{task?}',
	],
	'notification' => [
		'back' => [
			[
				'enable' => true,
				'id' => 'notifications',
				'order' => 9999,
				'route' => [
					'name' => 'admin.notifications',
				],
				'icon' => 'fa fa-warning',
				'label' => 'Notifications',
				'title' => 'Notifications',
				'viewAllText' => 'See all notifications',
				'badgeCount' => function(){
					$notifications = zbase_auth_user()->notificationsNotNotified();
					if(!empty($notifications))
					{
						return $notifications;
					}
					return null;
				},
				'defaultMessage' => function(){
					$notifications = zbase_auth_user()->notificationsNotNotified();
					if(!empty($notifications))
					{
						return 'You have ' . $notifications . ' new notifications.';
					}
					return 'No new notifications.';
				},
				'height' => '250px',
				'defaultContent' => function(){
					zbase_view_script_add('mnotifications-script', zbase_view_render(zbase_view_file_module('notifications.views.js', 'notifications', 'zbase')), true);
					$notifications = zbase_auth_user()->notificationsLatest();
					if(!empty($notifications))
					{
						$string = '';
						foreach($notifications as $notification)
						{
							$string .= '<li data-id="' . $notification->id() . '" id="notifications-list-'.$notification->id().'">
								<a href="'. $notification->url() . '">
									<span class="label label-sm label-icon label-'.$notification->displayColor().'">
										<i class="fa '. $notification->displayIcon() . '"></i>
									</span>
									 ' . $notification->displayMessage() . '
									<span class="time">
										 ' . $notification->displayTime() . '
									</span>
								</a>
							</li>';
						}
						return $string;
					}
					return null;
				},
			]
		],
	],
	'controller' => [
		'back' => [
			'action' => [
				'index' => [
					'page' => [
						'title' => 'Notifications',
						'headTitle' => 'Notifications',
						'subTitle' => '',
						'breadcrumbs' => [
							['label' => 'Notifications'],
						],
					],
				],
			]
		],
	],
	'routes' => [],
	'widgets' => [
		'back' => [
			'controller' => [
				'action' => [
					'index' => [
						'notifications-list' => null,
					],
					'json-seen' => [
						'notifications-list' => function(){
							zbase_auth_user()->notificationSeen();
							return null;
						},
					],
				],
			],
		],
	],
];
