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
if(!empty($hasActions))
{
	$columnCount++;
}

$tHeads = [];
$tBodys = [];
// $tFoots = ['<td colspan="' . $columnCount . '">&nbsp;</td>'];
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
			$clickableRow = ' class="pointer zbase-datatable-row-toggle" ' . (!empty($isRowsToNextRowReplaceContent) ? 'data-content="1"' : null) . ' data-href="' . $ui->getRowClickableUrl(null, $template) . '"';
		}

		if($ui->entity() instanceof \Zbase\Post\PostInterface)
		{
			$tBodys[] = '<tr' . $clickableRow . ' id="rowPostMainContentWrapper' . $ui->entity()->postHtmlTemplateId() . '">';
		}
		else
		{
			$tBodys[] = '<tr id="' . $prefix . 'RowId__' . $ui->rowValueIndex() . '__"' . $clickableRow . '>';
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
		foreach ($columns as $column)
		{
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
					$clickableRow = ' class="pointer" onclick="zbase_to_url(this);" data-href="' . $ui->getRowClickableUrl($row) . '"';
				}
				if($isClickableToNextRow)
				{
					$clickableRow = ' class="pointer zbase-datatable-row-toggle" ' . (!empty($isRowsToNextRowReplaceContent) ? 'data-content="1"' : null) . ' data-href="' . $ui->getRowClickableUrl($row) . '"';
				}
				if($row instanceof \Zbase\Post\PostInterface)
				{
					$tBodys[] = '<tr' . $clickableRow . ' id="rowPostMainContentWrapper' . $row->postHtmlId() . '">';
				}
				else
				{
					$tBodys[] = '<tr' . $clickableRow . '>';
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
		foreach ($columns as $column)
		{
			$tHeads[] = '<th ' . $column->renderTagAttribute('th') . '>' . $column->getLabel() . '</th>';
		}
	}
}
if(!empty($hasActions))
{
	$tHeads[] = '<th>&nbsp;</th>';
}
if($paginationLoadMore)
{
	$tFoots[] = '<tr><td colspan="' . $columnCount . '"><div class="alert alert-warning"><a href="" id="'.$tableId.'LoadMoreAnchor">Load more...</a></div></td></tr>';
}
?>
<?php if(!empty($template)): ?>
	<?php if(!empty($columnConfig)): ?>
		var <?php echo $prefix ?>Columns = <?php echo json_encode($columnConfig, true) ?>;
	<?php endif; ?>
	var <?php echo $prefix ?>TemplateToolbar = '<?php echo zbase_view_render(zbase_view_file_contents('ui.datatable.toolbar'), ['ui' => $ui, 'template' => true]); ?>';
	var <?php echo $prefix ?>TemplateTable = '<table class="table table-hover flip-content" id="<?php echo $prefix ?>Table">
		<thead class="flip-content">
			<tr>
				<?php echo implode("\n", $tHeads); ?>
			</tr>
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
			<table class="table table-hover zbase-table-responsive" id="<?php echo $tableId?>DataTable">
				<thead>
					<tr>
						<?php echo implode("\n", $tHeads); ?>
					</tr>
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