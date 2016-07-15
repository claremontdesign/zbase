<script type="text/javascript">
	app.controller('adminAuthController',
			function ($scope, $http, $window) {
				$scope.login = function () {
					$http.post('<?php echo zbase_api_url(['module' => 'account', 'object' => 'user', 'method' => 'login']) ?>', {paramOne: $scope.email, paramTwo: $scope.password})
							.success(function (response) {
								if (response.api.result.success !== undefined && response.api.result.success === true)
								{
									$window.location.href = '/admin';
								} else {
									FlashService.Error('Login error.');
								}
							});
				};
			}
	)
</script>