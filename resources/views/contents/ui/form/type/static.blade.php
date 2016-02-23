<?php
$label = $ui->getLabel();
$wrapperAttributes = $ui->renderHtmlAttributes($ui->wrapperAttributes());
$labelAttributes = $ui->renderHtmlAttributes($ui->labelAttributes());
$inputAttributes = $ui->renderHtmlAttributes($ui->inputAttributes());
?>
<div <?php echo $wrapperAttributes ?>>
    <label <?php echo $labelAttributes ?>><?php echo $label ?></label>
	<p class="form-control-static"><?php echo $ui->displayValue(); ?></p>
</div>