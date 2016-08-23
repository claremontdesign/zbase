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
$hasTelegram = !empty($user->telegram_chat_id) ? true : false;
if(empty($hasTelegram))
{
	$codeCheck = zbase()->telegram()->checkUserCode(zbase_auth_user());
}

$telegramBot = zbase()->telegram()->botusername();
$code = zbase()->telegram()->userCode($user);
?>
<div class="col-md-12">
	<h2>Receive Updates and Notifications via Telegram</h2>
	<hr />
	<h3>What is Telegram?</h3>
	<p>Telegram is a cloud-based mobile and desktop messaging app with a focus on security and speed.</p>
	<p>Download and install telegram here: <a href="https://telegram.org/" target="_blank">https://telegram.org/</a></p>
	<hr />
	<?php if(empty($hasTelegram)): ?>
		<p>
			<strong>When you're done downloading, installing and creating an account in Telegram App click the button below</strong>:
			<br />
			<br />
			<a class="btn btn-success" target="_blank" href="https://telegram.me/<?php echo $telegramBot ?>?start=<?php echo $code ?>">Click to enable Telegram notifications</a>
		</p>
	<?php else: ?>
		<div class="alert alert-success">You are currently receiving notifications via Telegram</div>
		<br />
		<br />
		<a class="btn btn-warning" href="<?php echo zbase_url_from_route('admin.system', ['action' => 'telegram-disable']) ?>">Disable Telegram notifications</a>
	<?php endif; ?>
</div>