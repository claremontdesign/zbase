<?php
/**
 * Dx
 *
 * @link http://dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2015 ClaremontDesign/MadLabs-Dx
 * @version 0.0.0.1
 * @since Sep 9, 2016 10:11:07 PM
 * @file account.blade.php
 * @project Zbase
 * @package Expression package is undefined on line 14, column 15 in Templates/Scripting/EmptyPHP.php.
 */
$profile = zbase_config_get('modules.account.widgets.profile.enable', true);
$image = zbase_config_get('modules.account.widgets.image.enable', true);
$email = zbase_config_get('modules.account.widgets.email.enable', true);
$username = zbase_config_get('modules.account.widgets.username.enable', true);
$password = zbase_config_get('modules.account.widgets.password.enable', true);
$notification = zbase_config_get('modules.account.widgets.notifications.enable', true);
$currentUser = zbase_auth_user();
$moduleName = 'account';
$isAdmin = $currentUser->isAdmin();
$adminView = false;
if($isAdmin && !empty(zbase_route_input('id')))
{
	$adminView = true;
	$moduleName = 'admin-user';
	$selectedUser = zbase_user_byid(zbase_route_input('id'));
	if(!$selectedUser instanceof \Zbase\Entity\Laravel\User\User)
	{
		zbase_abort(404);
		exit;
	}

	$page = [];
	$page['title'] = '<span class="userDisplayName' . $selectedUser->id() . '">' . $selectedUser->roleTitle() . ' - ' . $selectedUser->id() . ': ' . $selectedUser->displayName() . '</span>' . $selectedUser->statusText();
	$page['headTitle'] = $selectedUser->displayName();
	$page['subTitle'] = $selectedUser->email() . '|' . $selectedUser->username() . '|' . $selectedUser->cityStateCountry();
	zbase_view_page_details(['page' => $page]);
	$breadcrumbs = [
		['label' => 'Users', 'route' => ['name' => 'admin.users']],
		['label' => '<span class="userDisplayName' . $selectedUser->id() . '">' . $selectedUser->displayName() . '</span>', 'link' => '#', 'title' => $selectedUser->displayName()],
	];
	zbase_view_breadcrumb($breadcrumbs);

}
else
{
	$selectedUser = zbase_auth_user();
}


$accountTabs = [];
$accountContents = [];
$accountContents[] = [
	'position' => 10,
	'groupId' => 'information',
	'content' => function() use ($selectedUser){
		return zbase_view_render(zbase_view_file_module('account.views.information', 'account', 'zbase'), ['user' => $selectedUser]);
	}
];
$accountContents[] =[
	'position' => 10,
	'groupId' => 'notification',
	'content' => function() use ($selectedUser){
		return zbase_view_render(zbase_view_file_module('account.views.notifications', 'account', 'zbase'), ['user' => $selectedUser]);
	}
];
$accountContents = zbase_module_widget_contents('account', 'account', zbase_section(), $adminView, null, $accountContents);
$accountTabs = zbase_module_widget_contents('account', 'account', zbase_section(), $adminView, 'tabs');
$widgetConfig = ['config' => ['entity' => ['entity' => $selectedUser]]];
?>
<div class="zbase-ui-wrapper zbase-ui-tabs" id="zbase-ui-tabs-accounttabs">
	<ul class="nav nav-tabs">
		<li class=""><a data-toggle="tab" href="#accounttabsaccountInformation">Account Information</a></li>
		<?php if(!empty($accountTabs)):?>
			<?php foreach($accountTabs as $accountTab):?>
				<?php $accountTabId = !empty($accountTab['id']) ? $accountTab['id'] : null;?>
				<?php if(!empty($accountTabId)):?>
					<li class=""><a data-toggle="tab" href="#accounttabs<?php echo !empty($accountTab['id']) ? $accountTab['id'] : null?>">
						<?php echo !empty($accountTab['label']) ? $accountTab['label'] : null?></a>
					</li>
				<?php endif;?>
			<?php endforeach;?>
		<?php endif;?>
		<?php if(!empty($notification)): ?>
			<li class=""><a data-toggle="tab" href="#accounttabsnotifications">Notifications</a></li>
		<?php endif; ?>
		<?php if(!empty($profile)): ?>
			<li class=""><a data-toggle="tab" href="#accounttabsprofile">Profile</a></li>
		<?php endif; ?>
		<?php if(!empty($profile)): ?>
			<li class=""><a data-toggle="tab" href="#accounttabsaccount">Account</a></li>
		<?php endif; ?>
	</ul>

	<div class="tab-content">
		<div class="zbase-ui-wrapper zbase-ui-tab tab-pane fade active in" id="accounttabsaccountInformation">
			<?php echo zbase_module_widget_render_contents($accountContents, 'information');?>
		</div>
		<?php if(!empty($accountTabs)):?>
			<?php foreach($accountTabs as $accountTab):?>
				<?php $accountTabId = !empty($accountTab['id']) ? $accountTab['id'] : null;?>
				<?php if(!empty($accountTabId)):?>
					<div class="zbase-ui-wrapper zbase-ui-tab tab-pane fade" id="accounttabs<?php echo $accountTabId?>">
						<?php echo zbase_module_widget_render_contents($accountContents, $accountTabId);?>
					</div>
				<?php endif;?>
			<?php endforeach;?>
		<?php endif;?>
		<?php if(!empty($notification)): ?>
			<div class="zbase-ui-wrapper zbase-ui-tab tab-pane fade" id="accounttabsnotifications">
				<?php echo zbase_module_widget_render_contents($accountContents, 'notification');?>
			</div>
		<?php endif; ?>
		<?php if(!empty($profile)): ?>
			<div class="zbase-ui-wrapper zbase-ui-tab tab-pane fade" id="accounttabsprofile">
				<div class="col-md-6 col-sm-12">
					<?php echo zbase_widget($moduleName . '-profile', [],true, $widgetConfig); ?>
				</div>
				<?php if(!empty($image)): ?>
					<div class="col-md-6 col-sm-12">
					<?php echo zbase_widget($moduleName . '-image', [],true, $widgetConfig); ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="zbase-ui-wrapper zbase-ui-tab tab-pane fade" id="accounttabsaccount">
			<?php if(!empty($adminView)): ?>
				<?php if(zbase_auth_can_duplex() && zbase_auth_duplex_enable()):?>
					<?php echo $selectedUser->loginAs();?>
					<hr />
				<?php endif;?>
				<?php echo zbase_widget($moduleName . '-status', [],true, $widgetConfig); ?>
				<hr />
			<?php endif; ?>
			<?php if(!empty($username)): ?>
			<?php echo zbase_widget($moduleName . '-username', [],true, $widgetConfig); ?>
			<hr />
			<?php endif; ?>
			<?php if(!empty($email)): ?>
			<?php echo zbase_widget($moduleName . '-email', [],true, $widgetConfig); ?>
			<hr />
			<?php endif; ?>
			<?php if(!empty($password)): ?>
			<?php echo zbase_widget($moduleName . '-password', [],true, $widgetConfig); ?>
			<?php endif; ?>
		</div>
	</div>
</div>