<?php
$label = $ui->getLabel();
$wrapperAttributes = $ui->renderHtmlAttributes($ui->wrapperAttributes());
$labelAttributes = $ui->renderHtmlAttributes($ui->labelAttributes());
$inputAttributes = $ui->renderHtmlAttributes($ui->inputAttributes());
?>
<div <?php echo $wrapperAttributes ?>>
	<label <?php echo $labelAttributes ?>>
		<input <?php echo $inputAttributes ?>>
			<?php echo $label ?>
	</label>
</div>