<?php
$attributes = $ui->wrapperAttributes();
$wrapperAttributes = $ui->renderHtmlAttributes($attributes);
$columns = $ui->getProcessedColumns();
$rows = $ui->getRows();
$columnCount = count($columns);
$hasActions = $ui->hasActions();
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
	if(!empty($rows))
	{
		foreach ($rows as $row)
		{
			$tBodys[] = '<tr>';
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
		$tHeads[] = '<th>' . $column->getLabel() . '</th>';
	}
}
if(!empty($hasActions))
{
	$tHeads[] = '<th>&nbsp;</th>';
}
?>
<?php if(zbase_is_mobile()): ?>
<div <?php echo $wrapperAttributes ?>>
	<?php if(!empty($columns)): ?>
		<table class="table">
			<thead>
				<tr>
					<?php echo implode("\n", $tHeads); ?>
				</tr>
			</thead>
			<?php if(!empty($tFoots)):?>
			<tfoot>
				<tr>
					<?php echo implode("\n", $tFoots); ?>
				</tr>
			</tfoot>
			<?php endif;?>
			<tbody>
				<?php echo implode("\n", $tBodys); ?>
			</tbody>
		</table>
	<?php endif; ?>
</div>
<?php echo zbase_view_render(zbase_view_file_contents('ui.datatable.toolbar'), ['ui' => $ui]); ?>

<?php else: ?>

	<div <?php echo $wrapperAttributes ?>>
		<?php if(!empty($columns)): ?>
			<?php echo zbase_view_render(zbase_view_file_contents('ui.datatable.toolbar'), ['ui' => $ui]); ?>
			<table class="table">
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
	</div>


<?php endif; ?>
