<?php
/**
 * $template = will return the template only
 */
$columns = $ui->getProcessedColumns();
$rows = $ui->getRows();
$rowCount = count($rows);
$columnCount = count($columns);
$hasActions = $ui->hasActions();
$isClickableRows = $ui->isRowsClickable();
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
		$tBodys[] = '<tr id="' . $prefix . 'RowId__' . $ui->rowValueIndex() . '__"' . $clickableRow . '>';
		foreach ($columns as $column)
		{
			$column->prepare();
			$columnConfig[$column->id()] = $column->getDataType();
			$tBodys[] = $column->renderValue('td', true);
		}
		if(!empty($hasActions))
		{
			$tBodys[] = '<td>' . $ui->renderRowActions(null, true) . '</td>';
		}
		$tBodys[] = '</tr>';
		foreach ($columns as $column)
		{
			$tHeads[] = '<th>' . $column->getLabel() . '</th>';
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
				$tBodys[] = '<tr' . $clickableRow .'>';
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
			$tHeads[] = '<th class="' . $column->getDataType() . '">' . $column->getLabel() . '</th>';
		}
	}
}
if(!empty($hasActions))
{
	$tHeads[] = '<th>&nbsp;</th>';
}
?>
<?php if(!empty($template)): ?>
	<?php if(!empty($columnConfig)): ?>
		var <?php echo $prefix ?>Columns = <?php echo json_encode($columnConfig, true) ?>;
	<?php endif; ?>
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
			<table class="table table-hover flip-content">
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
				<tbody>
					<?php echo implode("\n", $tBodys); ?>
				</tbody>
			</table>
		<?php endif; ?>
	<?php else: ?>
		<?php echo zbase_view_render($ui->emptyViewFile()); ?>
	<?php endif; ?>
<?php endif; ?>