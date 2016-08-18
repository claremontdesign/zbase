<?php
$status = $ui->getValue();
$wrapperAttributes = $ui->renderHtmlAttributes($ui->wrapperAttributes());
$text = 'UNKNOWN';
$statuses = [
	'ok' => '<span class="label label-success">OK</span>',
	'ban' => '<span class="label label-warning">Banned</span>',
	'locked' => '<span class="label label-danger">Locked</span>',
	'ban_no_auth' => '<span class="label label-danger">DISABLED</span>',
];
if(array_key_exists($status, $statuses))
{
	$text = $statuses[$status];
}
?>
<span <?php echo $wrapperAttributes ?>> <?php echo $text ?> </span>
