<script type="text/javascript">
	app.controller('adminAuthController',
			function ($rootScope, $scope, $http, $window) {
				$scope.login = function () {
					$rootScope.loading = true;
					$http.post('<?php echo zbase_api_url(['module' => 'account', 'object' => 'user', 'method' => 'login']) ?>', {paramOne: $scope.email, paramTwo: $scope.password})
							.success(function (response) {
								$rootScope.loading = false;
								if (response.api.result.success !== undefined && response.api.result.success === true)
								{
									$window.location.href = '/admin';
								}
							});
				};
				$scope.lostPassword = function () {
					$rootScope.loading = true;
					$http.post('<?php echo zbase_api_url(['module' => 'account', 'object' => 'user', 'method' => 'password']) ?>', {email: $scope.email})
							.success(function (response) {
								$rootScope.loading = false;
							});
				};
			}
	)
</script>