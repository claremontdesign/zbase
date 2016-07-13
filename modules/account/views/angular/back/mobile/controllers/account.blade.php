<script type="text/javascript">
	app.controller('adminAccountMainController',
			function ($rootScope, $scope, $http, $window, userService) {
				initController();
				function initController() {
					if ($rootScope.currentUser === undefined)
					{
						loadCurrentUser();
					}
				}
				function loadCurrentUser() {
					userService.getCurrentUser()
							.then(function (response) {
								$rootScope.currentUser = response.data.api.result.user;
							});
				}
				$rootScope.logout = function () {
					$http.get('<?php echo zbase_api_url(['module' => 'account', 'object' => 'user', 'method' => 'logout']) ?>')
							.success(function () {
								$window.location.reload();
							});
				};
				$rootScope.tabaccounttabsprofileClick = function () {
					$rootScope.tabClicked = 'tabaccounttabsprofile';
				}
				$rootScope.tabaccounttabsemailClick = function () {
					$rootScope.tabClicked = 'tabaccounttabsemail';
				}
				$rootScope.tabaccounttabspasswordClick = function () {
					$rootScope.tabClicked = 'tabaccounttabspassword';
				}
				$rootScope.tabaccounttabsimagesClick = function () {
					$rootScope.tabClicked = 'tabaccounttabsimages';
				}
				$scope.modifyAccount = function () {
					console.log($rootScope.tabClicked);
				}
			}
	);
</script>