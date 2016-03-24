<?php

/**
 * Email address verification code
 * Send this message to verify the new email address
 * $entity = The account owner
 * $newEmailAddress = The New Email Address
 * $code = The Code
 *
 * /account/email/verify?e=email&c=$code
 * zbase_url_from_route('account', ['action' => 'email','task' => 'verify','e' => $newEmailAddress,'c' => $code])
 */
?>

<?php echo zbase_view_render(zbase_view_file_contents('email.header')); ?>

<h1>Email Address verification</h1>
<p>To be able to continue, we need to verify your email address
<br />
Email Address: <?php echo $newEmailAddress?>
Code: <strong><?php echo $code?></strong>
<br />
<br />
<a href="<?php echo zbase_url_from_route('account', ['action' => 'email','task' => 'verify','e' => $newEmailAddress,'c' => $code])?>">Click here</a> to verify your email address.
</p>

<?php echo zbase_view_render(zbase_view_file_contents('email.footer')); ?>