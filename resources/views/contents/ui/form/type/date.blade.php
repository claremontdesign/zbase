<?php
zbase_view_plugin_load('bootstrap-datetime');
zbase_view_plugin_load('bootstrap-select');
$label = $ui->getLabel();
$wrapperAttributes = $ui->renderHtmlAttributes($ui->wrapperAttributes());
$labelAttributes = $ui->renderHtmlAttributes($ui->labelAttributes());
$inputAttributes = $ui->renderHtmlAttributes($ui->inputAttributes());
$inputAppend = $ui->getInputAppend();
$inputPrepend = $ui->getInputPrepend();
$inputType = $ui->getType();
$dateFormat = 'dd/mm/yyyy';
$options = $ui->getAttribute('option');
$isDateRange = !empty($options['daterange']) ? true : false;
$isDataFilter = !empty($options['dataFilter']) ? true : false;
?>
<?php if($inputType != 'hidden'): ?>
	<div <?php echo $wrapperAttributes ?>>
		<?php if($label !== false): ?>
			<label <?php echo $labelAttributes ?>><?php echo $label ?></label>
		<?php endif; ?>
		<?php if(empty($inputAppend) && !empty($inputPrepend)): ?>
			<div class="input-prepend">
				<span class="add-on"><?php echo $inputPrepend ?></span>
				<input <?php echo $inputAttributes ?> />
			</div>
		<?php endif; ?>
		<?php if(!empty($inputAppend) && empty($inputPrepend)): ?>
			<div class="input-append">
				<input <?php echo $inputAttributes ?> />
				<span class="add-on"><?php echo $inputAppend ?></span>
			</div>
		<?php endif; ?>
		<?php if(empty($inputAppend) && empty($inputPrepend)): ?>
			<div data-date-end-date="0d" class="input-group date date-picker <?php echo $isDateRange ? 'input-daterange' : '' ?>" data-date-format="<?php echo $dateFormat ?>">
				<input <?php echo $inputAttributes ?> readonly="" />
				<span class="input-group-btn">
					<button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
				</span>
			</div>
		<?php endif; ?>
		{!! view(zbase_view_file_contents('ui.form.helpblock'), compact('ui')) !!}
	</div>
<?php else: ?>
	<input <?php echo $inputAttributes ?> />
<?php endif; ?>