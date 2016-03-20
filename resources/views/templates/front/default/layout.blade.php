<?php
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
