<script type="text/javascript">
	app.controller('adminAccountMainController',
			function ($rootScope, $http, $window, userService) {
				initController();
				function initController() {
					loadCurrentUser();
				}
				function loadCurrentUser() {
					userService.getCurrentUser()
							.then(function (response) {
								$rootScope.currentUser = response.data.api.result.user;
								$rootScope.originalCurrentUser = response.data.api.result.user;
							});
				}
				$rootScope.submitaccounttabsprofileAccount = function () {
					var data = {
						first_name: $rootScope.currentUser.profile.first_name,
						last_name: $rootScope.currentUser.profile.last_name
					};
					$rootScope.loading = true;
					$http.post('<?php echo zbase_api_url(['module' => 'account', 'object' => 'user', 'method' => 'updateProfile']) ?>', data)
							.success(function (response) {
								if(response.api.result.user !== undefined)
								{
									$rootScope.currentUser = response.api.result.user;
									$rootScope.originalCurrentUser = response.api.result.user;
								}
							});
				}
				$rootScope.submitaccounttabsemailAccount = function () {
					var data = {
						email: $rootScope.currentUser.email,
						accountpassword: $rootScope.currentUser.accountPassword
					};
					$rootScope.loading = true;
					$http.post('<?php echo zbase_api_url(['module' => 'account', 'object' => 'user', 'method' => 'updateEmail']) ?>', data);
				}
				$rootScope.submitaccounttabspasswordAccount = function () {
					var data = {
						password: $rootScope.currentUser.password,
						passwordConfirm: $rootScope.currentUser.passwordConfirm,
						accountpassword: $rootScope.currentUser.accountPassword
					};
					$rootScope.loading = true;
					$http.post('<?php echo zbase_api_url(['module' => 'account', 'object' => 'user', 'method' => 'updatePassword']) ?>', data)
							.success(function(){
								$rootScope.currentUser.password = null;
								$rootScope.currentUser.passwordConfirm = null;
								$rootScope.currentUser.accountPassword = null;
							});
				}
				$rootScope.submitaccounttabsimagesAccount = function () {
					var data = {
					};
					$http.post('<?php echo zbase_api_url(['module' => 'account', 'object' => 'user', 'method' => 'updateProfileImage']) ?>', data)
							.success(function (response) {

							});
				}
			}
	);
</script>