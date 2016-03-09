<?php
$label = $ui->getLabel();
$wrapperAttributes = $ui->renderHtmlAttributes($ui->wrapperAttributes());
$labelAttributes = $ui->renderHtmlAttributes($ui->labelAttributes());
$inputAttributes = $ui->renderHtmlAttributes($ui->inputAttributes());
?>
<div <?php echo $wrapperAttributes ?>>
    <label <?php echo $labelAttributes ?>><?php echo $label ?></label>
    <textarea <?php echo $inputAttributes ?>><?php echo $ui->getValue() ?></textarea>
	{!! view(zbase_view_file_contents('ui.form.helpblock'), compact('ui')) !!}
</div>