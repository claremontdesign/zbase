<?php
$wrapperAttributes = $ui->renderHtmlAttributes($ui->wrapperAttributes());
?>
<div <?php echo $wrapperAttributes?>>
	<?php echo $ui->getContent()?>
</div>