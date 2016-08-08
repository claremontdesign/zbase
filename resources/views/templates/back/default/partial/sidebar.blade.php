<!-- BEGIN SIDEBAR -->
<div class="page-sidebar-wrapper">
	<div class="page-sidebar navbar-collapse collapse">
		<!-- BEGIN SIDEBAR MENU -->
		<ul class="page-sidebar-menu" data-auto-scroll="true" data-slide-speed="200">
			<li class="sidebar-toggler-wrapper">
				<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
				<div class="sidebar-toggler hidden-phone">
				</div>
				<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
			</li>
			<li class="start ">
				<a href="<?php echo zbase_url_from_route('admin') ?>">
					<i class="fa fa-home"></i>
					<span class="title">
						Dashboard
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
		<!-- END SIDEBAR MENU -->
	</div>
</div>
<!-- END SIDEBAR -->