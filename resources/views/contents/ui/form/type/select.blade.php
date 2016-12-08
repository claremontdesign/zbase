<?php
$label = $ui->getLabel();
$wrapperAttributes = $ui->renderHtmlAttributes($ui->wrapperAttributes());
$labelAttributes = $ui->renderHtmlAttributes($ui->labelAttributes());
$inputAttributes = $ui->renderHtmlAttributes($ui->inputAttributes());
$multiOptions = $ui->renderMultiOptions();
$inputWrapper = isset($options['inputWrapper']) ? $options['inputWrapper'] : true;
?>
<?php if(!empty($isDataFilter)):?>
	<div <?php echo $wrapperAttributes ?>>
<?php endif;?>
		<?php if($label !== false):?>
			<label <?php echo $labelAttributes ?>><?php echo $label ?></label>
		<?php endif;?>
	<select <?php echo $inputAttributes ?>>
		<?php echo $multiOptions ?>
	</select>
	{!! view(zbase_view_file_contents('ui.form.helpblock'), compact('ui')) !!}
<?php if(!empty($isDataFilter)):?>
</div>
<?php endif;?>