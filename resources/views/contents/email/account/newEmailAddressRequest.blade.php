<?php

/**
 * Email address update request
 * Send this message to the old email address with a link to complete the process of new email address
 * $entity = The account owner
 * $newEmailAddress = The New Email Address
 * $code = The Code
 *
 * /account/email/update-request?e=email&c=$code
 * zbase_url_from_route('account', ['action' => 'email','task' => 'update-request','e' => $newEmailAddress,'c' => $code])
 */
?>
<?php echo zbase_view_render(zbase_view_file_contents('email.header')); ?>

<h1>Email Update Request</h1>
<p>You requested to update your email address.
<?php if(!empty($newEmailAddress)):?>
<br />
Current Email Address: <?php echo $entity->email()?>
<br />
New Email Address: <?php echo $newEmailAddress?>
<?php endif;?>
Code: <strong><?php echo $code?></strong>
<br />
<br />
<a href="<?php echo zbase_url_from_route('account', ['action' => 'email','task' => 'update-request','e' => $newEmailAddress,'c' => $code])?>">Click here</a> to Complete the Email address update
</p>

<?php echo zbase_view_render(zbase_view_file_contents('email.footer')); ?>