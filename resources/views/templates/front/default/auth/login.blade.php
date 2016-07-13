@extends(zbase_view_template_layout())
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">Member Login</div>
				<div class="panel-body">
					{!! view(zbase_view_file_contents('auth.login.form')) !!}
				</div>
			</div>
		</div>
	</div>
</div>
@stop