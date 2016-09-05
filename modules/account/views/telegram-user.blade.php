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
$hasTelegram = zbase()->telegram()->checkUserTelegram(zbase_auth_user());
if(empty($hasTelegram))
{
	$codeCheck = zbase()->telegram()->checkUserCode(zbase_auth_user());
}
$telegramBot = zbase()->telegram()->botusername();
$code = zbase()->telegram()->userCode($user);
?>
<div class="col-md-12">
	<h2>Receive Real-Time Updates and Notifications via Telegram</h2>
	<hr />
	<h3>What is Telegram?</h3>
	<p>Telegram is a cloud-based mobile and desktop messaging app with a focus on security and speed.</p>
	<p>Download and install telegram here: <a href="https://telegram.org/" target="_blank">https://telegram.org/</a></p>
	<hr />
	<?php if(empty($hasTelegram)): ?>
		<p>
			<strong>When you're done downloading, installing and creating an account in Telegram App click the button below.</strong>
			<br />
			<br />
			<strong>
					A new window will open and it will prompt you to open or install the Telegram App.
					<br />
					Follow the instruction until you will receive the first message from the DermaSecrets.
			</strong>
			<br />
			<br />
			<a class="btn btn-success" id="btnTelegramEnable" target="_blank" href="https://telegram.me/<?php echo $telegramBot ?>?start=<?php echo $code ?>">Click to enable Telegram notifications</a>
			<div id="telegramConnetingInfo" class="alert alert-block alert-warning fade in" style="display: none;">
				<h4 class="alert-heading">Please don't close the window until we were able to connect to your account.</h4>
				<p>
					A new window will open and it will prompt you to open or install the Telegram App.
					<br />
					Follow the instruction until you will receive the first message from the DermaSecrets.
					<br />
					<br />
					Don't close this window yet.
				</p>
			</div>
	</p>

	<?php ob_start(); ?>
	<script type="text/javascript">
		jQuery('#btnTelegramEnable').click(function (e) {
			e.preventDefault();
			jQuery(this).hide();
			jQuery('#telegramConnetingInfo').show();
			setInterval(function () {
				zbase_ajax_post('<?php echo zbase_url_from_route('admin.account', ['action' => 'telegram-check']) ?>', {}, function (e) {
					if (e.telegramHooked !== undefined)
					{
						window.location = '<?php echo zbase_url_from_current() ?>';
					}
				}, {});
			}, 5000);
			window.open(jQuery(this).attr('href'));
		});
	</script>
	<?php
	$script = ob_get_clean();
	zbase_view_script_add('telegramEnabler', $script, true);
	?>

<?php else: ?>
	<div class="alert alert-success">You are currently receiving notifications via Telegram</div>
	<br />
	<br />
	<a class="btn btn-warning" href="<?php echo zbase_url_from_route('admin.account', ['action' => 'telegram-disable']) ?>">Disable Telegram notifications</a>
<?php endif; ?>
</div>