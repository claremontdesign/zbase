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
	zbase_view_plugin_load('bootstrap');
	zbase_view_plugin_load('jquery');
	zbase_view_plugin_load('zbase');
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