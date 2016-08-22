<?php if(zbase()->system()->inMaintenance()): ?>
	<div class="alert alert-danger text-center">
		<a href="<?php echo zbase_url_from_route('admin.system', ['action' => 'maintenance-mode-off']) ?>" class="btn btn-danger">Stop Maintenance Mode</a>
		<br />
		<br />
		Website is currently in <strong>maintenance mode</strong>.
	</div>
<?php else : ?>
	<div class="alert alert-info text-center">
		<a href="<?php echo zbase_url_from_route('admin.system', ['action' => 'maintenance-mode-on']) ?>" class="btn btn-info">Set to Maintenance Mode</a>
		<br />
		<br />
		When <strong>maintenance mode</strong> is enabled,<br />
		a maintenance message will be displayed when someone accesses the site.
		Your current IP Address of <strong><?php echo zbase_ip() ?></strong> will be excluded.
	</div>
<?php endif; ?>