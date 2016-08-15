<?php
$prefix = 'notifications';
?>
<script type="text/javascript">
jQuery('#notification-<?php echo $prefix?>').mouseover(function(){
	if(jQuery(this).hasClass('updated'))
	{
		return;
	}
	jQuery(this).addClass('updated');
	var data = {loader: false};
	zbase_ajax_post('<?php echo zbase_url_from_route('admin.notifications', ['action' => 'seen'])?>', data, {}, {});
	jQuery('#notification-<?php echo $prefix?>-badge').text('');
});
</script>