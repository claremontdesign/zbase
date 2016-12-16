<?php
/**
 * $template = will return the template only
 */
$tableId = $ui->id();
$columns = $ui->getProcessedColumns();
$rows = $ui->getRows();
$rowCount = count($rows);
$columnCount = count($columns);
$hasActions = $ui->hasActions();
$isClickableRows = $ui->isRowsClickable();
$isClickableToNextRow = $ui->isRowsClickableToNextRow();
$isRowsToNextRowReplaceContent = $ui->isRowsToNextRowReplaceContent();
$paginationLoadMore = $ui->hasPaginationLoadMore();
$tableClasses = zbase_config_get('ui.table.html.attributes.class', 'table table-hover zbase-table-responsive');
$hasFilters = false;
$tHeadsFilters = [];
if(!empty($hasActions))
{
	$columnCount++;
}

$tHeads = [];
$tBodys = [];
$tFoots = [];
if(!empty($columns))
{
	if(!empty($template))
	{
		$columnConfig = [];
		$rowCount = true;
		$clickableRow = '';
		if($isClickableRows)
		{
			$clickableRow = ' class="pointer" onclick="zbase_to_url(this);" data-href="' . $ui->getRowClickableUrl(null, $template) . '"';
		}
		if($isClickableToNextRow)
		{
			$clickableRow = ' class="zbase-datatable-row-toggle" ' . (!empty($isRowsToNextRowReplaceContent) ? 'data-content="1"' : null) . ' data-href="' . $ui->getRowClickableUrl(null, $template) . '"';
		}

		if($ui->entity() instanceof \Zbase\Post\PostInterface)
		{
			$tBodys[] = '<tr' . $clickableRow . ' id="rowPostMainContentWrapper' . $ui->entity()->postHtmlTemplateId() . '">';
		}
		else
		{
			$tBodys[] = '<tr id="' . $prefix . 'RowId__' . $ui->rowValueIndex() . '__"' . $clickableRow . '>';
		}
        if($isClickableToNextRow)
        {
            $tBodys[] = '<td class="row-details-toggler"><span class="row-details row-details-close"></span></td>';
        }
		foreach ($columns as $column)
		{
			$column->setTemplateMode(true)->prepare();
			$columnConfig[$column->id()] = $column->getDataType();
			$tBodys[] = $column->renderValue('td');
			$column->setTemplateMode(false);
		}
		if(!empty($hasActions))
		{
			$tBodys[] = '<td>' . $ui->renderRowActions(null, true) . '</td>';
		}
		$tBodys[] = '</tr>';
        if($isClickableToNextRow)
        {
            $tHeads[] = '<th class="clickableColumn" style="width:10px;">&nbsp;</th>';
        }
		foreach ($columns as $column)
		{
			if($column->filterable())
			{
				$hasFilters = true;
			}
			$tHeads[] = '<th ' . $column->renderTagAttribute('th') . '>' . $column->getLabel() . '</th>';
		}
	}
	else
	{
		if(!empty($rowCount))
		{
			foreach ($rows as $row)
			{
				$clickableRow = '';
				if($isClickableRows)
				{
					$clickableRow = ' class="pointer zbase-datatable-clickable-row" onclick="zbase_to_url(this);" data-href="' . $ui->getRowClickableUrl($row) . '"';
				}
				if($isClickableToNextRow)
				{
					$clickableRow = ' class="zbase-datatable-row-toggle" ' . (!empty($isRowsToNextRowReplaceContent) ? 'data-content="1"' : null) . ' data-href="' . $ui->getRowClickableUrl($row) . '"';
				}
				if($row instanceof \Zbase\Post\PostInterface)
				{
					$tBodys[] = '<tr' . $clickableRow . ' id="rowPostMainContentWrapper' . $row->postHtmlId() . '">';
				}
				else
				{
					$tBodys[] = '<tr' . $clickableRow . '>';
				}
                if($isClickableToNextRow)
                {
                    $tBodys[] = '<td class="row-details-toggler"><span class="pointer row-details row-details-close"></span></td>';
                }
				foreach ($columns as $column)
				{
					$column->setRow($row)->prepare();
					$tBodys[] = $column->renderValue('td');
				}
				if(!empty($hasActions))
				{
					$tBodys[] = '<td>' . $ui->renderRowActions($row) . '</td>';
				}
				$tBodys[] = '</tr>';
			}
		}
        if($isClickableToNextRow)
        {
            $tHeads[] = '<th class="clickableColumn" style="width:10px;">&nbsp;</th>';
        }
		foreach ($columns as $column)
		{
			if($column->filterable())
			{
				$hasFilters = true;
			}
			$tHeads[] = '<th ' . $column->renderTagAttribute('th') . '>' . $column->getLabel() . '</th>';
		}
	}
}
if(!empty($hasFilters))
{
	$tableId = $ui->getWidgetPrefix('search');
	$filterPrefix = $ui->getWidgetPrefix('filter');
    if($isClickableToNextRow)
    {
        $tHeadsFilters[] = '<td>&nbsp;</td>';
    }
	foreach ($columns as $column)
	{
		if($column->filterable())
		{
			$tHeadsFilters[] = '<td ' . $column->renderTagAttribute('td') . '>' . $column->renderFilterElement() . '</td>';
		}
		else
		{
			$tHeadsFilters[] = '<td ' . $column->renderTagAttribute('td') . '>&nbsp;</th>';
		}
	}
}
if(!empty($hasActions))
{
	$tHeads[] = '<th>&nbsp;</th>';
}
if($paginationLoadMore)
{
    $columnCount = count($tHeads);
	$tFoots[] = '<tr><td colspan="' . $columnCount . '"><div class="alert alert-warning"><a href="" id="' . $tableId . 'LoadMoreAnchor">Load more...</a></div></td></tr>';
}
?>
<?php if(!empty($template)): ?>
	<?php if(!empty($columnConfig)): ?>
		var <?php echo $prefix ?>Columns = <?php echo json_encode($columnConfig, true) ?>;
	<?php endif; ?>
	var <?php echo $prefix ?>TemplateToolbar = '<?php echo zbase_view_render(zbase_view_file_contents('ui.datatable.toolbar'), ['ui' => $ui, 'template' => true]); ?>';
	var <?php echo $prefix ?>TemplateTable = '<table class="table table-hover flip-content <?php echo $tableClasses?>" id="<?php echo $prefix ?>Table">
		<thead class="flip-content">
			<tr>
			<?php echo implode("\n", $tHeads); ?>
			</tr>
				<?php if(!empty($hasFilters)): ?>
				<tr style="border-bottom: 2px solid black;" role="row" class="filter zbase-data-filters" id="<?php echo $filterPrefix ?>TrDataFilters">
				<?php echo implode("\n", $tHeadsFilters); ?>
				</tr>
	<?php endif; ?>
		</thead>
		<tfoot>
			<tr>
	<?php echo implode("\n", $tFoots); ?>
			</tr>
		</tfoot>
		<tbody></tbody>
	</table>';
	var <?php echo $prefix ?>TemplateTableRow = '<?php echo implode("\n", $tBodys); ?>';
	var <?php echo $prefix ?>EmptyMessage = '<?php echo zbase_view_render($ui->emptyViewFile()); ?>';

<?php else: ?>
	<?php if(!empty($rowCount)): ?>
				<?php if(!empty($columns)): ?>
			<style type="text/css">
				@media screen and (max-width: 767px){
					<?php $colCounter = 1; ?>
					<?php foreach ($columns as $column): ?>
						td:nth-of-type(<?php echo $colCounter++; ?>):before { content: "<?php echo $column->getLabel() ?>:";  }
			<?php endforeach; ?>
				}
			</style>
			<table class="<?php echo $tableClasses?>" id="<?php echo $tableId ?>Table">
				<thead>
					<tr>
					<?php echo implode("\n", $tHeads); ?>
					</tr>
						<?php if(!empty($hasFilters)): ?>
						<tr style="border-bottom: 2px solid black;" role="row" class="filter zbase-data-filters">
						<?php echo implode("\n", $tHeadsFilters); ?>
						</tr>
			<?php endif; ?>
				</thead>
				<tfoot>
					<tr>
			<?php echo implode("\n", $tFoots); ?>
					</tr>
				</tfoot>
				<tbody>
			<?php echo implode("\n", $tBodys); ?>
				</tbody>
			</table>
		<?php endif; ?>
	<?php else: ?>
		<?php echo zbase_view_render($ui->emptyViewFile()); ?>
	<?php endif; ?>
<?php endif; ?>