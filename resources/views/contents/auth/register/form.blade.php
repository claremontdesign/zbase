<form class="form-horizontal" role="form" method="POST" action="{{ zbase_url_create('register') }}">
	{!! zbase_csrf_token_field() !!}

	<div class="form-group">
		<label class="col-md-4 control-label">Name</label>

		<div class="col-md-6">
			<input type="text" class="form-control" name="name" value="{{ zbase_form_old('name') }}">
		</div>
	</div>
	<div class="form-group">
		<label class="col-md-4 control-label">Username</label>

		<div class="col-md-6">
			<input type="text" class="form-control" name="username" value="{{ zbase_form_old('username') }}">
		</div>
	</div>

	<div class="form-group">
		<label class="col-md-4 control-label">E-Mail Address</label>

		<div class="col-md-6">
			<input type="email" class="form-control" name="email" value="{{ zbase_form_old('email') }}">
		</div>
	</div>

	<div class="form-group">
		<label class="col-md-4 control-label">Password</label>

		<div class="col-md-6">
			<input type="password" class="form-control" name="password">
		</div>
	</div>

	<div class="form-group">
		<label class="col-md-4 control-label">Confirm Password</label>

		<div class="col-md-6">
			<input type="password" class="form-control" name="password_confirmation">
		</div>
	</div>

	<div class="form-group">
		<div class="col-md-6 col-md-offset-4">
			<button type="submit" class="btn btn-primary">
				<i class="fa fa-btn fa-user"></i>Register
			</button>
		</div>
	</div>
</form>