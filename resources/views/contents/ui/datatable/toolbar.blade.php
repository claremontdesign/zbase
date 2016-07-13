<?php
$rows = $ui->getRows();
$actionCreateButton = $ui->getActionCreateButton();
if(zbase_is_mobile())
{
	if(!empty($actionCreateButton))
	{
		zbase_view_placeholder_add('topActionBar', $ui->id() . 'createAction', '<li><a href="' . $actionCreateButton->href() . '">' . $actionCreateButton->getLabel() . '</a></li>');
	}
}
?>
<?php if(zbase_is_mobile()): ?>
	<div role="toolbar" class="btn-toolbar">
		<div class="col-md-12">
			<?php echo zbase_view_render(zbase_view_file_contents('ui.datatable.pagination'), ['paginator' => $rows, 'ui' => $ui]); ?>
		</div>
	</div>
<?php else: ?>
	<div role="toolbar" class="btn-toolbar">
		<div class="col-md-10 pull-left">
			<?php echo zbase_view_render(zbase_view_file_contents('ui.datatable.pagination'), ['paginator' => $rows, 'ui' => $ui]); ?>
		</div>
		<div class="col-md-2 pull-right">
			<?php echo zbase_view_render(zbase_view_file_contents('ui.datatable.sorting'), ['ui' => $ui]); ?>
		</div>
	</div>
	<?php if(!empty($actionCreateButton)): ?>
		<div class="btn-toolbar pull-right" role="toolbar" aria-label="Buttons">
			<?php echo $actionCreateButton->setAttribute('size', 'default'); ?>
		</div>
	<?php endif; ?>
<?php endif; ?>