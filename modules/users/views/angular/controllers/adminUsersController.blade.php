<?php
$adminUsers = zbase_angular_widget_datatable('users', 'admin-users');
$scopes = [];
$factories = [];
$arguments = [];
if(!empty($adminUsers['scope']))
{
	$scopes[] = $adminUsers['scope'];
}
if(!empty($adminUsers['factory']))
{
	$factories[] = $adminUsers['factory'];
}
if(!empty($adminUsers['name']))
{
	$arguments[] = $adminUsers['name'];
}
?>
<script type="text/javascript">
	app.controller('adminUsersController',
			function ($rootScope, $scope<?php echo !empty($arguments) ? ', ' . implode(',', $arguments) : null?>) {
				<?php // echo !empty($scopes) ? implode("\n\n", $scopes) : ''?>
				$scope.usersdatatableadminusersservice = UsersDatatableAdminUsersService;
			});
			<?php // echo !empty($factories) ? implode("\n\n",$factories) : ''?>

		app.factory('UsersDatatableAdminUsersService', UsersDatatableAdminUsersService);
		UsersDatatableAdminUsersService.$inject = ['$rootScope', '$http'];
		function UsersDatatableAdminUsersService($rootScope, $http)
		{
			var service = {};
			service.items = [];
			service.busy = false;
			service.page = 0;
			service.maxPage = 1;
			service.nextPage = nextPage;
			return service;
			function nextPage(){
				if(service.page == service.maxPage)
				{
					return;
				}
				if (service.busy)
				{
					return;
				}
				service.busy = true;
				$http.jsonp('http://zbase.com/admin/users?page=' + (service.page + 1) + '&jsonp=JSON_CALLBACK&angular=1').success(function (data) {
					if(data.UsersDatatableAdminUsersService !== undefined)
					{
						if(data.UsersDatatableAdminUsersService.rows !== undefined)
						{
							var items = data.UsersDatatableAdminUsersService.rows;
							for (var i = 0; i < items.length; i++)
							{
								service.items.push(items[i]);
							}
						}
						service.page = data.UsersDatatableAdminUsersService.page;
						service.maxPage = data.UsersDatatableAdminUsersService.maxPage;
					}
					service.busy = false;
			});
		}
	}
</script>