<?php

/**
 * Email to send when requesting to update new password
 * $entity = The account owner
 * $code = The Code
 *
 * /account/password/update-request?e=email&c=$code
 * zbase_url_from_route('account', ['action' => 'password','task' => 'update-request','e' => $entity->email(),'c' => $code])
 *
 */
$url = zbase_url_from_route('account', ['action' => 'password','task' => 'update-request','e' => $entity->email(),'c' => $code]);
?>

<?php echo zbase_view_render(zbase_view_file_contents('email.header')); ?>

We received a request to update your password.
If this is not you, disregard this email, else click on the link below.

<a href="<?php echo $url?>">Update your password</a>

<?php echo zbase_view_render(zbase_view_file_contents('email.footer')); ?>