<?php
zbase_view_plugin_load('bootstrap-datetime');
zbase_view_plugin_load('bootstrap-select');
zbase_view_plugin_load('flotCharts');
$chartPlugin = !empty($chartPlugin) ? $chartPlugin : 'flotCharts';
$chartType = !empty($chartType) ? $chartType : 'pie';
$exportTable = !empty($exportTable) ? $exportTable : true;
$chartOptions = !empty($chartOptions) ? $chartOptions : [];
$reportTitle = !empty($reportTitle) ? $reportTitle : '';
$ajaxMethod = !empty($ajaxMethod) ? $ajaxMethod : 'post';
$loadChartOnLoad = isset($loadChartOnLoad) ? $loadChartOnLoad : true;
$configurableExpand = !empty($configurableExpand) ? $configurableExpand : false;
$fullScreenOnLoad = !empty($fullScreenOnLoad) ? $fullScreenOnLoad : false;
$chartTypeSelections = !empty($chartTypeSelections) ? $chartTypeSelections : [$chartType];
if($chartPlugin == 'flotCharts')
{
	zbase_view_plugin_load('flotCharts');
}
elseif($chartPlugin == 'amCharts')
{
	/**
	 * Pie
	 */
	$chartOptionsPie = [];
	$chartOptionsPie['type'] = 'pie';
	$chartOptionsPie['legend'] = !empty($chartLegend) ? $chartLegend : [
			'position' => 'right',
			'marginRight' => 100,
			'autoMargins' => true
		];
	$chartOptionsPie['labelsEnabled'] = !empty($amChartsLabelsEnabled) ? $amChartsLabelsEnabled : false;
	$chartOptionsPie['innerRadius'] = !empty($amChartsInnerRadius) ? $amChartsInnerRadius : '30%';
	$chartOptionsPie['responsive']['rules'][] = ['maxWidth' => 400, 'overrides' => ['legend' => ['enabled' => false]]];
	// 3d
	if(!empty($threeDEffect))
	{
		$chartOptionsPie['angle'] = !empty($amChartsAngle) ? $amChartsAngle : 15;
		$chartOptionsPie['depth3D'] = !empty($amChartsDepth) ? $amChartsDepth : 10;
	}

	/**
	 * Funnel
	 */
	$chartOptionsFunnel = [];
	$chartOptionsFunnel['type'] = 'funnel';
	$chartOptionsFunnel['marginRight'] = !empty($amChartsMarginRight) ? $amChartsMarginRight : '150';
	$chartOptionsFunnel['funnelAlpha'] = !empty($amChartsFunnelAlpha) ? $amChartsFunnelAlpha : 0.9;
	$chartOptionsFunnel['neckWidth'] = !empty($amChartsNeckWidth) ? $amChartsNeckWidth : '40%';
	$chartOptionsFunnel['neckHeight'] = !empty($amChartsNeckHeight) ? $amChartsNeckHeight : '30%';
	$chartOptionsFunnel['legend'] = !empty($chartLegend) ? $chartLegend : false;

	/**
	 * Column | Serial
	 */
	$chartOptionsColumn = $chartOptions;
	$chartOptionsColumn['type'] = 'serial';
	$chartOptionsColumn['gridAboveGraphs'] = true;
	$chartOptionsColumn['startDuration'] = 1;
	$chartOptionsColumn['categoryField'] = 'label';
	if(empty($chartOptions['valueAxes']))
	{
		$chartOptionsColumn['valueAxes'] = [[
			"gridColor" => "#FFFFFF",
			"gridAlpha" => 0.2,
			"dashLength" => 0
		]];
	}
	if(empty($chartOptions['graphs']))
	{
		$chartOptionsColumn['graphs'] = [[
			"balloonText" => "[[label]]: <b>[[value]]</b>",
			"fillAlphas" => 0.8,
			"lineAlpha" => 0.2,
			"fillColorsField" => "color",
			"type" => "column",
			"valueField" => "value"
		]];
	}
	if(empty($chartOptions['chartCursor']))
	{
		$chartOptionsColumn['chartCursor'] = [
			"labelBalloonEnabled" => false,
			"cursorAlpha" => 0,
			"zoomable" => false
		];
	}
	if(empty($chartOptions['labelAxis']))
	{
		$chartOptionsColumn['labelAxis'] = [
			"gridPosition" => "start",
			"gridAlpha" => 0,
			"tickPosition" => "start",
			"tickLength" => 20
		];
	}

	zbase_view_plugin_load('amCharts');
	$amChartTheme = !empty($amChartTheme) ? $amChartTheme : 'light';
	$chartOptions['type'] = $chartType;
	$chartOptions['responsive'] = ['enabled' => true];
	$chartOptions['autoTransform'] = true;
	$chartOptions['theme'] = $amChartTheme;
	$chartOptions['titleField'] = !empty($amChartsTheme) ? $amChartsTheme : 'label';
	$chartOptions['valueField'] = !empty($amChartsValueField) ? $amChartsValueField : 'data';
	$chartOptions['marginRight'] = !empty($amChartsMarginRight) ? $amChartsMarginRight : 0;
	$chartOptions['marginLeft'] = !empty($amChartsMarginLeft) ? $amChartsMarginLeft : 0;
	$chartOptions['labelPosition'] = !empty($amChartsLabelPosition) ? $amChartsLabelPosition : 'right';
	$chartOptions['startX'] = !empty($amChartsStartX) ? $amChartsStartX : 0;
	$chartOptions['baloonText'] = isset($amChartsBaloonText) ? $amChartsBaloonText : '[[label]]:[[data]]</b>';
	if(!empty($reportTitle))
	{
		//$chartOptions['titles'][] = ['text' => $reportTitle];
	}
	if(empty($chartOptions['baloonText']))
	{
		unset($chartOptions['baloonText']);
	}
	$chartOptions['startAlpha'] = !empty($amChartsStartAlpha) ? $amChartsStartAlpha : 0;
	$chartOptions['outlineThickness'] = !empty($amChartsOutlineThickness) ? $amChartsOutlineThickness : 1;
	$chartOptions['addClassNames'] = !empty($amChartsAddClassNames) ? $amChartsAddClassNames : false;

	$pieChartOptions = array_replace_recursive($chartOptions, $chartOptionsPie);
	$funnelChartOptions = array_replace_recursive($chartOptions, $chartOptionsFunnel);

	if($chartType == 'funnel')
	{
		$chartOptions = array_merge($chartOptions, $chartOptionsFunnel);
	}
	if($chartType == 'pie')
	{
		$chartOptions = array_merge($chartOptions, $chartOptionsPie);
	}
	if($chartType == 'column' || $chartType == 'serial')
	{
		$chartOptions = array_replace($chartOptions, $chartOptionsColumn);
	}
	if(!empty($exportTable))
	{
		$chartOptions['export'] = ['enabled' => true];
	}
	// dd($chartOptions);
	zbase_view_javascript_add('amchartTheme', zbase_path_asset('amcharts/themes/'. $amChartTheme .'.js'),null, null, 705);
}
// Content by Tabs
$tabs = !empty($tabs) ? $tabs : null;
// If true, will return the chart only
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
	<div class="portlet box <?php echo $portletColor ?> tabbable chartTabbable <?php echo !empty($exportTable) ? 'exporttable' : ''?>" id="<?php echo $prefix ?>">
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
							<div id="<?php echo $tabPrefix ?>Chart" class="chart <?php echo $chartPlugin?>"></div>
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
										type: '<?php echo $ajaxMethod?>',
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
							<?php if(!empty($loadChartOnLoad)):?>
								<?php echo $tabPrefix ?>ChartTabbable();
							<?php endif;?>
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
								<input type="hidden" value="<?php echo $reportType?>" name="reportType">
								<input type="hidden" value="json" name="format">
								<button type="submit" id="<?php echo $prefix ?>ChartConfigurableSubmit" class="btn blue">Submit</button>
								<button type="button" onclick="jQuery('#<?php echo $prefix ?>ChartConfigurableTabForm').toggle();jQuery('#<?php echo $prefix ?>ChartConfigurableBtnCustomize').show();" class="btn default">Close</button>
								<br />
								<br />
							</form>
							<h2 style="display:none;text-align:center;" id="<?php echo $prefix ?>ChartConfigurableTitle" class="chartTitle"></h2>
							<div id="<?php echo $prefix ?>ChartConfigurableTab" class="chart <?php echo $chartPlugin?>"></div>
							<?php ob_start(); ?>
							<script type="text/javascript">
								function <?php echo $prefix ?>ChartConfigurableTab(formData)
								{
									App.blockUI({target: $('#<?php echo $prefix ?>ChartConfigurableTab').parent(), boxed: true});
									$.ajax({
										dataType: 'json',
										type: '<?php echo $ajaxMethod?>',
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
									<?php if(!empty($loadChartOnLoad)):?>
										<?php echo $prefix ?>ChartConfigurableTab(jQuery('#<?php echo $prefix ?>ChartConfigurableTabForm').serialize());
									<?php endif;?>
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
							<a href="#" class="config  <?php echo $prefix ?>ChartConfigurableClose"></a>
						<?php endif; ?>
						<?php if($refreshable): ?>
							<a href="javascript:<?php echo $prefix ?>Chart(jQuery('#<?php echo $prefix ?>ChartConfigurableForm').serialize());" class="reload"></a>
						<?php endif; ?>
						<a href="#" class="fullscreen"></a>
					</div>
				<?php endif; ?>
				<?php if(!empty($actionHtmls)):?>
					<div class="actions">
						<?php foreach($actionHtmls as $actionHtml):?>
							<?php if(!empty($actionHtml['dropdown'])):?>
								<div class="btn-group open">
									<a data-toggle="dropdown" href="<?php echo !empty($actionHtml['href']) ? $actionHtml['href'] : '#'?>" class="btn <?php echo !empty($actionHtml['color']) ? $actionHtml['color'] : 'blue'?> btn-sm">
										<i class="fa <?php echo !empty($actionHtml['icon']) ? $actionHtml['icon'] : ''?>"></i> <?php echo !empty($actionHtml['label']) ? $actionHtml['label'] : 'Action'?> <i class="fa fa-angle-down"></i>
									</a>
									<ul class="dropdown-menu pull-right">
										<?php foreach($actionHtml['dropdown'] as $subActionHtml):?>
											<li>
												<a href="<?php echo !empty($subActionHtml['href']) ? $subActionHtml['href'] : ''?>">
													<i class="fa <?php echo !empty($subActionHtml['icon']) ? $subActionHtml['icon'] : ''?>"></i> <?php echo !empty($subActionHtml['label']) ? $subActionHtml['label'] : ''?>
												</a>
											</li>
										<?php endforeach;?>
									</ul>
								</div>
							<?php else:?>
								<a href="<?php echo !empty($actionHtml['href']) ? $actionHtml['href'] : '#'?>" class="btn <?php echo !empty($actionHtml['color']) ? $actionHtml['color'] : 'blue'?> btn-sm">
									<i class="fa <?php echo !empty($actionHtml['icon']) ? $actionHtml['icon'] : ''?>"></i> <?php echo !empty($actionHtml['label']) ? $actionHtml['label'] : 'Action'?>
								</a>
							<?php endif;?>
						<?php endforeach;?>
					</div>
				<?php endif;?>
			</div>
			<div class="portlet-body" id="<?php echo $prefix?>-portlet-body">
				<?php if($configurable && !empty($configurableForms)): ?>
					<div class="portlet-configurable" id="<?php echo $prefix ?>ChartConfigurable" style="display:<?php echo $configurableExpand ? 'block' : 'none'?>;border-bottom:1px solid #e5e5e5;margin-bottom:20px;">
						<form method="post" action="" id="<?php echo $prefix ?>ChartConfigurableForm">
							@include(zbase_view_file_contents('ui.chart.configurable'))
						</form>
					</div>
				<?php endif; ?>
				<?php if(!empty($chartTypeSelections) && count($chartTypeSelections) > 1):?>

					<div class="form-group" id="<?php echo $prefix . 'chartTypeFormGroup' ?>" style="display:none;">
						<p class="form-control-static">
							<a href="#" class="<?php echo $prefix ?>ChartConfigurableClose">Customize</a> |
							Chart Type:
							<?php $chartTypeSelectionsHtml = [];?>
							<?php foreach ($chartTypeSelections as $chartTypeSelection): ?>
								<?php
								if(is_array($chartTypeSelection))
								{
									if(!empty($chartTypeSelection['subTypes']))
									{
										$chartTypeSelectionSubTypes = $chartTypeSelection['subTypes'];
										$chartTypeSelection = $chartTypeSelection['type'];
										foreach($chartTypeSelectionSubTypes as $chartTypeSelectionSubType)
										{
											$chartTypeSelectionSubTypeLabel = !empty($chartTypeSelectionSubType['label']) ? $chartTypeSelectionSubType['label'] : ucfirst($chartTypeSelectionSubType);
											$chartTypeSelectionSubTypeType = !empty($chartTypeSelectionSubType['type']) ? $chartTypeSelectionSubType['type'] : $chartTypeSelectionSubType;
											$chartTypeSelectionsHtml[] = '<a onclick="'.$prefix.'Chart'.$chartTypeSelection.'(\''.$chartTypeSelectionSubType.'\')" href="javascript:void(0)">' . $chartTypeSelectionSubTypeLabel . '</a>';
										}
										continue;
									}
								}
								else
								{
									$chartTypeSelectionsHtml[] = '<a onclick="'.$prefix.'Chart'.$chartTypeSelection.'()" href="javascript:void(0)">'.ucwords(strtolower($chartTypeSelection)).'</a>';
								}
								?>
							<?php endforeach; ?>
							<?php echo implode(' | ', $chartTypeSelectionsHtml);?>
						</p>
					</div>
				<?php endif;?>
				<div id="<?php echo $prefix ?>Chart<?php echo $chartType?>" class="chart <?php echo $prefix ?>Charts <?php echo $chartPlugin?>"></div>
				<?php if(!empty($chartTypeSelections)):?>
					<?php foreach ($chartTypeSelections as $chartTypeSelection): ?>
						<?php
						if(is_array($chartTypeSelection))
						{
							$chartTypeSelection = $chartTypeSelection['type'];
						}
						?>
						<?php if($chartType !== $chartTypeSelection):?>
						<div style="display:none;" id="<?php echo $prefix ?>Chart<?php echo $chartTypeSelection?>" class="chart <?php echo $prefix ?>Charts <?php echo $chartPlugin?>"></div>
						<?php endif;?>
					<?php endforeach;?>
				<?php endif;?>
			</div>
		</div>
	<?php else: ?>
		<div id="<?php echo $prefix ?>Chart" class="chart <?php echo $chartPlugin?>"></div>
	<?php endif; ?>
	<?php ob_start(); ?>
	<script type="text/javascript">
		var <?php echo $prefix ?>data = null;
		function <?php echo $prefix ?>Chart(formData)
		{
			App.blockUI({target: $('#<?php echo $prefix?>-portlet-body'), boxed: true});
			$.ajax({
				dataType: 'json',
				type: '<?php echo $ajaxMethod?>',
				url: '<?php echo $jsonDataUrl ?>',
				data: formData,
				beforeSend: function(){
					$('#<?php echo $prefix . 'chartTypeFormGroup' ?>').hide();
				},
				success: function (data) {
					$('#<?php echo $prefix . 'chartTypeFormGroup' ?>').show();
					<?php echo $prefix ?>data = data;
					App.unblockUI($('#<?php echo $prefix?>-portlet-body'));
					<?php if($chartPlugin == 'flotCharts'):?>
					$.plot($("#<?php echo $prefix ?>Chart"), data, <?php echo zbase_json_to_javascript($chartOptions) ?>);
					<?php endif;?>
					<?php if($chartPlugin == 'amCharts'):?>
						<?php echo $prefix ?>Chart<?php echo $chartType?>();
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
		<?php if(!empty($chartTypeSelections)):?>
			<?php foreach ($chartTypeSelections as $chartTypeSelection): ?>
				<?php
				if(is_array($chartTypeSelection))
				{
					$chartTypeSelection = $chartTypeSelection['type'];
				}
				?>
				function <?php echo $prefix ?>Chart<?php echo $chartTypeSelection?>(subType)
				{
					<?php if($chartTypeSelection == 'pie'):?>
						<?php $chartTypeSelectionOptions = $pieChartOptions; ?>
					<?php elseif($chartTypeSelection == 'funnel'):?>
						<?php $chartTypeSelectionOptions = $funnelChartOptions; ?>
					<?php elseif($chartTypeSelection == 'serial' || $chartTypeSelection == 'column'):?>
						<?php $chartTypeSelectionOptions = $chartOptionsColumn; ?>
					<?php endif;?>
					<?php $chartTypeSelectionOptions['dataProvider'] = '@@' . $prefix . 'data.data@@';?>
					jQuery('.<?php echo $prefix ?>Charts').hide();
					jQuery('#<?php echo $prefix ?>Chart<?php echo $chartTypeSelection?>').show();
					var chart = AmCharts.makeChart("<?php echo $prefix ?>Chart<?php echo $chartTypeSelection?>", <?php echo zbase_json_to_javascript($chartTypeSelectionOptions) ?>);
					if(subType !== undefined)
					{
						chart.graphs[0].type = subType;
					}
					chart.validateNow();
					chart.invalidateSize();
					chart.animateAgain();
				}
			<?php endforeach;?>
		<?php endif;?>
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
	<?php if(!empty($loadChartOnLoad)):?>
	<?php echo $prefix ?>Chart(jQuery('#<?php echo $prefix ?>ChartConfigurableForm').serialize());
	<?php endif;?>
		jQuery('#<?php echo $prefix ?>ChartConfigurableForm').submit(function (e) {
			e.preventDefault();
			<?php echo $prefix ?>Chart(jQuery('#<?php echo $prefix ?>ChartConfigurableForm').serialize());
			return false;
		});
		<?php echo !empty($fullScreenOnLoad) ? 'jQuery(\'.fullscreen\').trigger(\'click\');' : ''?>
		jQuery('.<?php echo $prefix ?>ChartConfigurableClose').click(function(e){
			e.preventDefault();
			jQuery('#<?php echo $prefix ?>ChartConfigurable').toggle();
		});
	</script>
	<?php zbase_view_script_add($prefix . 'ChartOnload', ob_get_clean(), true); ?>
<?php endif; ?>
