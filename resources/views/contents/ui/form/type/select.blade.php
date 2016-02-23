<?php
$label = $ui->getLabel();
$wrapperAttributes = $ui->renderHtmlAttributes($ui->wrapperAttributes());
$labelAttributes = $ui->renderHtmlAttributes($ui->labelAttributes());
$inputAttributes = $ui->renderHtmlAttributes($ui->inputAttributes());
$multiOptions = $ui->renderMultiOptions();
?>
<div <?php echo $wrapperAttributes ?>>
    <label <?php echo $labelAttributes ?>><?php echo $label ?></label>
	<select <?php echo $inputAttributes ?>>
		<?php echo $multiOptions?>
	</select>
</div>