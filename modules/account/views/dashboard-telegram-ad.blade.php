<?php if(zbase()->telegram()->isEnabled()): ?>
	<?php $hasTelegram = zbase()->telegram()->checkUserTelegram(zbase_auth_user()); ?>
	<?php if(empty($hasTelegram)): ?>

	<?php endif; ?>
<?php endif; ?>