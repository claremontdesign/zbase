<?php
zbase_view_pagetitle_set('Dashboard', 'Dashboard');
?>
@extends(zbase_view_template_layout())
@section('content')
<div class="row">
	<?php echo zbase_view_placeholder_render('admin-dashboard-stats') ?>
</div>
<?php echo zbase_view_placeholder_render('admin-dashboard') ?>
@stop