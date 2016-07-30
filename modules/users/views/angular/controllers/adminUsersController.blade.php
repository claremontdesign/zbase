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
if(!empty($adminUsers['serviceName']))
{
	$arguments[] = $adminUsers['serviceName'];
}
?>
<script type="text/javascript">
	app.controller('adminUsersController',
			function ($rootScope, $scope, $routeParams<?php echo !empty($arguments) ? ', ' . implode(',', $arguments) : null?>) {
				$rootScope.pageTitle = 'Manage Users';
				<?php echo !empty($scopes) ? implode("\n\n", $scopes) : ''?>
			});
			<?php echo !empty($factories) ? implode("\n\n",$factories) : ''?>
</script>