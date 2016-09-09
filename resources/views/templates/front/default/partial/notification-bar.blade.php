<!-- BEGIN NOTIFICATION DROPDOWN -->
<?php
if(zbase_auth_has())
{
	$modules = zbase()->modules();
	$notifications = [];
	foreach ($modules as $module)
	{
		$notifications = array_merge($notifications, $module->getNotifications());
	}
	if(!empty($notifications))
	{
		$notifications = zbase_collection($notifications)->sortByDesc(function ($itm) {
					return $itm->getOrder();
					})->toArray();
		foreach ($notifications as $notification)
		{
			echo $notification;
		}
	}
}
?>
<!-- END NOTIFICATION DROPDOWN -->
