<?php
zbase_view_plugin_load('bootstrap-datetime');
zbase_view_plugin_load('bootstrap-select');
zbase_view_plugin_load('flotCharts');
// If true, will return the chart only
$reportTitle = !empty($reportTitle) ? $reportTitle : '';
$chartOnly = !empty($chartOnly) ? $chartOnly : false;
// If true chart data can be configured
$configurable = !empty($configurable) ? $configurable : true;
// Array of form types
$configurableForms = !empty($configurableForms) ? $configurableForms : [];
// If true, chart can be refreshed
$refreshable = !empty($refreshable) ? $refreshable : true;
$portletColor = !empty($portletColor) ? $portletColor : 'blue';
$saveToLocalStorage = !empty($saveToLocalStorage) ? $saveToLocalStorage : true;
?>
<?php if(!$chartOnly): ?>
	<div class="portlet box <?php echo $portletColor ?>">
		<div class="portlet-title">
			<div class="caption">
				<i class="fa fa-reorder"></i><?php echo $reportTitle ?>
			</div>
			<?php if($configurable || $refreshable): ?>
				<div class="tools">
					<?php if($configurable && !empty($configurableForms)): ?>
						<a href="javascript:jQuery('#<?php echo $prefix ?>ChartConfigurable').toggle();" class="config"></a>
					<?php endif; ?>
					<?php if($refreshable): ?>
						<a href="javascript:<?php echo $prefix ?>Chart(jQuery('#<?php echo $prefix ?>ChartConfigurableForm').serialize());" class="reload"></a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
		<div class="portlet-body">
			<?php if($configurable && !empty($configurableForms)): ?>
				<div class="portlet-configurable" id="<?php echo $prefix ?>ChartConfigurable" style="display:none;border-bottom:1px solid #e5e5e5;margin-bottom:20px;">
					<form method="post" action="" id="<?php echo $prefix ?>ChartConfigurableForm">
						<?php foreach ($configurableForms as $element): ?>
							<?php
							$elementName = !empty($element['name']) ? $element['name'] : null;
							$elementType = !empty($element['type']) ? $element['type'] : 'text';
							$elementLabel = !empty($element['label']) ? $element['label'] : null;
							$elementPlaceholder = !empty($element['placeholder']) ? $element['placeholder'] : null;
							$elementValue = !empty($element['value']) ? $element['value'] : null;
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
							<?php elseif($elementType == 'select'): ?>

							<?php else: ?>
								<div class="form-group">
									<label for="<?php echo $prefix . '_' . $elementName ?>"><?php echo $elementLabel ?></label>
									<input <?php echo implode(' ', $elementAttributes); ?> id="<?php echo $prefix . '_' . $elementName ?>" class="form-control" name="<?php echo $elementName ?>">
								</div>
							<?php endif; ?>
						<?php endforeach; ?>
						<button type="submit" id="<?php echo $prefix ?>ChartConfigurableSubmit" class="btn blue">Submit</button>
						<button type="button" onclick="jQuery('#<?php echo $prefix ?>ChartConfigurable').toggle();" class="btn default">Close</button>
						<br />
						<br />
					</form>
				</div>
			<?php endif; ?>
			<div id="<?php echo $prefix ?>Chart" class="chart"></div>
		</div>
	</div>
<?php else: ?>
	<div id="<?php echo $prefix ?>Chart" class="chart"></div>
<?php endif; ?>
<?php ob_start(); ?>
<script type="text/javascript">
	function <?php echo $prefix ?>Chart(formData)
	{
		App.blockUI({target: $('#<?php echo $prefix ?>Chart').parent(), boxed: true});
		$.ajax({
			dataType: 'json',
			type: 'post',
			url: '<?php echo $jsonDataUrl ?>',
			data: formData,
			success: function (data) {
				App.unblockUI($('#<?php echo $prefix ?>Chart').parent());
				$.plot($("#<?php echo $prefix ?>Chart"), data, <?php echo zbase_json_to_javascript($chartOptions) ?>);
					<?php if(!empty($saveToLocalStorage)): ?>
					$("#<?php echo $prefix ?>ChartConfigurableForm").find(':input').each(function () {
						if (jQuery(this).attr('name') !== undefined)
						{
							saveToLocalStorage('<?php echo $prefix ?>ChartConfigurableForm_' + jQuery(this).attr('name'), jQuery(this).val());
						}
					});
					<?php endif; ?>
			}
		});
	}
</script>
<?php zbase_view_script_add($prefix . 'Chart', ob_get_clean(), false);?>
<?php ob_start(); ?>
<script type="text/javascript">
	<?php if(!empty($saveToLocalStorage)): ?>
		$("#<?php echo $prefix ?>ChartConfigurableForm").find(':input').each(function () {
			if (jQuery(this).attr('name') !== undefined && getFromLocalStorage('<?php echo $prefix ?>ChartConfigurableForm_' + jQuery(this).attr('name')) !== undefined)
			{
				jQuery(this).val(getFromLocalStorage('<?php echo $prefix ?>ChartConfigurableForm_' + jQuery(this).attr('name')));
			}
		});
	<?php endif; ?>
	<?php echo $prefix ?>Chart(jQuery('#<?php echo $prefix ?>ChartConfigurableForm').serialize());
	jQuery('#<?php echo $prefix ?>ChartConfigurableForm').submit(function (e) {
		e.preventDefault();
		<?php echo $prefix ?>Chart(jQuery('#<?php echo $prefix ?>ChartConfigurableForm').serialize());
		return false;
	});
</script>
<?php
zbase_view_script_add($prefix . 'ChartOnload', ob_get_clean(), true);
?>