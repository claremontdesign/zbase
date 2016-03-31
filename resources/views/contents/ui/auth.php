<div class="ui-auth">
	<?php echo zbase_view_render(zbase_view_file_contents('ui.message.access'), array('message' => !empty($message) ? $message : _zt('You need to login to be able to continue'))); ?>
	<hr />
	<?php echo zbase_view_render(zbase_view_file_contents('auth.login.form')); ?>
	<hr />
</div>