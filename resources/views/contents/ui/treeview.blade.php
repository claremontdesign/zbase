<?php
/**
 * Convert Row to JSON
 * @param EntityInterface $row
 * http://jonmiles.github.io/bootstrap-treeview/#grandchild1
 * https://github.com/jonmiles/bootstrap-treeview
 */
$rows = $ui->getRows();
$htmls = [];
if(empty($rows))
{
	return;
}
$form = $ui->form();

function treeview_row($row)
{
	$newRow = [];
	$newRow['text'] = $row['title'];
	$newRow['id'] = $row['category_id'];
	if(!empty($row['selected']))
	{
		$newRow['state']['selected'] = true;
	}
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
$actionCreateButton = $ui->getActionCreateButton();
if(!empty($actionCreateButton))
{
	$actionCreateButton->setAttribute('size', 'default');
}
if(!empty($rows))
{
	$newRows = [];
	$selectedNodes = zbase_form_old('category');
	foreach ($rows->toArray() as $row)
	{
		if(!empty($selectedNodes) && in_array($row['category_id'], $selectedNodes))
		{
			$row['selected'] = true;
			$htmls[] = '<input type="hidden" value="' . $row['category_id'] . '" id="' . $uiId . 'Category' . $row['category_id'] . '" name="category[]">';
		}
		$newRows[] = treeview_row($row);
	}
}
$treeViewOptions = [
	'data: ' . json_encode($newRows)
];
if($form instanceof \Zbase\Widgets\Type\FormInterface)
{
	$treeViewOptions[] = 'showCheckbox: false';
	$treeViewOptions[] = 'onNodeUnselected: function(event, node) {var nodeId = \'' . $uiId . 'Category\'+node.id;jQuery(\'#\' + nodeId).remove();}';
	$treeViewOptions[] = 'onNodeSelected: function(event, node) {var nodeId = \'' . $uiId . 'Category\'+node.id;jQuery(\'#' . $uiId . 'TreeView\').parent().append(\'<input type="hidden" value="\'+node.id+\'" id="\'+nodeId+\'" name="category[]">\');}';
}
$treeOptions = $ui->getAttribute('treeOptions');
$label = $ui->getAttribute('label');
if(!empty($treeOptions))
{
	foreach ($treeOptions as $oK => $oV)
	{
		$treeViewOptions[] = $oK . ': ' . (!empty($oV) ? 'true' : 'false');
	}
}
$script = 'jQuery(\'#' . $uiId . 'TreeView\').treeview({' . implode(',', $treeViewOptions) . '});';
zbase_view_script_add($uiId . 'TreeView', $script, true);
?>
<?php if($form instanceof \Zbase\Widgets\Type\FormInterface): ?>
	<div class="form-group">
		<label><?php echo $label ?></label>
		<div <?php echo $wrapperAttributes ?>>
			<div id="<?php echo $uiId ?>TreeView"></div>
			<?php echo implode('', $htmls); ?>
		</div>
	</div>
<?php else: ?>
	<div <?php echo $wrapperAttributes ?>>
		<?php if(!empty($actionCreateButton)): ?>
			<?php echo $actionCreateButton ?>
		<?php endif; ?>
		<div id="<?php echo $uiId ?>TreeView"></div>
		<?php echo implode('', $htmls); ?>
	</div>
<?php endif; ?>
