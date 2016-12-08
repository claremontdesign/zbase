<?php
$status = (integer) $ui->getValue();
$wrapperAttributes = $ui->renderHtmlAttributes($ui->wrapperAttributes());
$options = $ui->getAttribute('options');
$booleanOptions = zbase_data_get($options, 'boolean', [0 => 'No', 1 => 'Yes']);
$text = 'UNKNOWN';
$statuses = [
	0 => '<span class="label label-danger">' . (isset($booleanOptions[0]) ? $booleanOptions[0] : 'No') . '</span>',
	1 => '<span class="label label-success">' . (isset($booleanOptions[1]) ? $booleanOptions[1] : 'Yes') . '</span>',
];
if(array_key_exists($status, $statuses))
{
	$text = $statuses[$status];
}
?>
<span <?php echo $wrapperAttributes ?>>
	<?php echo $text ?>
</span>
