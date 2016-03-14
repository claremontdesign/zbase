<?php
$text = $ui->getText();
$wrapperAttributes = $ui->renderHtmlAttributes($ui->wrapperAttributes());
$tag = $ui->getTag();
?>
<div <?php echo $wrapperAttributes ?>>
	<<?php echo $tag?>><?php echo $text?></<?php echo $tag?>>
</div>