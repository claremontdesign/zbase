<!-- BEGIN HEADER -->
<div class="header navbar navbar-fixed-top">
	<!-- BEGIN TOP NAVIGATION BAR -->
	<div class="header-inner">
		<!-- BEGIN LOGO -->
		<a class="navbar-brand" href="<?php echo zbase_url_from_route('admin')?>">
			<?php
			$adminName = zbase_config_get('view.package.templates.metronic.logotext', '<img src="' . zbase_path_asset('metronic/img/logo.png') . '" alt="logo" class="img-responsive"/>');
			echo $adminName;
			?>
		</a>
		<!-- END LOGO -->
		<!-- BEGIN RESPONSIVE MENU TOGGLER -->
		<a href="javascript:;" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
			<img src="<?php echo zbase_path_asset('metronic/img/menu-toggler.png'); ?>" alt=""/>
		</a>
		<!-- END RESPONSIVE MENU TOGGLER -->
		<!-- BEGIN TOP NAVIGATION MENU -->
		<ul class="nav navbar-nav pull-right">
			<!-- BEGIN USER LOGIN DROPDOWN -->
			<li class="dropdown user">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
					<img alt="" src="<?php echo zbase_auth_user()->avatarUrl(['w' => 30])?>"/>
					<span class="username">
						<?php echo zbase_auth_user()->displayName()?>
					</span>
					<i class="fa fa-angle-down"></i>
				</a>
				<ul class="dropdown-menu">
					<li>
						<a href="<?php echo zbase_url_from_route('admin.account')?>">
							<i class="fa fa-user"></i> My Profile
						</a>
					</li>
					<li class="divider">
					</li>
					<li>
						<a href="javascript:;" id="trigger_fullscreen">
							<i class="fa fa-arrows"></i> Full Screen
						</a>
					</li>
<!--					<li>
						<a href="extra_lock.html">
							<i class="fa fa-lock"></i> Lock Screen
						</a>
					</li>-->
					<li>
						<a href="<?php echo zbase_url_from_route('admin.logout')?>">
							<i class="fa fa-key"></i> Log Out
						</a>
					</li>
				</ul>
			</li>
			<!-- END USER LOGIN DROPDOWN -->
		</ul>
		<!-- END TOP NAVIGATION MENU -->
	</div>
	<!-- END TOP NAVIGATION BAR -->
</div>
<!-- END HEADER -->
<div class="clearfix">
</div>