<?php
$status = (integer) $ui->getValue();
$wrapperAttributes = $ui->renderHtmlAttributes($ui->wrapperAttributes());
$text = 'UNKNOWN';
$statuses = [
	0 => '<span class="bg-danger">Hidden</span>',
	1 => '<span class="bg-warning">Draft</span>',
	2 => '<span class="bg-success">Published</span>'
];
if(array_key_exists($status, $statuses))
{
	$text = $statuses[$status];
}
?>
<span <?php echo $wrapperAttributes ?>>
	<?php echo $text ?>
</span>
