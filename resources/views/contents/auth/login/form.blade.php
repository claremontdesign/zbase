<?php if(zbase_is_angular_template()): ?>
	<form name="form" role="form" class="form-horizontal">
		<div ng-hide="loginPasswordToggle">
			<div class="form-group" ng-class="{'has-error': form.email.$dirty && form.email.$error.required }">
				<label class="col-md-4 control-label">E-Mail Address</label>

				<div class="col-md-6">
					<input ng-model="email" type="email" class="form-control" required name="email" value="" />
				</div>
			</div>

			<div class="form-group"  ng-class="{'has-error': form.password.$dirty && form.password.$error.required }">
				<label class="col-md-4 control-label">Password</label>

				<div class="col-md-6">
					<input type="password" ng-model="password" class="form-control" name="password" required>
				</div>
			</div>

			<div class="form-group">
				<div class="col-md-6 col-md-offset-4">
					<button class="btn btn-primary" ng-disabled="form.$invalid" ng-click="login()">
						<i class="fa fa-btn fa-sign-in"></i> Login
					</button>
					<a class="btn btn-link" ng-click="loginPasswordToggle = true">Forgot Your Password?</a>
				</div>
			</div>
		</div>
		<div ng-show="loginPasswordToggle">
			<div class="form-group" ng-class="{'has-error': form.email.$dirty && form.email.$error.required }">
				<label class="col-md-4 control-label">E-Mail Address</label>
				<div class="col-md-6">
					<input type="email" ng-model="email" required
						   class="form-control" name="email"
						   value="">
				</div>
			</div>

			<div class="form-group">
				<div class="col-md-6 col-md-offset-4">
					<button class="btn btn-primary" ng-disabled="form.$invalid" ng-click="lostPassword()">
						<i class="fa fa-btn fa-envelope"></i> Send Password Reset Link
					</button>
					<a class="btn btn-link" ng-click="loginPasswordToggle = false">Login</a>
				</div>
			</div>
		</div>
	</form>
<?php else: ?>
	<form class="form-horizontal" role="form" method="POST" action="{{ zbase_url_from_route(zbase_is_back() ? 'admin.login' : 'login')}}">
		{!! zbase_csrf_token_field('login') !!}

		<div class="form-group <?php echo zbase_form_input_has_error('email') ? 'has-error' : '' ?>">
			<label class="col-md-4 control-label">E-Mail Address</label>

			<div class="col-md-6">
				<input type="email" class="form-control" required name="email" value="{{ zbase_form_old('email')}}" x-moz-errormessage="Please Enter Email Address">
			</div>
		</div>

		<div class="form-group <?php echo zbase_form_input_has_error('password') ? 'has-error' : '' ?>">
			<label class="col-md-4 control-label">Password</label>

			<div class="col-md-6">
				<input type="password" class="form-control" name="password" required x-moz-errormessage="Please Enter Password">
			</div>
		</div>

		<div class="form-group">
			<div class="col-md-6 col-md-offset-4">
				<div class="checkbox">
					<label>
						<input type="checkbox" value="1" name="remember" <?php echo zbase_form_old('remember') == 1 ? 'checked="checked"' : '' ?>> Remember Me
					</label>
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="col-md-6 col-md-offset-4">
				<button type="submit" class="btn btn-primary">
					<i class="fa fa-btn fa-sign-in"></i>Login
				</button>
				<a class="btn btn-link" href="{{ zbase_url_from_route('password', [], true)}}">Forgot Your Password?</a>
			</div>
		</div>
		<?php if(!zbase_request_is('login')): ?>
			<input type="hidden" value="<?php echo zbase_url_path() ?>" name="redirect" />
		<?php endif; ?>
	</form>
<?php endif; ?>

