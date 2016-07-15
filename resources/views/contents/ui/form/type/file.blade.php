<?php
// http://plugins.krajee.com/file-basic-usage-demo
$label = $ui->getLabel();
$wrapperAttributes = $ui->renderHtmlAttributes($ui->wrapperAttributes());
$labelAttributes = $ui->renderHtmlAttributes($ui->labelAttributes());
$inputAttributes = $ui->renderHtmlAttributes($ui->inputAttributes());
?>
<div <?php echo $wrapperAttributes ?>>
	<?php if(zbase_is_angular_template()): ?>
		<span class="btn btn-primary" flow-btn><?php echo $label?></span>
	<?php else: ?>
		<label <?php echo $labelAttributes ?>><?php echo $label ?></label>
		<input <?php echo $inputAttributes ?> />
	<?php endif; ?>
	{!! view(zbase_view_file_contents('ui.form.helpblock'), compact('ui')) !!}
</div>