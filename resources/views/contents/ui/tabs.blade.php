<?php
$wrapperAttributes = $ui->renderHtmlAttributes($ui->wrapperAttributes());
$navAttributes = '';
$tabs = $ui->tabs();
?>
<?php if(!empty($tabs)): ?>
	<?php if(zbase_is_angular_template()): ?>
		<ui-state id='<?php echo $ui->getHtmlId() ?>activeTab' default='1'></ui-state>
		<div <?php echo $wrapperAttributes ?>>
			<ul class="nav nav-tabs">
				<?php $tabCounter = 0; ?>
				<?php foreach ($tabs as $tab): ?>
					<?php $tabCounter++; ?>
					<li ui-class="{'active': <?php echo $ui->getHtmlId() ?>activeTab == <?php echo $tabCounter; ?>}">
						<a ui-set="{'<?php echo $ui->getHtmlId() ?>activeTab': <?php echo $tabCounter; ?>}">
							<?php echo $tab->label() ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>

			<?php $tabCounter = 0; ?>
			<?php foreach ($tabs as $tab): ?>
				<?php $tabCounter++; ?>
				<div ui-if="<?php echo $ui->getHtmlId() ?>activeTab == <?php echo $tabCounter; ?>">
					<?php echo $tab; ?>
				</div>
			<?php endforeach; ?>
		</div>
	<?php else: ?>
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


<?php endif; ?>


