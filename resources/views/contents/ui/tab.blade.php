<?php
$label = $ui->getLabel();
$attributes = $ui->wrapperAttributes();
$wrapperAttributes = $ui->renderHtmlAttributes($attributes);
?>
<?php if(zbase_is_angular_template()): ?>
	<?php echo $ui->renderContents(); ?>
<?php else: ?>
	<div <?php echo $wrapperAttributes ?>>
		<?php echo $ui->renderContents(); ?>
	</div>
<?php endif; ?>