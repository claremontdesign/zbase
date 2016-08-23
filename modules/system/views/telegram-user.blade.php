<?php
/**
 * Dx
 *
 * @link http://dennesabing.com
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @license proprietary
 * @copyright Copyright (c) 2015 ClaremontDesign/MadLabs-Dx
 * @version 0.0.0.1
 * @since Aug 23, 2016 5:57:34 PM
 * @file telegram-user.blade.php
 * @project Zbase
 * @package Expression package is undefined on line 14, column 15 in Templates/Scripting/EmptyPHP.php.
 */
$user = zbase_auth_user();
$hasTelegram = (bool) $user->telegram_chat_id;
$telegramBot = zbase()->telegram()->botusername();
$code = zbase()->telegram()->userCode();
?>
<div class="col-md-12">
	<h2>Receive Updates and Notifications via Telegram</h2>
	<?php if(empty($hasTelegram)): ?>
		<hr />
		<h3>What is Telegram?</h3>
		<p>Telegram is a cloud-based mobile and desktop messaging app with a focus on security and speed.</p>
		<p>Download and install telegram here: <a href="https://telegram.org/" target="_blank">https://telegram.org/</a></p>
		<hr />
		<p>
			<strong>When you're done downloading and installing telegram, create an account and click the button below</strong>:
			<br />
			<br />
			<a class="btn btn-success" href="https://telegram.me/<?php echo $telegramBot ?>?start=<?php echo $code ?>">Click to enable Telegram notifications</a>
		</p>
	<?php else: ?>
		<button class="btn btn-success">You are currently receiving updates via Telegram</button>
	<?php endif; ?>
</div>