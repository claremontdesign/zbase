<?php

/**
 * Convert Row to JSON
 * @param EntityInterface $row
 */
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

$script = '$(\'#' . $uiId . 'TreeView\').treeview({data: ' . json_encode($newRows) . '});';
zbase_view_script_add($uiId . 'TreeView', $script, true);
?>
<div <?php echo $wrapperAttributes ?>>
	<?php if(!empty($actionCreateButton)): ?>
		<?php echo $actionCreateButton ?>
	<?php endif; ?>
	<div id="<?php echo $uiId ?>TreeView"></div>
</div>