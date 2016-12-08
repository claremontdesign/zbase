<?php
/**
	'label' => 'Date Order',
	'enable' => true,
	'options' => [
		'dateFormat' => 'm/d/Y'
	],
	'data' => [
		'type' => 'timestamp',
		'index' => 'DATE_ORDER'
	],
 */
$date = !empty($date) ? $date : (!empty($value) && $value instanceof \Carbon\Carbon ? $value : null);
if(!empty($ui))
{
	$options = $ui->getAttribute('options',[]);
}
$dateFormat = !empty($options) && !empty($options['dateFormat']) ? $options['dateFormat'] : 'Y-m-d H:i:s';
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
	<?php echo $date->format($dateFormat) ?>
<?php else: ?>
	<time datetime="<?php echo $date->format('Y-m-d H:i:s') ?>" title="<?php echo $date->format('F d, Y h:i A') ?>"><?php echo $date->format($dateFormat) ?><?php // echo zbase_date_human($date) ?></time>
<?php endif?>