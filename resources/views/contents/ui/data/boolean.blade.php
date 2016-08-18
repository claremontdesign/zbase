<?php
$status = (integer) $ui->getValue();
$wrapperAttributes = $ui->renderHtmlAttributes($ui->wrapperAttributes());
$text = 'UNKNOWN';
$statuses = [
	0 => '<span class="label label-danger">No</span>',
	1 => '<span class="label label-success">Yes</span>',
];
if(array_key_exists($status, $statuses))
{
	$text = $statuses[$status];
}
?>
<span <?php echo $wrapperAttributes ?>>
	<?php echo $text ?>
</span>
