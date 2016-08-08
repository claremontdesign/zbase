<?php
$attributes = $ui->wrapperAttributes();
$wrapperAttributes = $ui->renderHtmlAttributes($attributes);
$queryOnLoad = $ui->isQueryOnLoad();
?>

<div <?php echo $wrapperAttributes ?>>
	<?php echo zbase_view_render(zbase_view_file_contents('ui.datatable.search'), ['ui' => $ui]); ?>
	<?php if(!empty($queryOnLoad)): ?>
		<?php echo zbase_view_render(zbase_view_file_contents('ui.datatable.toolbar'), ['ui' => $ui]); ?>
		<?php echo zbase_view_render(zbase_view_file_contents('ui.datatable.table'), ['ui' => $ui]); ?>
	<?php endif; ?>
</div>


