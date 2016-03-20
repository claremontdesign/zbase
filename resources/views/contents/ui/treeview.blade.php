<?php
/**
 * Convert Row to JSON
 * @param EntityInterface $row
 */
$form = $ui->form();

function treeview_row($row)
{
	$newRow = [];
	$newRow['text'] = $row['title'];
	$newRow['id'] = $row['category_id'];
	$children = !empty($row['children']) ? $row['children'] : false;
	if(!empty($children))
	{
		foreach ($children as $child)
		{
			$newRow['nodes'][] = treeview_row($child);
		}
	}
	return $newRow;
}

zbase_view_plugin_load('bootstrap-treeview');
$uiId = $ui->id();
$attributes = $ui->wrapperAttributes();
$wrapperAttributes = $ui->renderHtmlAttributes($attributes);
$rows = $ui->getRows();
$actionCreateButton = $ui->getActionCreateButton()->setAttribute('size', 'default');
if(!empty($rows))
{
	$newRows = [];
	foreach ($rows->toArray() as $row)
	{
		$newRows[] = treeview_row($row);
	}
}
$treeViewOptions = [
	'data: ' . json_encode($newRows)
];
if($form instanceof \Zbase\Widgets\Type\FormInterface)
{
	$treeViewOptions[] = 'multiSelect: false';
	$treeViewOptions[] = 'showCheckbox: false';
	$treeViewOptions[] = 'onNodeUnselected: function(event, node) {var nodeId = \'parentCategory\'+node.id;$(\'#\' + nodeId).remove();}';
	$treeViewOptions[] = 'onNodeSelected: function(event, node) {var nodeId = \'parentCategory\'+node.id;$(\'#' . $uiId . 'TreeView\').parent().append(\'<input type="hidden" value="\'+node.id+\'" id="\'+nodeId+\'" name="parent[]">\');}';
}
$script = '$(\'#' . $uiId . 'TreeView\').treeview({' . implode(',', $treeViewOptions) . '});';
zbase_view_script_add($uiId . 'TreeView', $script, true);
?>
<?php if($form instanceof \Zbase\Widgets\Type\FormInterface): ?>
	<div class="form-group">
		<label>Parent Category</label>
		<div <?php echo $wrapperAttributes ?>>
			<div id="<?php echo $uiId ?>TreeView"></div>
		</div>
	</div>
<?php else: ?>
	<div <?php echo $wrapperAttributes ?>>
		<?php if(!empty($actionCreateButton)): ?>
			<?php echo $actionCreateButton ?>
		<?php endif; ?>
		<div id="<?php echo $uiId ?>TreeView"></div>
	</div>
<?php endif; ?>
