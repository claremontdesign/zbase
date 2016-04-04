<?php
/**
 * Convert Row to JSON
 * @param EntityInterface $row
 * http://jonmiles.github.io/bootstrap-treeview/#grandchild1
 * https://github.com/jonmiles/bootstrap-treeview
 */
$rows = $ui->getRows();
$treeRows = $ui->getTree();
$htmls = [];
if(empty($rows))
{
	return;
}
$form = $ui->form();
$selectedRows = $ui->selectedRows();
zbase_view_plugin_load('bootstrap-treeview');
zbase_view_plugin_load('bootstrap');
$uiId = $ui->id();
$attributes = $ui->wrapperAttributes();
$wrapperAttributes = $ui->renderHtmlAttributes($attributes);
$actionCreateButton = $ui->getActionCreateButton();
if(!empty($actionCreateButton))
{
	$actionCreateButton->setAttribute('size', 'default');
}
if(!empty($selectedRows))
{
	foreach ($selectedRows as $sel)
	{
		if(is_object($sel))
		{
			$htmls[] = '<input type="hidden" value="' . $sel->id() . '" id="' . $uiId . 'Category' . $sel->id() . '" name="category[]">';
		}
		else
		{
			$htmls[] = '<input type="hidden" value="' . $sel . '" id="' . $uiId . 'Category' . $sel . '" name="category[]">';
		}
	}
}
$treeViewOptions = [
	'data: ' . json_encode($treeRows)
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
