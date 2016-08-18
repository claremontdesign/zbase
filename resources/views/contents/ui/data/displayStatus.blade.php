<?php
$status = (integer) $ui->getValue();
$wrapperAttributes = $ui->renderHtmlAttributes($ui->wrapperAttributes());
$text = 'UNKNOWN';
$statuses = [
	0 => '<span class="label label-danger">Hidden</span>',
	1 => '<span class="label label-warning">Draft</span>',
	2 => '<span class="label label-success">Published</span>'
];
if(array_key_exists($status, $statuses))
{
	$text = $statuses[$status];
}
?>
<span <?php echo $wrapperAttributes ?>> <?php echo $text ?> </span>
