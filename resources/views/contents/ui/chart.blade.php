<?php
zbase_view_plugin_load('bootstrap-datetime');
zbase_view_plugin_load('bootstrap-select');
zbase_view_plugin_load('flotCharts');
$chartPlugin = !empty($chartPlugin) ? $chartPlugin : 'flotCharts';
$chartType = !empty($chartType) ? $chartType : 'pie';
$exportTable = !empty($exportTable) ? $exportTable : false;
$chartOptions = !empty($chartOptions) ? $chartOptions : [];
if($chartPlugin == 'flotCharts')
{
	zbase_view_plugin_load('flotCharts');
}
elseif($chartPlugin == 'amCharts')
{
	zbase_view_plugin_load('amCharts');
	$amChartTheme = !empty($amChartTheme) ? $amChartTheme : 'light';
	$chartOptions['type'] = $chartType;
	$chartOptions['responsive'] = ['enabled' => true];
	$chartOptions['autoTransform'] = true;
	$chartOptions['theme'] = !empty($amChartsTheme) ? $amChartsTheme : 'none';
	$chartOptions['titleField'] = !empty($amChartsTheme) ? $amChartsTheme : 'label';
	$chartOptions['valueField'] = !empty($amChartsValueField) ? $amChartsValueField : 'data';
	$chartOptions['marginRight'] = !empty($amChartsMarginRight) ? $amChartsMarginRight : 0;
	$chartOptions['marginLeft'] = !empty($amChartsMarginLeft) ? $amChartsMarginLeft : 0;
	$chartOptions['labelPosition'] = !empty($amChartsLabelPosition) ? $amChartsLabelPosition : 'right';
	$chartOptions['startX'] = !empty($amChartsStartX) ? $amChartsStartX : 0;
	$chartOptions['baloonText'] = isset($amChartsBaloonText) ? $amChartsBaloonText : '[[label]]: [[data]]</b>';
	if(empty($chartOptions['baloonText']))
	{
		unset($chartOptions['baloonText']);
	}
	$chartOptions['startAlpha'] = !empty($amChartsStartAlpha) ? $amChartsStartAlpha : 0;
	$chartOptions['outlineThickness'] = !empty($amChartsOutlineThickness) ? $amChartsOutlineThickness : 1;
	$chartOptions['addClassNames'] = !empty($amChartsAddClassNames) ? $amChartsAddClassNames : false;
	if($chartType == 'funnel')
	{
		$chartOptions['funnelAlpha'] = !empty($amChartsFunnelAlpha) ? $amChartsFunnelAlpha : 0.9;
		$chartOptions['neckWidth'] = !empty($amChartsNeckWidth) ? $amChartsNeckWidth : '40%';
		$chartOptions['neckHeight'] = !empty($amChartsNeckHeight) ? $amChartsNeckHeight : '30%';
		$chartOptions['legend'] = !empty($chartLegend) ? $chartLegend : false;
	}
	if($chartType == 'pie')
	{
		$chartOptions['legend'] = !empty($chartLegend) ? $chartLegend : [
				'position' => 'right',
				'marginRight' => 100,
				'autoMargins' => true
			];
		$chartOptions['labelsEnabled'] = !empty($amChartsLabelsEnabled) ? $amChartsLabelsEnabled : false;
		$chartOptions['innerRadius'] = !empty($amChartsInnerRadius) ? $amChartsInnerRadius : '30%';
		$chartOptions['responsive']['rules'] = ['maxWidth' => 400, 'overrides' => ['legend' => ['enabled' => false]]];
	}
	if(!empty($exporttable))
	{
		$chartOptions['export'] = ['enabled' => true];
	}
	zbase_view_javascript_add('amchartTheme', zbase_path_asset('amcharts/themes/'. $amChartTheme .'.js'),null, null, 705);
}
// Content by Tabs
$tabs = !empty($tabs) ? $tabs : null;
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
<?php if(!empty($tabs)): ?>
	<div class="portlet box <?php echo $portletColor ?> tabbable chartTabbable" id="<?php echo $prefix ?>">
		<div class="portlet-title">
			<div class="caption">
				<i class="fa fa-reorder"></i><?php echo $reportTitle ?>
			</div>
			<div class="tools">
				<a href="#" class="fullscreen"></a>
			</div>
		</div>
		<div class="portlet-body">
			<div class="tabbable portlet-tabs">
				<ul class="nav nav-tabs">
					<?php if($configurable && !empty($configurableForms)): ?>
						<li>
							<a href="#<?php echo $prefix ?>ConfigurableTab" data-toggle="tab">
								Custom
							</a>
						</li>
					<?php endif; ?>
					<?php $tabCounter = 0; ?>
					<?php foreach ($tabs as $tabPrefix => $tabConfig): ?>
						<?php
						$tabLabel = $tabConfig['label'];
						$tabActive = $tabCounter == (count($tabs) - 1) ? true : false;
						?>
						<li class="<?php echo $tabActive ? 'active' : null ?>">
							<a href="#<?php echo $tabPrefix ?>" data-toggle="tab" data-showcallback="<?php echo $tabPrefix ?>ChartTabbable">
								<?php echo $tabLabel ?>
							</a>
						</li>
						<?php $tabCounter++; ?>
					<?php endforeach; ?>
				</ul>
				<div class="tab-content">
					<?php $tabCounter = 0; ?>
					<?php foreach ($tabs as $tabPrefix => $tabConfig): ?>
						<?php
						$tabUrl = $tabConfig['url'];
						$tabActive = $tabCounter == (count($tabs) - 1) ? true : false;
						?>
						<div class="tab-pane <?php echo $tabActive ? 'active' : null ?>" id="<?php echo $tabPrefix ?>">
							<h2 style="display:none;text-align:center;" id="<?php echo $tabPrefix ?>ChartTitle" class="chartTitle"></h2>
							<div id="<?php echo $tabPrefix ?>Chart" class="chart"></div>
						</div>
						<?php ob_start(); ?>
						<script type="text/javascript">
							function <?php echo $tabPrefix ?>ChartTabbable()
							{
								if (!$("#<?php echo $tabPrefix ?>Chart").hasClass('chartLoaded') && $("#<?php echo $tabPrefix ?>Chart").is(':visible'))
								{
									App.blockUI({target: $('#<?php echo $tabPrefix ?>Chart').parent(), boxed: true});
									$.ajax({
										dataType: 'json',
										type: 'post',
										url: '<?php echo $tabUrl; ?>',
										success: function (data) {
											App.unblockUI($('#<?php echo $tabPrefix ?>Chart').parent());
											<?php if($chartPlugin == 'flotCharts'):?>
											$.plot($("#<?php echo $tabPrefix ?>Chart"), data.data, <?php echo zbase_json_to_javascript($chartOptions) ?>);
											<?php endif;?>
											<?php if($chartPlugin == 'amCharts'):?>
												<?php $chartOptions['dataProvider'] = 'data.data';?>
												AmCharts.makeChart( "<?php echo $tabPrefix ?>Chart", <?php echo zbase_json_to_javascript($chartOptions) ?>);
											<?php endif;?>
											$("#<?php echo $tabPrefix ?>Chart").addClass('chartLoaded');
											if (data.title !== undefined)
											{
												jQuery("#<?php echo $tabPrefix ?>ChartTitle").text(data.title).show();
											}
										}
									});
								}
							}
						</script>
						<?php zbase_view_script_add($tabPrefix . 'Chart', ob_get_clean(), false); ?>
						<?php ob_start(); ?>
						<script type="text/javascript">
							<?php echo $tabPrefix ?>ChartTabbable();
						</script>
						<?php zbase_view_script_add($tabPrefix . 'ChartOnload', ob_get_clean(), true); ?>
						<?php $tabCounter++; ?>
					<?php endforeach; ?>
					<?php if($configurable && !empty($configurableForms)): ?>
						<div class="tab-pane" id="<?php echo $prefix ?>ConfigurableTab">
							<button type="button" style="display:none;position:absolute;" id="<?php echo $prefix ?>ChartConfigurableBtnCustomize" onclick="jQuery('#<?php echo $prefix ?>ChartConfigurableTabForm').toggle();jQuery('#<?php echo $prefix ?>ChartConfigurableBtnCustomize').hide();" class="btn default btn-chart-customize">Customize</button>
							<form method="post" action="" id="<?php echo $prefix ?>ChartConfigurableTabForm" style="display:block;border-bottom:2px solid #EBEBEB;">
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
								<button type="button" onclick="jQuery('#<?php echo $prefix ?>ChartConfigurableTabForm').toggle();jQuery('#<?php echo $prefix ?>ChartConfigurableBtnCustomize').show();" class="btn default">Close</button>
								<br />
								<br />
							</form>
							<h2 style="display:none;text-align:center;" id="<?php echo $prefix ?>ChartConfigurableTitle" class="chartTitle"></h2>
							<div id="<?php echo $prefix ?>ChartConfigurableTab" class="chart"></div>
							<?php ob_start(); ?>
							<script type="text/javascript">
								function <?php echo $prefix ?>ChartConfigurableTab(formData)
								{
									App.blockUI({target: $('#<?php echo $prefix ?>ChartConfigurableTab').parent(), boxed: true});
									$.ajax({
										dataType: 'json',
										type: 'post',
										url: '<?php echo $jsonBaseUrl ?>',
										data: formData,
										success: function (data) {
											App.unblockUI($('#<?php echo $prefix ?>ChartConfigurableTab').parent());
											<?php if($chartPlugin == 'flotCharts'):?>
											$.plot($("#<?php echo $prefix ?>ChartConfigurableTab"), data.data, <?php echo zbase_json_to_javascript($chartOptions) ?>);
											<?php endif; ?>
											<?php if($chartPlugin == 'amCharts'):?>
												<?php $chartOptions['dataProvider'] = 'data.data';?>
												AmCharts.makeChart( "<?php echo $prefix ?>ChartConfigurableTab", <?php echo zbase_json_to_javascript($chartOptions) ?>);
											<?php endif;?>
											if (data.title !== undefined)
											{
												jQuery("#<?php echo $prefix ?>ChartConfigurableTitle").text(data.title).show();
											}
											<?php if(!empty($saveToLocalStorage)): ?>
												$('#<?php echo $prefix ?>ChartConfigurableTabForm').find(':input').each(function () {
													if (jQuery(this).attr('name') !== undefined)
													{
														saveToLocalStorage('<?php echo $prefix ?>ChartConfigurableTabForm_' + jQuery(this).attr('name'), jQuery(this).val());
													}
												});
											</script>
										<?php endif; ?>
										}
									});
								}
							</script>
							<?php zbase_view_script_add($prefix . 'ChartConfigurableTab', ob_get_clean(), false); ?>
							<?php ob_start(); ?>
							<script type="text/javascript">
									<?php if(!empty($saveToLocalStorage)): ?>
									$("#<?php echo $prefix ?>ChartConfigurableTabForm").find(':input').each(function () {
										if (jQuery(this).attr('name') !== undefined && getFromLocalStorage('<?php echo $prefix ?>ChartConfigurableTabForm_' + jQuery(this).attr('name')) !== null)
										{
											jQuery(this).val(getFromLocalStorage('<?php echo $prefix ?>ChartConfigurableTabForm_' + jQuery(this).attr('name')));
										}
									});
									<?php endif; ?>
								jQuery('#<?php echo $prefix ?>ChartConfigurableTabForm').submit(function (e) {
									e.preventDefault();
											<?php echo $prefix ?>ChartConfigurableTab(jQuery('#<?php echo $prefix ?>ChartConfigurableTabForm').serialize());
											return false;
										});
									<?php echo $prefix ?>ChartConfigurableTab(jQuery('#<?php echo $prefix ?>ChartConfigurableTabForm').serialize());
							</script>
							<?php zbase_view_script_add($prefix . 'ChartConfigurableTabOnload', ob_get_clean(), true); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>

<?php else: ?>
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
						<a href="#" class="fullscreen"></a>
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
					<?php if($chartPlugin == 'flotCharts'):?>
					$.plot($("#<?php echo $prefix ?>Chart"), data, <?php echo zbase_json_to_javascript($chartOptions) ?>);
					<?php endif;?>
					<?php if($chartPlugin == 'amCharts'):?>
						<?php $chartOptions['dataProvider'] = 'data.data';?>
						AmCharts.makeChart( "<?php echo $prefix ?>Chart", <?php echo zbase_json_to_javascript($chartOptions) ?>);
					<?php endif;?>
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
	<?php zbase_view_script_add($prefix . 'Chart', ob_get_clean(), false); ?>
	<?php ob_start(); ?>
	<script type="text/javascript">
			<?php if(!empty($saveToLocalStorage)): ?>
			$("#<?php echo $prefix ?>ChartConfigurableForm").find(':input').each(function () {
				if (jQuery(this).attr('name') !== undefined && getFromLocalStorage('<?php echo $prefix ?>ChartConfigurableForm_' + jQuery(this).attr('name')) !== null)
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
	<?php zbase_view_script_add($prefix . 'ChartOnload', ob_get_clean(), true); ?>
<?php endif; ?>