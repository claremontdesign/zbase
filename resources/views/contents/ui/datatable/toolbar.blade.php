<?php
$rows = $ui->getRows();
$actionCreateButton = $ui->getActionCreateButton();
if(!empty($actionCreateButton))
{
	zbase_view_placeholder_add('topActionBar', $ui->id() . 'createAction', '<li><a href="' . $actionCreateButton->href() . '">' . $actionCreateButton->getLabel() . '</a></li>');
}
?>
<div role="toolbar" class="btn-toolbar pull-left">
	<?php echo zbase_view_render(zbase_view_file_contents('ui.datatable.pagination'), ['paginator' => $rows]); ?>
</div>
<div class="btn-toolbar pull-right" role="toolbar" aria-label="Buttons">
	<?php if(!empty($actionCreateButton)): ?>
		<?php echo $actionCreateButton->setAttribute('size', 'default'); ?>
	<?php endif; ?>
</div>