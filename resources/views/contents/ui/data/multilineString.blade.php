<?php
$wrapperAttributes = $ui->renderHtmlAttributes($ui->wrapperAttributes());
$value = $ui->getValue();
?>
<span <?php echo $wrapperAttributes ?>>
	<?php echo nl2br($value) ?>
</span>
