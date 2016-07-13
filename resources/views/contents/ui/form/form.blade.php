<?php
$label = $ui->getLabel();
$hasAccess = $ui->hasAccess();
$attributes = $ui->wrapperAttributes();
$wrapperAttributes = $ui->renderHtmlAttributes($attributes);
$elements = $ui->elements();
$tabs = $ui->tabs();
$submitButton = $ui->submitButton();
$submitButtonLabel = $ui->submitButtonLabel();
$formTag = $ui->hasFormTag();
?>
<?php if(zbase_is_angular_template()): ?>
	<div <?php echo $wrapperAttributes ?>>
		<?php if(empty($ui->isNested())): ?>
			<?php if(!empty($formTag)): ?>
				<?php echo $ui->startTag(); ?>
			<?php endif; ?>
			<?php echo $ui->renderCSRFToken(); ?>
		<?php endif; ?>
		<?php if(!empty($viewFile)): ?>
			<?php echo zbase_view_render(zbase_view_file_contents($viewFile), compact('ui')); ?>
		<?php endif; ?>
		<?php if(empty($viewFile)): ?>
			<?php if(!empty($elements)): ?>
				<?php foreach ($elements as $element): ?>
					<?php echo $element ?>
				<?php endforeach; ?>
			<?php endif; ?>
			<?php if(!empty($tabs)): ?>
				<?php echo $tabs ?>
			<?php endif; ?>
		<?php endif; ?>

		<?php if(!empty($submitButton) && !empty($formTag)): ?>
			<hr />
			<?php echo $ui->renderSubmitButton(); ?>
		<?php endif; ?>

		<?php if(empty($ui->isNested())): ?>
			<?php if(!empty($formTag)): ?>
				<?php echo $ui->endTag(); ?>
			<?php endif; ?>
		<?php endif; ?>
	</div>
<?php else: ?>

	<div <?php echo $wrapperAttributes ?>>
		<?php if(empty($ui->isNested())): ?>
			<?php if(!empty($formTag)): ?>
				<?php echo $ui->startTag(); ?>
			<?php endif; ?>
			<?php echo $ui->renderCSRFToken(); ?>
		<?php endif; ?>
		<?php if(!empty($viewFile)): ?>
			<?php echo zbase_view_render(zbase_view_file_contents($viewFile), compact('ui')); ?>
		<?php endif; ?>
		<?php if(empty($viewFile)): ?>
			<?php if(!empty($elements)): ?>
				<?php foreach ($elements as $element): ?>
					<?php echo $element ?>
				<?php endforeach; ?>
			<?php endif; ?>
			<?php if(!empty($tabs)): ?>
				<?php echo $tabs ?>
			<?php endif; ?>
		<?php endif; ?>

		<?php if(!empty($submitButton) && !empty($formTag)): ?>
			<hr />
			<?php echo $ui->renderSubmitButton(); ?>
		<?php endif; ?>

		<?php if(empty($ui->isNested())): ?>
			<?php if(!empty($formTag)): ?>
				<?php echo $ui->endTag(); ?>
			<?php endif; ?>
		<?php endif; ?>
	</div>
<?php endif; ?>