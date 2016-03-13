<?php
$attributes = $ui->wrapperAttributes();
$wrapperAttributes = $ui->renderHtmlAttributes($attributes);
$columns = $ui->getColumns();
$columnCount = count($columns);
?>

<div <?php echo $wrapperAttributes ?>>
	<table class="table">
	</table>
</div>