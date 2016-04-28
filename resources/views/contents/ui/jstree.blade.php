<?php
/**
 * Convert Row to JSON
 * @param EntityInterface $row
 * http://jonmiles.github.io/bootstrap-treeview/#grandchild1
 * https://github.com/jonmiles/bootstrap-treeview
 */
$rows = $ui->getRows();
if(empty($rows))
{
	return;
}
zbase_view_plugin_load('jstree');
zbase_view_plugin_load('nodes');
$treeData = $ui->getTree(['jstree' => true]);
$treeOptions = $ui->getAttribute('treeOptions');
//$form = $ui->form();
$uiId = $ui->id();
$entity = $ui->entity();
$attributes = $ui->wrapperAttributes();
$wrapperAttributes = $ui->renderHtmlAttributes($attributes);
$actionCreateButton = $ui->getActionCreateButton();
$selectable = isset($treeOptions['selectable']) ? $treeOptions['selectable'] : true;
$positionable = true;
$multiple = isset($treeOptions['multiple']) ? $treeOptions['multiple'] : true;
$jsTreePlugins = ['types'];
if($selectable)
{
	$jsTreePlugins[] = 'checkbox';
	$jsTreePlugins[] = 'wholerow';
}
if($positionable)
{
	$jsTreePlugins[] = 'dnd';
	$jsTreePlugins[] = 'state';
	$jsTreePlugins[] = 'unique';
}
$pluginOptions = [
	'plugins' => $jsTreePlugins,
	'core' => [
		'data' => $treeData,
		'themes' => [
			'responsive' => true
		]
	],
];
if(empty($multiple))
{
	$pluginOptions['core']['multiple'] = false;
}
if(empty($selectable))
{
	zbase_view_style_add('jstreeSelectable', '.jstree-icon.jstree-checkbox{display: none;}');
}
$script = ['jQuery(\'#' . $uiId . 'TreeView\').jstree(' . zbase_collection($pluginOptions)->toJson() . ')'];
$script[] = 'on(\'changed.jstree\', nodeCategoryJstreeOnClicked)';
zbase_view_script_add($uiId . 'TreeView', implode('.', $script) . ';', true);
$dataConfig = [];
$dataConfig['url'] = zbase_url_from_route('admin.node_' . $entity::$nodeNamePrefix . '_category', ['action' => 'ACTION','id' => 'ID']);
$dataConfig['node'] = str_replace('-','_',$treeOptions['nodeWidgetId']);
$dataConfig['infopane'] = '#' . $uiId . 'TreeViewInfoPane';
?>

<div class="col-md-12">
	<div class="col-md-3 col-xs-12">
		<div class="slimScrollDiv" id="<?php echo $uiId ?>TreeView" data-id="<?php echo $uiId ?>" data-config='<?php echo zbase_collection($dataConfig)->toJson()?>'></div>
	</div>
	<div class="col-md-9 col-xs-12">
		<div id="<?php echo $uiId ?>TreeViewInfoPane"></div>
	</div>
</div>
