<?php if(!empty($alerts)): ?>
	<div <?php echo zbase_view_ui_tag_attributes('alert', 'class="alert alert-info fade in" role="alert"'); ?>>
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
		<?php if(is_array($alerts)): ?>
			<?php foreach ($alerts as $msg): ?>
				{{ $msg }}<br />
			<?php endforeach; ?>
		<?php else: ?>
			{{ $msgs }}
		<?php endif; ?>
	</div>
<?php endif; ?>