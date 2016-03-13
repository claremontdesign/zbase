@extends(zbase_view_template_layout())
@section('content')
Dashboard | <a href="{{ zbase_url_from_route('admin.logout') }}" title="Logout">Logout</a>
<br />
<br />
Modules
<?php
$modules = zbase()->modules();
if(!empty($modules))
{
	echo '<ul>';
	foreach ($modules as $moduleName => $module)
	{
		$module = zbase()->module($moduleName);
		if($module->isEnable() && $module->hasBackend())
		{
			echo '<li><a title="' . $module->title() . '" href="' . zbase_url_from_route('admin.' . $module->id()) . '">' . $module->title() . '</a></li>';
		}
	}
	echo '</ul>';
}
?>
@stop