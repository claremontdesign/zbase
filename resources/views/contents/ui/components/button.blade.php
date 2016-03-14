<?php
$wrapperAttributes = $ui->renderHtmlAttributes($ui->wrapperAttributes());
$tag = $ui->getTag();
$label = $ui->getLabel();
$enabled = $ui->enabled();
?>
<?php if(!empty($enabled)):?>
<<?php echo $tag?> <?php echo $wrapperAttributes?>><?php echo $label?></<?php echo $tag?>>
<?php endif;?>