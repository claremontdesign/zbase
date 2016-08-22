<?php
$user = zbase_auth_user();
?>
<?php if($user->emailVerificationEnabled() && !$user->isEmailVerified()): ?>
	<div class="note note-danger">
		<h4 class="block">Verify your email address.</h4>
		<p>To be able to receive updates and information, kindly verify your email address.</p>
		<p>When you registered, we sent an email with your email verification code.
			<br />If you didn't receive it,
			<a class="zbase-ajax-anchor" href="<?php echo zbase_url_from_route('account', ['action' => 'resend-email-verification']) ?>" title="Resend verification code">click here so we can resend</a> it to you.
		</p>
	</div>
<?php endif; ?>