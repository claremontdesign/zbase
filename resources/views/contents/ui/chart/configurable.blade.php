<?php foreach ($configurableForms as $element): ?>
	<?php
	$elementName = !empty($element['name']) ? $element['name'] : null;
	$elementType = !empty($element['type']) ? $element['type'] : 'text';
	$elementLabel = !empty($element['label']) ? $element['label'] : null;
	$elementPlaceholder = !empty($element['placeholder']) ? $element['placeholder'] : null;
	$elementValue = !empty($element['value']) ? $element['value'] : null;
	$selectOptions = !empty($element['options']) ? $element['options'] : null;
	$elementAttributes = [
		'value="' . $elementValue . '"',
		'placeholder="' . $elementPlaceholder . '"',
		'type="' . $elementType . '"',
	];
	?>
	<?php if($elementType == 'daterange'): ?>
		<?php
		$elementValueFrom = !empty($element['valuefrom']) ? $element['valuefrom'] : (!empty($startDate) && $startDate instanceof \DateTime ? $startDate->format('m/d/Y') : '');
		$elementValueTo = !empty($element['valueto']) ? $element['valueto'] : (!empty($endDate) && $endDate instanceof \DateTime ? $endDate->format('m/d/Y') : '');
		$elementRequired = isset($element['required']) ? $element['required'] : false;
		?>
		<div class="form-group">
			<label for="<?php echo $prefix . '_' . $elementName ?>">Date Range</label>
			<div class="input-group input-large date-picker input-daterange" data-date-format="mm/dd/yyyy">
				<input type="text" value="<?php echo $elementValueFrom ?>" class="form-control" id="<?php echo $prefix . '_' . $elementName ?>filter_from" name="from">
				<span class="input-group-addon">
					to
				</span>
				<input type="text" value="<?php echo $elementValueTo ?>"  class="form-control" id="<?php echo $prefix . '_' . $elementName ?>filter_from" name="to">
			</div>
		</div>
	<?php elseif($elementType == 'select' && !empty($selectOptions)): ?>
		<?php
		$elementEmptyOption = !empty($element['emptyOption']) ? $element['emptyOption'] : false;
		$elementEmptyOptionLabel = !empty($element['emptyOptionLabel']) ? $element['emptyOptionLabel'] : null;
		?>
		<div class="form-group">
			<label for="<?php echo $prefix . '_' . $elementName ?>"><?php echo $elementLabel ?></label>
			<select <?php echo!empty($elementRequired) ? ' required' : ''; ?> id="<?php echo $prefix . '_' . $elementName ?>" class="form-control" name="<?php echo $elementName ?>">
				<?php if(!empty($elementEmptyOption)): ?>
					<option value="" selected="selected"><?php echo!empty($elementEmptyOptionLabel) ? $elementEmptyOptionLabel : 'Select'; ?></option>
				<?php endif; ?>
				<?php foreach ($selectOptions as $optionId => $optionLabel): ?>
					<option value="<?php echo $optionId ?>"><?php echo $optionLabel ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	<?php else: ?>
		<div class="form-group">
			<label for="<?php echo $prefix . '_' . $elementName ?>"><?php echo $elementLabel ?></label>
			<input <?php echo implode(' ', $elementAttributes); ?> id="<?php echo $prefix . '_' . $elementName ?>" class="form-control" name="<?php echo $elementName ?>">
		</div>
	<?php endif; ?>
<?php endforeach; ?>
<button type="submit" id="<?php echo $prefix ?>ChartConfigurableSubmit" class="btn blue <?php echo $prefix ?>ChartConfigurableSubmit">Submit</button>
<button type="button" class="btn default <?php echo $prefix ?>ChartConfigurableClose">Close</button>
<br />
<br />