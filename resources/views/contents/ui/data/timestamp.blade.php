<?php
$date = !empty($date) ? $date : (!empty($value) && $value instanceof \Carbon\Carbon ? $value : null);
if(empty($date))
{
	return;
}
if(is_string($date))
{
	$date = zbase_date_from_db($date);
}
?>
<?php if(zbase_is_angular_template()): ?>
	<?php echo $date->format('Y-m-d H:i:s') ?>
<?php else: ?>
	<time datetime="<?php echo $date->format('Y-m-d H:i:s') ?>" title="<?php echo $date->format('F d, Y h:i A') ?>"><?php echo zbase_date_human($date) ?></time>
<?php endif?>