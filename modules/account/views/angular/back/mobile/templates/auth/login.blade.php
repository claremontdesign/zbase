<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default" ng-hide="loginPasswordToggle">
                <div class="panel-heading">Member Login</div>
                <div class="panel-body">
					<form class="form-horizontal" name="form" ng-submit="login()" role="form">
						{!! zbase_csrf_token_field('login') !!}

						<div class="form-group" ng-class="{ 'has-error': form.email.$dirty && form.email.$error.required }">
							<label class="col-md-4 control-label">E-Mail Address</label>

							<div class="col-md-6">
								<input ng-model="email" type="email" class="form-control" required name="email" value="" />
							</div>
						</div>

						<div class="form-group"  ng-class="{ 'has-error': form.password.$dirty && form.password.$error.required }">
							<label class="col-md-4 control-label">Password</label>

							<div class="col-md-6">
								<input type="password" ng-model="password" class="form-control" name="password" required>
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary" ng-disabled="form.$invalid">
									<i class="fa fa-btn fa-sign-in"></i> Login
								</button>
								<a class="btn btn-link" ng-click="loginPasswordToggle=true">Forgot Your Password?</a>
							</div>
						</div>
					</form>
                </div>
            </div>
            <div class="panel panel-default" ng-show="loginPasswordToggle">
                <div class="panel-heading">Reset Password</div>
                <div class="panel-body">
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
									<i class="fa fa-btn fa-envelope"></i> Send Password Reset Link
								</button>
								<a class="btn btn-link" ng-click="loginPasswordToggle=false">Login</a>
							</div>
						</div>
					</form>

                </div>
            </div>
        </div>
    </div>
</div>