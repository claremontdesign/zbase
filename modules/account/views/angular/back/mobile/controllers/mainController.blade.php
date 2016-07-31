<script type="text/javascript">
	initController();
	function initController() {
		userService.updateCurrentUser();
	}
	$rootScope.logout = function () {
		$http.get('<?php echo zbase_api_url(['module' => 'account', 'object' => 'user', 'method' => 'logout']) ?>')
				.success(function () {
					$window.location.reload();
				});
	};
</script>