<!-- BEGIN HEADER -->
<div class="header navbar navbar-fixed-top">
	<!-- BEGIN TOP NAVIGATION BAR -->
	<div class="header-inner">
		<!-- BEGIN LOGO -->
		<a class="navbar-brand" href="<?php echo zbase_url_from_route(zbase_auth_has() ? 'home' : 'index') ?>">
			<?php
			$adminName = zbase_config_get('view.package.templates.metronic.logotext', '<img src="' . zbase_path_asset('img/logo.png') . '" alt="logo" class="img-responsive"/>');
			echo $adminName;
			?>
		</a>
		<!-- END LOGO -->
		<!-- BEGIN RESPONSIVE MENU TOGGLER -->
		<a href="javascript:;" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
			<img src="<?php echo zbase_path_asset('metronic/img/menu-toggler.png'); ?>" alt=""/>
		</a>
		<!-- END RESPONSIVE MENU TOGGLER -->

		<?php if(zbase_auth_has()): ?>
			<div class="hor-menu hidden-sm hidden-xs">
				<ul class="nav navbar-nav">
					<li class="classic-menu-dropdown">
						<a href="/home">
							Home
							<span class="selected">
							</span>
						</a>
					</li>
					<?php
					$modules = zbase()->modules();
					$navs = [];
					foreach ($modules as $module)
					{
						if($module->hasNavigation(zbase_section()))
						{
							$navs[] = $module;
						}
					}
					$navs = zbase_collection($navs)->sortByDesc(function ($itm) {
								return $itm->getNavigationOrder();
					})->toArray();

					if(!empty($navs))
					{
						foreach ($navs as $navigation)
						{
							echo $navigation->getNavigation(zbase_section());
						}
					}
					?>
				</ul>
			</div>
		<?php endif; ?>


		<!-- BEGIN TOP NAVIGATION MENU -->
		<ul class="nav navbar-nav pull-right">

			<?php echo zbase_view_render(zbase_view_file('partial.notification-bar')) ?>

			<!-- BEGIN USER LOGIN DROPDOWN -->
			<?php if(zbase_auth_has()): ?>
				<li class="dropdown user">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
						<img style="width:28px;" alt="" src="<?php echo zbase_auth_user()->avatarUrl(['w' => 30]) ?>"/>
						<span class="username">
							<?php echo zbase_auth_user()->displayName() ?>
						</span>
						<i class="fa fa-angle-down"></i>
					</a>
					<ul class="dropdown-menu">
						<?php
						$headerPullDownMenu = zbase_config_get('theme');
						?>
						<li>
							<a href="<?php echo zbase_url_from_route('account') ?>">
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
						<li>
							<a href="<?php echo zbase_url_from_route('logout') ?>">
								<i class="fa fa-key"></i> Log Out
							</a>
						</li>
					</ul>
				</li>
			<?php endif; ?>
			<!-- END USER LOGIN DROPDOWN -->
		</ul>
		<!-- END TOP NAVIGATION MENU -->
	</div>
	<!-- END TOP NAVIGATION BAR -->
</div>
<!-- END HEADER -->
<div class="clearfix">
</div>