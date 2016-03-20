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



<?php echo zbase_view_render(zbase_view_file_contents('email.footer')); ?>