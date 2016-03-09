<!DOCTYPE html>
<html lang="en">
	<head>
		{!! zbase_view_render_head() !!}
	</head>
	<body class="backend {{ implode(' ',zbase_view_placeholder('body_class')) }}">
		{!! zbase_alerts_render() !!}
		@yield('content')
		{!! zbase_view_render_body() !!}
	</body>
</html>
