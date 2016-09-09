<?php
if(zbase_is_angular_template())
{
	?>
	@include(zbase_view_file('type.angular'))
	<?php
}
else
{
	ob_start('zbase_view_compile');
	zbase_view_plugin_load('jquery');
	zbase_view_plugin_load('bootstrap');
	zbase_view_plugin_load('zbase');
	zbase_view_plugin_load('metronic-admin');
	zbase_view_plugin_load('metronic-front');
	zbase_view_plugin_load('toastr');
	?>
	<!DOCTYPE html>
	<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
	<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
	<!--[if !IE]><!-->
	<html lang="en" class="no-js">
		<head>
			<?php echo zbase_view_render_head(); ?>
		</head>
		<body class="front page-header-fixed page-full-width {{ implode(' ',zbase_view_placeholder('body_class')) }}">
				<?php echo zbase_view_render(zbase_view_file('partial.header')); ?>
				<div class="page-container">
					<?php echo zbase_view_render(zbase_view_file('partial.sidebar')); ?>
					<!-- BEGIN CONTENT -->
					<div class="page-content-wrapper">
						<div class="page-content">
							<?php echo zbase_view_render(zbase_view_file('partial.themeCustomizer')); ?>
							<!-- BEGIN PAGE HEADER-->
							<div class="row">
								<div class="col-md-12">
									<h3 class="page-title">
										<span class="zbase-page-title"><?php echo zbase()->view()->title() ?> <small><?php echo zbase()->view()->subTitle() ?></small></span>
									</h3>
									<?php echo zbase_view_render(zbase_view_file('partial.breadcrumb')); ?>
								</div>
							</div>
							<!-- END PAGE HEADER-->
							<!-- BEGIN PAGE CONTENT-->
							<div class="row">
								<div class="col-md-12 page-content-inner">
									{!! zbase_alerts_render() !!}
									@yield('content')
								</div>
							</div>
							<!-- END PAGE CONTENT-->
						</div>
					</div>
					<!-- END CONTENT -->
				</div>
				<?php echo zbase_view_render(zbase_view_file('partial.footer', 'back')); ?>
			<?php echo zbase_view_render_body(); ?>
		</body>
	</html>
	<?php
}?>