<?php
$label = $ui->getLabel();
$attributes = $ui->wrapperAttributes();
$wrapperAttributes = $ui->renderHtmlAttributes($attributes);
?>
<div <?php echo $wrapperAttributes?>>
	<?php echo $ui->renderContents();?>
</div>