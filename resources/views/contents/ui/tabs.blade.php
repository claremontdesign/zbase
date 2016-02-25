<?php
$wrapperAttributes = $ui->renderHtmlAttributes($ui->wrapperAttributes());
$navAttributes = '';
$tabs = $ui->tabs();
?>
<?php if(!empty($tabs)): ?>
	<div <?php echo $wrapperAttributes ?>>
		<ul class="nav nav-tabs">
			<?php foreach ($tabs as $tab): ?>
				<?php
					$active = $tab->isActive() ? 'active' : '';
				?>
				<li class="<?php echo $active ?>"><a data-toggle="tab" href="#<?php echo $tab->getHtmlId() ?>"><?php echo $tab->label() ?></a></li>
			<?php endforeach; ?>
		</ul>

		<div class="tab-content">
			<?php foreach ($tabs as $tab): ?>
				<?php echo $tab; ?>
			<?php endforeach; ?>
		</div>
	</div>
<?php endif; ?>