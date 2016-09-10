<?php
$prefix = 'notifications';
$route = 'admin.notifications';
?>
<script type="text/javascript">
jQuery('#notification-<?php echo $prefix?>').mouseover(function(){
	if(jQuery(this).hasClass('updated'))
	{
		return;
	}
	var data = {loader: false};
	zbase_ajax_post('<?php echo zbase_url_from_route($route, ['action' => 'seen'])?>', data, {}, {});
	jQuery('#notification-<?php echo $prefix?>-badge').text('');
	jQuery(this).addClass('updated');
});
setInterval(function () {
	zbase_ajax_post('<?php echo zbase_url_from_route($route, ['action' => 'fetch']) ?>', {loader: false}, function () {}, {});
}, 60000);
</script>