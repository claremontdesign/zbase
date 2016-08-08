<?php
$status = (integer) $ui->getValue();
$wrapperAttributes = $ui->renderHtmlAttributes($ui->wrapperAttributes());
$text = 'UNKNOWN';
$statuses = [
	0 => '<span class="bg-danger">Disapprove</span>',
	1 => '<span class="bg-warning">Disapprove</span>',
	2 => '<span class="bg-success">Approve</span>'
];
if(array_key_exists($status, $statuses))
{
	$text = $statuses[$status];
}
?>
<span <?php echo $wrapperAttributes ?>>
	<?php echo $text ?>
</span>
