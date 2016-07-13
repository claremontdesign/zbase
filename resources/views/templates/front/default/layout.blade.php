<?php
if(zbase_is_angular_template())
{
	?>
	<div ui-content-for="title">
		<span><?php echo zbase()->view()->title() ?></span>
	</div>
	<div class="scrollable">
		<div class="scrollable-content">
			<div class='section'>
				@yield('content')
			</div>
		</div>
	</div>
	<?php
}
else
{
	ob_start('zbase_view_compile');
	zbase_view_plugin_load('bootstrap');
	?>
	<!DOCTYPE html>
	<html lang="en">
		<head>
			<?php echo zbase_view_render_head() ?>
		</head>
		<body class="{{ implode(' ',zbase_view_placeholder('body_class')) }}">
			{!! zbase_alerts_render() !!}
			@yield('content')
			{!! zbase_view_render_body() !!}
		</body>
	</html>
	<?php
}?>