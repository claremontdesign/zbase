<?php
$label = $ui->getLabel();
$attributes = $ui->wrapperAttributes();
$wrapperAttributes = $ui->renderHtmlAttributes($attributes);
$elements = $ui->elements();
$tabs = $ui->tabs();
$submitButton = $ui->submitButton();
$submitButtonLabel = $ui->submitButtonLabel();
?>
<div <?php echo $wrapperAttributes ?>>
	<?php if(empty($ui->isNested())): ?>
		<form action="" method="POST">
			<?php echo zbase_csrf_token_field($ui->id())?>
		<?php endif; ?>
		<?php if(!empty($elements)): ?>
			<?php foreach ($elements as $element): ?>
				<?php echo $element ?>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if(!empty($tabs)): ?>
			<?php echo $tabs ?>
		<?php endif; ?>

		<?php if(!empty($submitButton)): ?>
			<button type="submit" class="btn btn-default"><?php echo $submitButtonLabel ?></button>
		<?php endif; ?>

		<?php if(empty($ui->isNested())): ?>
		</form>
	<?php endif; ?>
</div>