Module <?php echo $module->title() ?> | <a href="<?php echo zbase_url_from_route('admin') ?>">Dashboard</a>

<?php if(!empty($widgets)): ?>
<?php foreach($widgets as $widget):?>
	<?php echo $widget;?>
<?php endforeach;?>
<?php endif; ?>