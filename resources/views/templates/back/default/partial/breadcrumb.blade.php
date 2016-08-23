<ul class="page-breadcrumb breadcrumb">
	<li class="btn-group">
		<?php
		$topActionBar = zbase_view_placeholder_render('topActionBar');
		?>
		<?php if(!empty($topActionBar)): ?>
			<button type="button" class="btn blue dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true">
				<span>
					Actions
				</span>
				<i class="fa fa-angle-down"></i>
			</button>
			<ul class="dropdown-menu pull-right" role="menu">
				<?php echo $topActionBar ?>
			</ul>
		<?php endif; ?>
	</li>
	<li>
		<i class="fa fa-home"></i>
		<a href="<?php echo zbase_url_from_route('admin') ?>">
			Home
		</a>
		<i class="fa fa-angle-right"></i>
	</li>
	<?php
	$breadcrumbs = zbase()->view()->getBreadcrumb();
	$i = 0;
	?>
	<?php if(!empty($breadcrumbs)): ?>

		<?php foreach ($breadcrumbs as $crumb): ?>
			<?php $i++; ?>
			<?php
			$url = zbase_url_from_config($crumb);
			?>
			<li>
				<a title="<?php echo !empty($crumb['title']) ? $crumb['title'] : $crumb['label']; ?>" href="<?php echo $url; ?>">
					<?php echo $crumb['label']; ?>
				</a>
				<?php if($i < count($breadcrumbs)): ?>
					<i class="fa fa-angle-right"></i>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	<?php endif; ?>
</ul>