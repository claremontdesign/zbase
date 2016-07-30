<?php
$isMobile = zbase_is_mobile();
$isMobileTablet = zbase_is_mobileTablet();
if(zbase_is_angular_template())
{
	?>
	@include(zbase_view_file('type.angular'))
	<?php
}
else
{
	?>
	<?php
	$prefix = zbase_tag();
	zbase_view_plugin_load('jquery');
	zbase_view_plugin_load('zbase');
	zbase_view_plugin_load('mobileangular');
	$mobileAngular = [
		'id' => 'mobileangular-zbase',
		'type' => \Zbase\Models\View::JAVASCRIPT,
		'src' => zbase_url_from_route('angular-js'),
		'enable' => true,
		'position' => 496,
	];
	zbase_view_javascripts_set(['mobileangular-zbase' => $mobileAngular]);
	?>
	<!DOCTYPE html>
	<html ng-app="MobileAngularUi<?php echo $prefix ?>" ng-controller="MainController">
		<head>
			<meta charset="utf-8" />
			<base href="/admin" />
			<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
			<meta name="apple-mobile-web-app-capable" content="yes" />
			<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimal-ui" />
			<meta name="apple-mobile-web-app-status-bar-style" content="yes" />
			<script type="text/javascript">var isAngular = true;</script>
			{!! zbase_view_render_head() !!}
			{!! zbase_view_render_body() !!}
		</head>
		<body ui-prevent-touchmove-defaults>

			<?php if(zbase_auth_has()): ?>
				<!-- Sidebars -->
				<div ng-include="'<?php echo zbase_url_from_route('admin-angular-mobile-sidebar') ?>?at=1'"
					 ui-track-as-search-param='true'
					 class="sidebar sidebar-left"></div>

				<div ng-include="'<?php echo zbase_url_from_route('admin-angular-mobile-sidebar-right') ?>?at=1'"
					 class="sidebar sidebar-right"></div>

				<div class="app"
					 ui-swipe-right='Ui.turnOn("uiSidebarLeft")'
					 ui-swipe-left='Ui.turnOff("uiSidebarLeft")'>

					<!-- Navbars -->
					<div class="navbar navbar-app navbar-absolute-top">
						<div class="navbar-brand navbar-brand-center" ui-yield-to="title">
							<% viewTitle %>
						</div>
						<div class="btn-group pull-left">
							<div ui-toggle="uiSidebarLeft" class="btn sidebar-toggle">
								<i class="fa fa-bars"></i> Menu
							</div>
						</div>
					</div>
					<!-- App Body -->
					<div class="app-body" ng-class="{loading: loading}">
						<div ng-show="loading" class="app-content-loading">
							<i class="fa fa-spinner fa-spin loading-spinner"></i>
						</div>
						<div ng-class="{ 'alert': flash, 'alert-success': flash.type === 'success', 'alert-danger': flash.type === 'error','alert-info': flash.type === 'info', 'alert-warning': flash.type === 'warning' }" ng-if="flash" ng-bind="flash.message"></div>
						<div class="app-content">
							<ng-view></ng-view>
						</div>
					</div>
				</div><!-- ~ .app -->
				<div ui-yield-to="modals"></div>
			<?php else: ?>
				<div class="app">
					<!-- App Body -->
					<div class="app-body" ng-class="{loading: loading}">
						<div ng-show="loading" class="app-content-loading">
							<i class="fa fa-spinner fa-spin loading-spinner"></i>
						</div>
						<div ng-class="{ 'alert': flash, 'alert-success': flash.type === 'success', 'alert-danger': flash.type === 'error','alert-info': flash.type === 'info', 'alert-warning': flash.type === 'warning' }" ng-if="flash" ng-bind="flash.message"></div>
						<div class="app-content">
							<ng-view></ng-view>
						</div>
					</div>
				</div><!-- ~ .app -->
				<div ui-yield-to="modals"></div>
			<?php endif; ?>
		</body>
	</html>
	<?php
}?>