<form class="form-horizontal" role="form" method="POST" action="{{ zbase_url_create('password') }}">
	{!! zbase_csrf_token_field('login') !!}

	<div class="form-group">
		<label class="col-md-4 control-label">E-Mail Address</label>

		<div class="col-md-6">
			<input type="email" class="form-control" name="email" value="{{ zbase_form_old('email') }}">
		</div>
	</div>

	<div class="form-group">
		<div class="col-md-6 col-md-offset-4">
			<button type="submit" class="btn btn-primary">
				<i class="fa fa-btn fa-envelope"></i>Send Password Reset Link
			</button>
		</div>
	</div>
</form>
